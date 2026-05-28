<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\CargoApplication;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Serves GET /cargo and GET /cargo/{cargo} for both guests and authenticated users.
 *
 * These routes are registered BEFORE the auth-middleware group in web.php, so they
 * intercept requests for all users. Authenticated users get the same views as the
 * original CargoController would serve; guests get stripped-down public views.
 *
 * The existing Route::resource('cargo', CargoController::class) inside the auth group
 * still owns the non-GET verbs (POST, PUT, DELETE) and named routes cargo.store,
 * cargo.update, cargo.destroy, cargo.create, cargo.edit, cargo.my-cargo. Only the
 * read routes (cargo.index / cargo.show) are now owned here.
 */
class PublicCargoController extends Controller
{
    /**
     * GET /cargo
     *
     * Guests:  show public listing (available only, price hidden).
     * Auth:    delegate to original CargoController logic (same view as before).
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        // ---- Authenticated path ----
        if ($user !== null) {
            return $this->authenticatedIndex($request, $user);
        }

        // ---- Guest path ----
        $suffix = ['ru' => 'rus', 'kz' => 'kaz', 'cn' => 'chn'][app()->getLocale()] ?? 'rus';

        $query = Cargo::available();

        if ($fromCityId = $request->integer('from_city_id')) {
            $query->where('from_location', function ($sub) use ($fromCityId) {
                $sub->select('name')->from('cities')->where('id', $fromCityId)->limit(1);
            });
        }

        if ($toCityId = $request->integer('to_city_id')) {
            $query->where('to_location', function ($sub) use ($toCityId) {
                $sub->select('name')->from('cities')->where('id', $toCityId)->limit(1);
            });
        }

        if ($dateFrom = $request->input('ready_date_from')) {
            $query->whereDate('ready_date', '>=', $dateFrom);
        }

        if ($dateTo = $request->input('ready_date_to')) {
            $query->whereDate('ready_date', '<=', $dateTo);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search, $suffix) {
                $q->where("from_location_{$suffix}", 'like', "%{$search}%")
                  ->orWhere("to_location_{$suffix}", 'like', "%{$search}%")
                  ->orWhere('cargo_type', 'like', "%{$search}%");
            });
        }

        $cargo  = $query->latest()->paginate(15)->withQueryString();
        $cities = City::orderBy('country')->orderBy('name_rus')->get();

        return view('cargo.public-index', compact('cargo', 'cities'));
    }

    /**
     * GET /cargo/{cargo}
     *
     * Guests:  show public detail (available only, price/author hidden).
     * Auth:    delegate to original CargoController logic.
     */
    public function show(Request $request, Cargo $cargo): View
    {
        $user = auth()->user();

        if ($user !== null) {
            return $this->authenticatedShow($user, $cargo);
        }

        // Guest: only available cargo is visible
        if ($cargo->status !== Cargo::STATUS_AVAILABLE) {
            abort(404);
        }

        return view('cargo.public-show', compact('cargo'));
    }

    // -------------------------------------------------------------------------
    // Private: replicate the auth CargoController logic so we don't need a
    // redirect (which would cause an infinite loop since the URL is the same).
    // -------------------------------------------------------------------------

    private function authenticatedIndex(Request $request, $user): View
    {
        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }

        $search = $request->input('search');
        $status = $request->input('status');

        if ($user->isWarehouseEmployee()) {
            $query = $user->createdCargo();
        } else {
            $query = Cargo::available();
        }

        if ($search) {
            $suffix = ['ru' => 'rus', 'kz' => 'kaz', 'cn' => 'chn'][app()->getLocale()] ?? 'rus';
            $query->where(function ($q) use ($search, $suffix) {
                $q->where("from_location_{$suffix}", 'like', "%{$search}%")
                  ->orWhere("to_location_{$suffix}", 'like', "%{$search}%")
                  ->orWhere('from_location', 'like', "%{$search}%")
                  ->orWhere('to_location', 'like', "%{$search}%")
                  ->orWhere('cargo_type', 'like', "%{$search}%");
            });
        }

        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        $cargo = $query->latest()->paginate(15);

        $myApplications = collect();
        if ($user->isDriver()) {
            $cargoIds = $cargo->pluck('id');
            $myApplications = CargoApplication::where('driver_id', $user->id)
                ->whereIn('cargo_id', $cargoIds)
                ->get()
                ->keyBy('cargo_id');
        }

        return view('cargo.index', compact('cargo', 'myApplications'));
    }

    private function authenticatedShow($user, Cargo $cargo): View
    {
        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }

        if (!$user->isAdmin() && $user->isWarehouseEmployee() && $cargo->created_by !== $user->id) {
            abort(403);
        }

        // Eager-load warehouse relations so the show view can display warehouse name
        // and address beneath each city pill without additional queries.
        $cargo->load(['fromWarehouse', 'toWarehouse']);

        return view('cargo.show', compact('cargo'));
    }
}
