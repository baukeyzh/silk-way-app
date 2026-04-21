<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CargoController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        // Проверяем подтверждение аккаунта
        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }
        
        // Получаем параметры поиска и фильтрации
        $search = request('search');
        $status = request('status');
        
        if ($user->isWarehouseEmployee()) {
            // Сотрудник склада видит все созданные им грузы
            $query = $user->createdCargo();
        } else {
            // Водитель видит только доступные грузы
            $query = Cargo::available();
        }
        
        // Применяем поиск по локализованным колонкам
        if ($search) {
            $suffix = ['ru' => 'rus', 'kz' => 'kaz', 'cn' => 'chn'][app()->getLocale()] ?? 'rus';
            $query->where(function($q) use ($search, $suffix) {
                $q->where("from_location_{$suffix}", 'like', "%{$search}%")
                  ->orWhere("to_location_{$suffix}", 'like', "%{$search}%")
                  ->orWhere('from_location', 'like', "%{$search}%")
                  ->orWhere('to_location', 'like', "%{$search}%")
                  ->orWhere('cargo_type', 'like', "%{$search}%");
            });
        }
        
        // Применяем фильтр по статусу
        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }
        
        // Получаем пагинированные результаты
        $cargo = $query->latest()->paginate(15);

        // Для водителя загружаем его заявки чтобы показать статус
        $myApplications = collect();
        if ($user->isDriver()) {
            $cargoIds = $cargo->pluck('id');
            $myApplications = \App\Models\CargoApplication::where('driver_id', $user->id)
                ->whereIn('cargo_id', $cargoIds)
                ->get()
                ->keyBy('cargo_id');
        }

        return view('cargo.index', compact('cargo', 'myApplications'));
    }

    public function myCargo(): View
    {
        $user = auth()->user();
        
        // Проверяем подтверждение аккаунта
        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }
        
        // Только водители могут видеть свои грузы
        if (!$user->isDriver()) {
            abort(403, 'Доступ только для водителей.');
        }
        
        $cargo = $user->pickedCargo()->latest()->paginate(15);

        $applications = $user->cargoApplications()
            ->whereIn('cargo_id', $cargo->pluck('id'))
            ->where('status', \App\Models\CargoApplication::STATUS_APPROVED)
            ->get()
            ->keyBy('cargo_id');

        return view('cargo.my-cargo', compact('cargo', 'applications'));
    }

    public function create(): View
    {
        // Проверяем подтверждение аккаунта
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }
        
        // Только администраторы и сотрудники склада могут создавать грузы
        if (!$user->isAdmin() && !$user->isWarehouseEmployee()) {
            abort(403);
        }
        
        $cities = City::orderBy('country')->orderBy('name_rus')->get()->groupBy('country');

        return view('cargo.create', compact('cities'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Проверяем подтверждение аккаунта
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }
        
        // Только администраторы и сотрудники склада могут создавать грузы
        if (!$user->isAdmin() && !$user->isWarehouseEmployee()) {
            abort(403);
        }

        $validated = $request->validate([
            'from_city_id' => 'required|exists:cities,id',
            'to_city_id'   => 'required|exists:cities,id',
            'cargo_type'   => 'required|string|max:255',
            'volume'       => 'required|numeric|min:0|max:99999999.99',
            'weight'       => 'required|numeric|min:0|max:99999999.99',
            'price_usd'    => 'nullable|numeric|min:0',
            'ready_date'   => 'required|date',
            'comment'      => 'nullable|string',
        ]);

        $fromCity = City::findOrFail($validated['from_city_id']);
        $toCity   = City::findOrFail($validated['to_city_id']);

        $cargo = Cargo::create([
            'from_location'     => $fromCity->name,
            'from_location_rus' => $fromCity->name_rus,
            'from_location_kaz' => $fromCity->name_kaz,
            'from_location_chn' => $fromCity->name_chn,
            'to_location'       => $toCity->name,
            'to_location_rus'   => $toCity->name_rus,
            'to_location_kaz'   => $toCity->name_kaz,
            'to_location_chn'   => $toCity->name_chn,
            'cargo_type'        => $validated['cargo_type'],
            'volume'            => $validated['volume'],
            'weight'            => $validated['weight'],
            'price_usd'         => $validated['price_usd'] ?? null,
            'ready_date'        => $validated['ready_date'],
            'comment'           => $validated['comment'] ?? null,
            'created_by'        => auth()->id(),
            'status'            => Cargo::STATUS_AVAILABLE,
        ]);

        return redirect()->route('cargo.index')->with('success', 'Груз успешно создан!');
    }

    public function show(Cargo $cargo): View
    {
        $user = auth()->user();
        
        // Проверяем подтверждение аккаунта
        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }
        
        // Администраторы могут просматривать любые грузы, остальные - только свои
        if (!$user->isAdmin() && $user->isWarehouseEmployee() && $cargo->created_by !== $user->id) {
            abort(403);
        }
        
        return view('cargo.show', compact('cargo'));
    }

    public function edit(Cargo $cargo): View
    {
        $user = auth()->user();
        
        // Проверяем подтверждение аккаунта
        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }
        
        // Администраторы могут редактировать любые грузы, остальные - только свои
        if (!$user->isAdmin() && (!$user->isWarehouseEmployee() || $cargo->created_by !== $user->id)) {
            abort(403);
        }
        
        $cities    = City::orderBy('country')->orderBy('name_rus')->get()->groupBy('country');
        $fromCity  = City::where('name', $cargo->from_location)->first();
        $toCity    = City::where('name', $cargo->to_location)->first();

        return view('cargo.edit', compact('cargo', 'cities', 'fromCity', 'toCity'));
    }

    public function update(Request $request, Cargo $cargo): RedirectResponse
    {
        $user = auth()->user();
        
        // Проверяем подтверждение аккаунта
        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }
        
        // Администраторы могут редактировать любые грузы, остальные - только свои
        if (!$user->isAdmin() && (!$user->isWarehouseEmployee() || $cargo->created_by !== $user->id)) {
            abort(403);
        }

        $validated = $request->validate([
            'from_city_id' => 'required|exists:cities,id',
            'to_city_id'   => 'required|exists:cities,id',
            'cargo_type'   => 'required|string|max:255',
            'volume'       => 'required|numeric|min:0',
            'weight'       => 'required|numeric|min:0',
            'price_usd'    => 'nullable|numeric|min:0',
            'ready_date'   => 'required|date',
            'comment'      => 'nullable|string',
        ]);

        $fromCity = City::findOrFail($validated['from_city_id']);
        $toCity   = City::findOrFail($validated['to_city_id']);

        $cargo->update([
            'from_location'     => $fromCity->name,
            'from_location_rus' => $fromCity->name_rus,
            'from_location_kaz' => $fromCity->name_kaz,
            'from_location_chn' => $fromCity->name_chn,
            'to_location'       => $toCity->name,
            'to_location_rus'   => $toCity->name_rus,
            'to_location_kaz'   => $toCity->name_kaz,
            'to_location_chn'   => $toCity->name_chn,
            'cargo_type'        => $validated['cargo_type'],
            'volume'            => $validated['volume'],
            'weight'            => $validated['weight'],
            'price_usd'         => $validated['price_usd'] ?? null,
            'ready_date'        => $validated['ready_date'],
            'comment'           => $validated['comment'] ?? null,
        ]);

        return redirect()->route('cargo.index')->with('success', 'Груз успешно обновлен!');
    }

    public function destroy(Cargo $cargo): RedirectResponse
    {
        $user = auth()->user();
        
        // Проверяем подтверждение аккаунта
        if (!$user->isAdmin() && !$user->isApproved()) {
            abort(403, 'Ваш аккаунт еще не подтвержден администратором.');
        }
        
        // Только создатель груза может его удалить
        if (!$user->isWarehouseEmployee() || $cargo->created_by !== $user->id) {
            abort(403);
        }

        $cargo->delete();

        return redirect()->route('cargo.index')->with('success', 'Груз успешно удален!');
    }
}
