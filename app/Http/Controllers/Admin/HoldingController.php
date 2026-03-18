<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHoldingRequest;
use App\Http\Requests\UpdateHoldingRequest;
use App\Models\Gang;
use App\Models\Holding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HoldingController extends Controller
{
    public function index(): View
    {
        $holdings = Holding::with('gang')
            ->latest()
            ->paginate(12);

        return view('admin.holdings.index', compact('holdings'));
    }

    public function create(): View
    {
        $gangs = Gang::whereDoesntHave('holding')->orderBy('name')->get();

        return view('admin.holdings.create', compact('gangs'));
    }

    public function store(StoreHoldingRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        Holding::create($data);

        return redirect()
            ->route('admin.holdings.index')
            ->with('success', 'Holding creado correctamente.');
    }

    public function edit(Holding $holding): View
    {
        $gangs = Gang::orderBy('name')->get();

        return view('admin.holdings.edit', compact('holding', 'gangs'));
    }

    public function update(UpdateHoldingRequest $request, Holding $holding): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $holding->update($data);

        return redirect()
            ->route('admin.holdings.index')
            ->with('success', 'Holding actualizado correctamente.');
    }

    public function destroy(Holding $holding): RedirectResponse
    {
        $holding->delete();

        return redirect()
            ->route('admin.holdings.index')
            ->with('success', 'Holding eliminado correctamente.');
    }
}
