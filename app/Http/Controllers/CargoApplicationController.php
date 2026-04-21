<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\CargoApplication;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CargoApplicationController extends Controller
{
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
        $allowedStatuses = ['pending', 'approved', 'rejected'];
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
            'pending'  => (clone $baseQuery)->where('status', 'pending')->count(),
            'approved' => (clone $baseQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
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
            if ($cargo->status !== 'available') {
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
                'status'       => 'pending',
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

        // Обновляем заявку
        $application->update([
            'status' => 'approved',
            'warehouse_notes' => $validated['warehouse_notes'] ?? null,
            'contact_whatsapp' => $validated['contact_whatsapp'] ?? null,
            'contact_wechat' => $validated['contact_wechat'] ?? null,
            'pickup_contact' => $validated['pickup_contact'] ?? null,
            'pickup_address' => $validated['pickup_address'] ?? null,
            'delivery_contact' => $validated['delivery_contact'] ?? null,
            'delivery_address' => $validated['delivery_address'] ?? null,
            'approved_at' => now(),
            'approved_by' => $user->id,
        ]);

        // Обновляем статус груза
        $application->cargo->update([
            'status' => 'in_progress',
            'picked_by' => $application->driver_id,
            'picked_at' => now(),
        ]);

        // Отклоняем все остальные заявки на этот груз
        $application->cargo->applications()
            ->where('id', '!=', $application->id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Заявка водителя подтверждена!');
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

        $application->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Заявка водителя отклонена.');
    }

    public function markAsDelivered(CargoApplication $application): RedirectResponse
    {
        $user = auth()->user();
        
        // Проверяем подтверждение аккаунта
        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }
        
        // Только водители могут отмечать грузы как доставленные
        if (!$user->isDriver()) {
            abort(403);
        }
        
        // Проверяем, что заявка принадлежит этому водителю
        if ($application->driver_id !== $user->id) {
            abort(403, 'Вы можете отмечать только свои грузы как доставленные.');
        }
        
        // Проверяем, что заявка подтверждена
        if (!$application->isApproved()) {
            return redirect()->route('cargo.my-applications')->with('error', 'Эта заявка еще не подтверждена!');
        }

        // Обновляем статус груза
        $application->cargo->update(['status' => 'delivered']);

        return redirect()->route('applications.my-applications')->with('success', 'Груз отмечен как доставленный!');
    }
}
