<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGangRequest;
use App\Http\Requests\UpdateGangRequest;
use App\Models\Company;
use App\Models\Gang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GangController extends Controller
{
    public function index(): View
    {
        $gangs = Gang::with('company')
            ->latest()
            ->paginate(12);

        return view('admin.gangs.index', compact('gangs'));
    }

    public function create(): View
    {
        $companies = Company::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.gangs.create', compact('companies'));
    }

    public function store(StoreGangRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Gang::create([
            'gang_code' => $this->generateGangCode(),
            'company_id' => $data['company_id'] ?? null,
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'boss_name' => $data['boss_name'] ?? null,
            'contact_discord' => $data['contact_discord'] ?? null,
            'commission_percent' => $data['commission_percent'],
            'status' => $data['status'],
        ]);

        return redirect()
            ->route('admin.gangs.index')
            ->with('success', 'Banda creada correctamente.');
    }

    public function edit(Gang $gang): View
    {
        $companies = Company::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.gangs.edit', compact('gang', 'companies'));
    }

    public function update(UpdateGangRequest $request, Gang $gang): RedirectResponse
    {
        $data = $request->validated();

        $gang->update([
            'description' => $data['description'] ?? null,
            'boss_name' => $data['boss_name'] ?? null,
            'contact_discord' => $data['contact_discord'] ?? null,
            'commission_percent' => $data['commission_percent'],
            'status' => $data['status'],
        ]);

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

    private function generateGangCode(): string
    {
        do {
            $code = 'GAN-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while (\App\Models\Gang::withTrashed()->where('gang_code', $code)->exists());

        return $code;
    }
}
