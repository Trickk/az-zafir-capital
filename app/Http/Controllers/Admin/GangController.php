<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGangRequest;
use App\Http\Requests\UpdateGangRequest;
use App\Models\Gang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GangController extends Controller
{
    public function index(): View
    {
        $gangs = Gang::query()
            ->latest()
            ->paginate(12);

        return view('admin.gangs.index', compact('gangs'));
    }

    public function create(): View
    {
        return view('admin.gangs.create');
    }

    public function store(StoreGangRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['slug'] = Str::slug($data['name']);

        Gang::create($data);

        return redirect()
            ->route('admin.gangs.index')
            ->with('success', 'Banda creada correctamente.');
    }

    public function edit(Gang $gang): View
    {
        return view('admin.gangs.edit', compact('gang'));
    }

    public function update(UpdateGangRequest $request, Gang $gang): RedirectResponse
    {
        $data = $request->validated();

        $data['slug'] = Str::slug($data['name']);

        $gang->update($data);

        return redirect()
            ->route('admin.gangs.index')
            ->with('success', 'Banda actualizada correctamente.');
    }

    public function destroy(Gang $gang): RedirectResponse
    {
        $gang->delete();

        return redirect()
            ->route('admin.gangs.index')
            ->with('success', 'Banda eliminada correctamente.');
    }
}
