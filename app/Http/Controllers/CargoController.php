<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
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
        
        // Применяем поиск
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('from_location', 'like', "%{$search}%")
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
        
        return view('cargo.index', compact('cargo'));
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
        
        // Получаем грузы, которые забрал водитель
        $cargo = $user->pickedCargo()->latest()->paginate(15);
        
        return view('cargo.my-cargo', compact('cargo'));
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
        
        return view('cargo.create');
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
            'from_location' => 'required|string|max:255',
            'to_location' => 'required|string|max:255',
            'cargo_type' => 'required|string|max:255',
            'volume' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'ready_date' => 'required|date',
            'comment' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'available';

        $cargo = Cargo::create($validated);
        
        // Сохраняем локализованные поля в зависимости от текущего языка
        $cargo->saveLocalizedFields($validated);

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
        
        return view('cargo.edit', compact('cargo'));
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
            'from_location' => 'required|string|max:255',
            'to_location' => 'required|string|max:255',
            'cargo_type' => 'required|string|max:255',
            'volume' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'ready_date' => 'required|date',
            'comment' => 'nullable|string',
        ]);

        $cargo->update($validated);
        
        // Сохраняем локализованные поля в зависимости от текущего языка
        $cargo->saveLocalizedFields($validated);

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
