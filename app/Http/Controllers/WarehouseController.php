<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Models\City;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $query = Warehouse::with(['city', 'creator']);

        if ($user->isWarehouseEmployee()) {
            // WE sees only their own rows — no filter UI
            $query->where('created_by', $user->id);
        } else {
            // Admin: optional city and creator filters
            if ($cityId = $request->query('city_id')) {
                $request->validate(['city_id' => 'exists:cities,id']);
                $query->where('city_id', (int) $cityId);
            }

            if ($creatorId = $request->query('creator_id')) {
                $request->validate(['creator_id' => 'exists:users,id']);
                $query->where('created_by', (int) $creatorId);
            }
        }

        $warehouses = $query->latest()->paginate(20)->withQueryString();

        $cities   = $user->isAdmin() ? City::orderBy('name_rus')->get() : collect();
        $creators = $user->isAdmin()
            ? \App\Models\User::whereIn('role', [
                \App\Models\User::ROLE_ADMIN,
                \App\Models\User::ROLE_WAREHOUSE_EMPLOYEE,
            ])->orderBy('name')->get()
            : collect();

        return view('warehouses.index', compact('warehouses', 'cities', 'creators'));
    }

    public function create(Request $request): View
    {
        $cities          = City::orderBy('country')->orderBy('name_rus')->get()->groupBy('country');
        $preselectedCity = $request->query('city_id');

        return view('warehouses.create', compact('cities', 'preselectedCity'));
    }

    public function store(StoreWarehouseRequest $request): RedirectResponse
    {
        $this->authorize('create', Warehouse::class);

        $data = $request->validated();

        // name defaults to name_rus if not supplied separately
        $data['name']       = $data['name_rus'];
        $data['created_by'] = auth()->id();

        Warehouse::create($data);

        return redirect()->route('warehouses.index')
            ->with('success', translate('warehouse.save') . ' — ' . $data['name_rus']);
    }

    public function edit(Warehouse $warehouse): View
    {
        $this->authorize('view', $warehouse);

        $cities = City::orderBy('country')->orderBy('name_rus')->get()->groupBy('country');

        return view('warehouses.edit', compact('warehouse', 'cities'));
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse): RedirectResponse
    {
        $this->authorize('update', $warehouse);

        $data         = $request->validated();
        $data['name'] = $data['name_rus'];

        $warehouse->update($data);

        return redirect()->route('warehouses.index')
            ->with('success', translate('warehouse.save') . ' — ' . $warehouse->name_rus);
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        $this->authorize('delete', $warehouse);

        $cargoCount = $warehouse->cargoCount();

        if ($cargoCount > 0) {
            return redirect()->back()
                ->withErrors(['warehouse' => translate('warehouse.delete_blocked', ['count' => $cargoCount])]);
        }

        $warehouse->delete();

        return redirect()->route('warehouses.index')
            ->with('success', 'Склад удалён.');
    }
}
