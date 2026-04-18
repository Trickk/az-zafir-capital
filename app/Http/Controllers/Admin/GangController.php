<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGangRequest;
use App\Http\Requests\UpdateGangRequest;
use App\Models\Company;
use App\Models\Gang;
use App\Models\MatrixFund;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GangController extends Controller
{
    public function index(): View
    {
        $gangs = Gang::with(['company', 'matrixFund'])
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

        DB::transaction(function () use ($data) {
            $gang = Gang::create([
                'gang_code' => $this->generateGangCode(),
                'company_id' => $data['company_id'] ?? null,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description'] ?? null,
                'boss_name' => $data['boss_name'] ?? null,
                'contact_discord' => $data['contact_discord'] ?? null,
                'commission_percent' => $data['commission_percent'] ?? 10,
                'matrix_percent' => $data['matrix_percent'] ?? 10,
                'operating_balance' => 0,
                'status' => $data['status'],
            ]);

            MatrixFund::create([
                'gang_id' => $gang->id,
                'balance' => 0,
                'total_in' => 0,
                'total_out' => 0,
            ]);
        });

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
            'company_id' => $data['company_id'] ?? null,
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'boss_name' => $data['boss_name'] ?? null,
            'contact_discord' => $data['contact_discord'] ?? null,
            'commission_percent' => $data['commission_percent'] ?? 10,
            'matrix_percent' => $data['matrix_percent'] ?? 10,
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
        } while (Gang::withTrashed()->where('gang_code', $code)->exists());

        return $code;
    }
}
