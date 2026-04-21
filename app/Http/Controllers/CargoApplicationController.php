<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesCmrUploads;
use App\Models\Cargo;
use App\Models\CargoApplication;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CargoApplicationController extends Controller
{
    use HandlesCmrUploads;
    public function index(Request $request): View
    {
        $user = auth()->user();

        // Проверяем подтверждение аккаунта
        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }

        // Только админы и сотрудники склада могут видеть все заявки
        if (!$user->isAdmin() && !$user->isWarehouseEmployee()) {
            abort(403, 'Доступ только для администраторов и сотрудников склада.');
        }

        // Whitelist allowed status values; anything outside the set is treated as "all"
        $allowedStatuses = [
            CargoApplication::STATUS_PENDING,
            CargoApplication::STATUS_APPROVED,
            CargoApplication::STATUS_REJECTED,
        ];
        $status = in_array($request->query('status'), $allowedStatuses, true)
            ? $request->query('status')
            : null; // null means "all"

        $search = trim((string) $request->query('search', ''));

        // Base query scoped by role
        $baseQuery = CargoApplication::with(['cargo', 'driver', 'approvedBy']);

        if (!$user->isAdmin()) {
            // Сотрудники склада видят заявки только на свои грузы
            $baseQuery->whereHas('cargo', fn ($q) => $q->where('created_by', $user->id));
        }

        // Compute per-status counts on the role-scoped query before applying filters
        $counts = [
            'all'      => (clone $baseQuery)->count(),
            'pending'  => (clone $baseQuery)->where('status', CargoApplication::STATUS_PENDING)->count(),
            'approved' => (clone $baseQuery)->where('status', CargoApplication::STATUS_APPROVED)->count(),
            'rejected' => (clone $baseQuery)->where('status', CargoApplication::STATUS_REJECTED)->count(),
        ];

        // Apply status filter
        if ($status !== null) {
            $baseQuery->where('status', $status);
        }

        // Apply search filter: match on driver name or email
        if ($search !== '') {
            $baseQuery->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $applications = $baseQuery
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('applications.index', compact('applications', 'counts', 'status', 'search'));
    }

    public function apply(Request $request, Cargo $cargo): RedirectResponse
    {
        $user = auth()->user();

        // Проверяем подтверждение аккаунта
        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }

        // Только водители могут подавать заявки
        if (!$user->isDriver()) {
            abort(403, 'Только водители могут подавать заявки на грузы.');
        }

        $validated = $request->validate([
            'driver_notes' => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use ($cargo, $user, $validated) {
            // Блокируем строку груза для предотвращения гонки потоков
            $cargo = Cargo::where('id', $cargo->id)->lockForUpdate()->firstOrFail();

            // Проверяем, что груз доступен (статус)
            if ($cargo->status !== Cargo::STATUS_AVAILABLE) {
                return back()->withErrors(['cargo' => translate('applications.cargo_already_taken')]);
            }

            // Защитная проверка: груз не должен иметь одобренную заявку
            if ($cargo->hasApprovedApplication()) {
                return back()->withErrors(['cargo' => translate('applications.cargo_already_taken')]);
            }

            // Проверяем, что водитель еще не подавал заявку на этот груз
            if ($cargo->applications()->where('driver_id', $user->id)->exists()) {
                return back()->withErrors(['cargo' => translate('applications.already_applied')]);
            }

            CargoApplication::create([
                'cargo_id'     => $cargo->id,
                'driver_id'    => $user->id,
                'car_id'       => $user->cars->first()?->id,
                'status'       => CargoApplication::STATUS_PENDING,
                'driver_notes' => $validated['driver_notes'] ?? null,
            ]);

            return redirect()->route('cargo.index')->with('success', 'Заявка на груз успешно подана!');
        });
    }

    public function myApplications(): View
    {
        $user = auth()->user();
        
        // Проверяем подтверждение аккаунта
        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }
        
        // Только водители могут видеть свои заявки
        if (!$user->isDriver()) {
            abort(403, 'Доступ только для водителей.');
        }
        
        $pendingApplications = $user->getPendingApplications();
        $approvedApplications = $user->getApprovedApplications();
        $rejectedApplications = $user->getRejectedApplications();
        
        return view('cargo.my-applications', compact('pendingApplications', 'approvedApplications', 'rejectedApplications'));
    }

    public function showApplication(CargoApplication $application): View
    {
        $user = auth()->user();
        
        // Проверяем подтверждение аккаунта
        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }
        
        // Проверяем права доступа
        if ($user->isDriver() && $application->driver_id !== $user->id) {
            abort(403, 'Вы можете просматривать только свои заявки.');
        }
        
        if ($user->isWarehouseEmployee() && $application->cargo->created_by !== $user->id) {
            abort(403, 'Вы можете просматривать заявки только на свои грузы.');
        }
        
        return view('cargo.show-application', compact('application'));
    }

    public function approveApplication(Request $request, CargoApplication $application): RedirectResponse
    {
        $user = auth()->user();
        
        // Проверяем подтверждение аккаунта
        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }
        
        // Только сотрудники склада и администраторы могут подтверждать заявки
        if (!$user->isWarehouseEmployee() && !$user->isAdmin()) {
            abort(403, 'Только сотрудники склада могут подтверждать заявки.');
        }

        // Проверяем, что груз принадлежит этому сотруднику склада (для не-администраторов)
        if (!$user->isAdmin() && $application->cargo->created_by !== $user->id) {
            abort(403, 'Вы можете подтверждать заявки только на свои грузы.');
        }
        
        // Проверяем, что заявка в статусе pending
        if (!$application->isPending()) {
            return redirect()->back()->with('error', 'Эта заявка уже обработана.');
        }

        $validated = $request->validate([
            'warehouse_notes' => 'nullable|string|max:1000',
            'contact_whatsapp' => 'nullable|string|max:255',
            'contact_wechat' => 'nullable|string|max:255',
            'pickup_contact' => 'nullable|string|max:255',
            'pickup_address' => 'nullable|string|max:500',
            'delivery_contact' => 'nullable|string|max:255',
            'delivery_address' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($application, $validated, $user): RedirectResponse {
            // Re-fetch with a row-level lock to prevent two concurrent approvals
            // from landing on the same cargo (e.g. two admins approving at the same time).
            $application = CargoApplication::where('id', $application->id)->lockForUpdate()->firstOrFail();

            // Defensive re-check: another admin may have acted between the initial
            // isPending() guard above and acquiring the lock here.
            if (!$application->isPending()) {
                return redirect()->back()->withErrors(['application' => 'Эта заявка уже обработана другим пользователем.']);
            }

            // Lock the cargo row to prevent the same cargo being approved via
            // a different concurrent application.
            $cargo = Cargo::where('id', $application->cargo_id)->lockForUpdate()->firstOrFail();

            if ($cargo->status !== Cargo::STATUS_AVAILABLE) {
                return redirect()->back()->withErrors(['cargo' => 'Груз уже не доступен — другая заявка была подтверждена первой.']);
            }

            $application->update([
                'status'           => CargoApplication::STATUS_APPROVED,
                'warehouse_notes'  => $validated['warehouse_notes'] ?? null,
                'contact_whatsapp' => $validated['contact_whatsapp'] ?? null,
                'contact_wechat'   => $validated['contact_wechat'] ?? null,
                'pickup_contact'   => $validated['pickup_contact'] ?? null,
                'pickup_address'   => $validated['pickup_address'] ?? null,
                'delivery_contact' => $validated['delivery_contact'] ?? null,
                'delivery_address' => $validated['delivery_address'] ?? null,
                'approved_at'      => now(),
                'approved_by'      => $user->id,
            ]);

            $cargo->update([
                'status'    => Cargo::STATUS_IN_PROGRESS,
                'picked_by' => $application->driver_id,
                'picked_at' => now(),
            ]);

            $cargo->applications()
                ->where('id', '!=', $application->id)
                ->where('status', CargoApplication::STATUS_PENDING)
                ->update(['status' => CargoApplication::STATUS_REJECTED]);

            return redirect()->back()->with('success', 'Заявка водителя подтверждена!');
        });
    }

    public function rejectApplication(CargoApplication $application): RedirectResponse
    {
        $user = auth()->user();
        
        // Проверяем подтверждение аккаунта
        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }
        
        // Только сотрудники склада и администраторы могут отклонять заявки
        if (!$user->isWarehouseEmployee() && !$user->isAdmin()) {
            abort(403, 'Только сотрудники склада могут отклонять заявки.');
        }

        // Проверяем, что груз принадлежит этому сотруднику склада (для не-администраторов)
        if (!$user->isAdmin() && $application->cargo->created_by !== $user->id) {
            abort(403, 'Вы можете отклонять заявки только на свои грузы.');
        }
        
        // Проверяем, что заявка в статусе pending
        if (!$application->isPending()) {
            return redirect()->back()->with('error', 'Эта заявка уже обработана.');
        }

        $application->update(['status' => CargoApplication::STATUS_REJECTED]);

        return redirect()->back()->with('success', 'Заявка водителя отклонена.');
    }

    /**
     * markAsDelivered is superseded by the CMR confirmation flow.
     * We keep the route alive and redirect the driver to the CMR upload path
     * so mobile deep-links and existing bookmarks don't 404.
     */
    public function markAsDelivered(CargoApplication $application): RedirectResponse
    {
        $user = auth()->user();

        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }

        if (!$user->isDriver()) {
            abort(403);
        }

        if ($application->driver_id !== $user->id) {
            abort(403, 'Вы можете отмечать только свои грузы как доставленные.');
        }

        // Redirect to the CMR upload form. The UX agent renders the form there.
        return redirect()->route('applications.show', $application)
            ->with('info', 'Для завершения доставки загрузите CMR-документ.');
    }

    // ── CMR flow ──────────────────────────────────────────────────────────────

    /**
     * Driver uploads a CMR file. Allowed when application is approved and CMR
     * is not_uploaded or rejected (i.e. re-upload after rejection).
     */
    public function uploadCmr(Request $request, CargoApplication $application): RedirectResponse
    {
        $user = auth()->user();

        abort_unless($user->isApproved() || $user->isAdmin(), 403, translate('cmr.access_denied'));
        abort_unless($user->isDriver(), 403, translate('cmr.access_denied'));
        abort_unless($application->driver_id === $user->id, 403, translate('cmr.access_denied'));
        abort_unless($application->canDriverUploadCmr(), 409, translate('cmr.invalid_state'));

        $request->validate([
            'cmr_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ], [
            'cmr_file.required' => translate('cmr.validation_file_required'),
        ]);

        $this->performCmrUpload($application, $request->file('cmr_file'));

        return redirect()->back()->with('success', translate('cmr.upload_success'));
    }

    /**
     * WE-of-cargo or admin confirms the uploaded CMR. Atomically moves cargo
     * to delivered status.
     */
    public function confirmCmr(Request $request, CargoApplication $application): RedirectResponse
    {
        $user = auth()->user();

        abort_unless($user->isApproved() || $user->isAdmin(), 403, translate('cmr.access_denied'));
        abort_unless(
            $user->isAdmin() || $application->cargo->created_by === $user->id,
            403,
            translate('cmr.access_denied')
        );
        abort_unless($application->canReviewerActOnCmr(), 409, translate('cmr.invalid_state'));

        $this->performCmrConfirm($application, $user);

        return redirect()->back()->with('success', translate('cmr.confirmed_success'));
    }

    /**
     * WE-of-cargo or admin rejects the CMR with a mandatory reason.
     */
    public function rejectCmr(Request $request, CargoApplication $application): RedirectResponse
    {
        $user = auth()->user();

        abort_unless($user->isApproved() || $user->isAdmin(), 403, translate('cmr.access_denied'));
        abort_unless(
            $user->isAdmin() || $application->cargo->created_by === $user->id,
            403,
            translate('cmr.access_denied')
        );
        abort_unless($application->canReviewerActOnCmr(), 409, translate('cmr.invalid_state'));

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ], [
            'rejection_reason.required' => translate('cmr.validation_reason_required'),
        ]);

        $this->performCmrReject($application, $request->input('rejection_reason'));

        return redirect()->back()->with('success', translate('cmr.rejected_success'));
    }

    /**
     * Driver deletes their own uploaded CMR before it has been reviewed.
     * Confirmed CMRs are immutable.
     */
    public function destroyCmr(CargoApplication $application): RedirectResponse
    {
        $user = auth()->user();

        abort_unless($user->isApproved() || $user->isAdmin(), 403, translate('cmr.access_denied'));
        abort_unless($user->isDriver(), 403, translate('cmr.access_denied'));
        abort_unless($application->driver_id === $user->id, 403, translate('cmr.access_denied'));
        abort_unless(!$application->isCmrLocked(), 409, translate('cmr.already_confirmed'));
        abort_unless(
            in_array($application->cmr_status, [
                CargoApplication::CMR_STATUS_PENDING_REVIEW,
                CargoApplication::CMR_STATUS_REJECTED,
            ], true),
            409,
            translate('cmr.invalid_state')
        );

        $this->performCmrDestroy($application);

        return redirect()->back()->with('success', translate('cmr.deleted_success'));
    }

    /**
     * Stream the stored CMR file to an authorised viewer.
     * Authorised: driver-owner, WE-of-cargo, any admin.
     */
    public function cmrFile(CargoApplication $application): StreamedResponse
    {
        $user = auth()->user();

        $isDriverOwner  = $user->isDriver() && $application->driver_id === $user->id;
        $isCargoOwner   = $user->isWarehouseEmployee() && $application->cargo->created_by === $user->id;
        $isAdmin        = $user->isAdmin();

        abort_unless($isDriverOwner || $isCargoOwner || $isAdmin, 403, translate('cmr.access_denied'));
        abort_unless($application->cmr_file_path, 404);
        abort_unless(Storage::disk('local')->exists($application->cmr_file_path), 404);

        return Storage::disk('local')->response(
            $application->cmr_file_path,
            $application->cmr_original_filename ?? 'cmr_document'
        );
    }
}
