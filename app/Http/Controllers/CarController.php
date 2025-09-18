<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Car::with('user')->where('is_active', true)->paginate(20);
        return view('cars.index', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trailerTypes = Car::getTrailerTypes();
        return view('cars.create', compact('trailerTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'license_plate' => 'required|string|max:20|unique:cars',
            'max_weight' => 'required|numeric|min:0.1|max:100',
            'trailer_length' => 'required|numeric|min:0.1|max:50',
            'trailer_width' => 'required|numeric|min:0.1|max:10',
            'trailer_height' => 'required|numeric|min:0.1|max:10',
            'trailer_type' => ['required', Rule::in(array_keys(Car::getTrailerTypes()))],
            'vehicle_document' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ]);

        // Вычисляем объем прицепа
        $validated['trailer_volume'] = $validated['trailer_length'] * $validated['trailer_width'] * $validated['trailer_height'];
        $validated['trailer_type_rus'] = Car::getTrailerTypes()[$validated['trailer_type']];
        $validated['user_id'] = Auth::id();

        // Загружаем документ если есть
        if ($request->hasFile('vehicle_document')) {
            $path = $request->file('vehicle_document')->store('vehicle_documents', 'public');
            $validated['vehicle_document'] = $path;
        }

        $car = Car::create($validated);
        
        // Сохраняем локализованные поля в зависимости от текущего языка
        $car->saveLocalizedFields($validated);

        if (Auth::user()->isDriver()) {
            return redirect()->route('cars.my-cars')->with('success', 'Машина успешно добавлена');
        }
        return redirect()->route('cars.all')->with('success', 'Машина успешно добавлена');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $car = Car::with('user')->findOrFail($id);
        return view('cars.show', compact('car'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $car = Car::where('user_id', Auth::id())->findOrFail($id);
        $trailerTypes = Car::getTrailerTypes();
        return view('cars.edit', compact('car', 'trailerTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $car = Car::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'license_plate' => ['required', 'string', 'max:20', Rule::unique('cars')->ignore($car->id)],
            'max_weight' => 'required|numeric|min:0.1|max:100',
            'trailer_length' => 'required|numeric|min:0.1|max:50',
            'trailer_width' => 'required|numeric|min:0.1|max:10',
            'trailer_height' => 'required|numeric|min:0.1|max:10',
            'trailer_type' => ['required', Rule::in(array_keys(Car::getTrailerTypes()))],
            'vehicle_document' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        // Вычисляем объем прицепа
        $validated['trailer_volume'] = $validated['trailer_length'] * $validated['trailer_width'] * $validated['trailer_height'];
        $validated['trailer_type_rus'] = Car::getTrailerTypes()[$validated['trailer_type']];

        // Загружаем новый документ если есть
        if ($request->hasFile('vehicle_document')) {
            // Удаляем старый документ
            if ($car->vehicle_document) {
                Storage::disk('public')->delete($car->vehicle_document);
            }
            $path = $request->file('vehicle_document')->store('vehicle_documents', 'public');
            $validated['vehicle_document'] = $path;
        }

        $car->update($validated);
        
        // Сохраняем локализованные поля в зависимости от текущего языка
        $car->saveLocalizedFields($validated);

        if (Auth::user()->isDriver()) {
            return redirect()->route('cars.my-cars')->with('success', 'Машина успешно обновлена');
        }
        return redirect()->route('cars.all')->with('success', 'Машина успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $car = Car::where('user_id', Auth::id())->findOrFail($id);
        
        // Удаляем документ если есть
        if ($car->vehicle_document) {
            Storage::disk('public')->delete($car->vehicle_document);
        }
        
        $car->delete();

        if (Auth::user()->isDriver()) {
            return redirect()->route('cars.my-cars')->with('success', 'Машина успешно удалена');
        }
        return redirect()->route('cars.all')->with('success', 'Машина успешно удалена');
    }

    /**
     * Показать машины текущего водителя
     */
    public function myCars()
    {
        Log::info('myCars method called', [
            'user_id' => Auth::id(),
            'user_role' => Auth::user()->role,
            'user_approved' => Auth::user()->approved
        ]);
        
        $cars = Auth::user()->cars()->paginate(20);
        
        Log::info('Cars retrieved', [
            'user_id' => Auth::id(),
            'cars_count' => $cars->count()
        ]);
        
        return view('cars.my-cars', compact('cars'));
    }

    /**
     * Активировать/деактивировать машину
     */
    public function toggleStatus(string $id)
    {
        $car = Car::where('user_id', Auth::id())->findOrFail($id);
        $car->update(['is_active' => !$car->is_active]);

        $status = $car->is_active ? 'активирована' : 'деактивирована';
        return redirect()->back()->with('success', "Машина {$status}");
    }
}
