<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CityController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $cities = City::query()
            ->when($request->search, fn($q) => $q
                ->where('name', 'like', "%{$request->search}%")
                ->orWhere('name_rus', 'like', "%{$request->search}%")
                ->orWhere('name_kaz', 'like', "%{$request->search}%")
                ->orWhere('name_chn', 'like', "%{$request->search}%")
            )
            ->when($request->country, fn($q) => $q->where('country', $request->country))
            ->orderBy('country')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('cities.index', compact('cities'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        return view('cities.create');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'name_rus' => 'required|string|max:255',
            'name_kaz' => 'required|string|max:255',
            'name_chn' => 'required|string|max:255',
            'country'  => 'required|in:kz,ru,cn',
        ]);

        City::create($validated);

        return redirect()->route('cities.index')->with('success', 'Город добавлен.');
    }

    public function edit(City $city): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        return view('cities.edit', compact('city'));
    }

    public function update(Request $request, City $city): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'name_rus' => 'required|string|max:255',
            'name_kaz' => 'required|string|max:255',
            'name_chn' => 'required|string|max:255',
            'country'  => 'required|in:kz,ru,cn',
        ]);

        $city->update($validated);

        return redirect()->route('cities.index')->with('success', 'Город обновлён.');
    }

    public function destroy(City $city): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $city->delete();

        return redirect()->route('cities.index')->with('success', 'Город удалён.');
    }
}
