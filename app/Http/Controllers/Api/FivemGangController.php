<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gang;
use App\Models\MatrixFund;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FivemGangController extends Controller
{
    public function index(): JsonResponse
    {
        $gangs = Gang::with(['company', 'matrixFund'])
            ->latest()->get()
            ->map(fn($g) => [
                'id'                 => $g->id,
                'gang_code'          => $g->gang_code,
                'name'               => $g->name,
                'boss_name'          => $g->boss_name,
                'contact_discord'    => $g->contact_discord,
                'company_id'         => $g->company_id,
                'company_name'       => $g->company?->name,
                'commission_percent' => (float) $g->commission_percent,
                'matrix_percent'     => (float) $g->matrix_percent,
                'operating_balance'  => (float) $g->operating_balance,
                'matrix_balance'     => (float) ($g->matrixFund?->balance ?? 0),
                'status'             => $g->status,
                'description'        => $g->description,
            ]);

        return response()->json(['data' => $gangs]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'               => 'required|string|max:150',
            'boss_name'          => 'nullable|string|max:150',
            'contact_discord'    => 'nullable|string|max:150',
            'company_id'         => 'nullable|exists:companies,id',
            'commission_percent' => 'nullable|numeric|min:0|max:100',
            'matrix_percent'     => 'nullable|numeric|min:0|max:100',
            'description'        => 'nullable|string',
            'status'             => 'nullable|in:active,inactive,suspended',
        ]);

        DB::transaction(function () use ($data) {
            $gang = Gang::create([
                'gang_code'          => $this->generateGangCode(),
                'company_id'         => $data['company_id'] ?? null,
                'name'               => $data['name'],
                'slug'               => Str::slug($data['name']),
                'description'        => $data['description'] ?? null,
                'boss_name'          => $data['boss_name'] ?? null,
                'contact_discord'    => $data['contact_discord'] ?? null,
                'commission_percent' => $data['commission_percent'] ?? 10,
                'matrix_percent'     => $data['matrix_percent'] ?? 10,
                'operating_balance'  => 0,
                'status'             => $data['status'] ?? 'active',
            ]);
            MatrixFund::create([
                'gang_id'  => $gang->id,
                'balance'  => 0,
                'total_in' => 0,
                'total_out'=> 0,
            ]);
        });

        return response()->json(['success' => true], 201);
    }

    public function update(Request $request, Gang $gang): JsonResponse
    {
        $data = $request->validate([
            'name'               => 'required|string|max:150',
            'boss_name'          => 'nullable|string|max:150',
            'contact_discord'    => 'nullable|string|max:150',
            'company_id'         => 'nullable|exists:companies,id',
            'commission_percent' => 'nullable|numeric|min:0|max:100',
            'matrix_percent'     => 'nullable|numeric|min:0|max:100',
            'description'        => 'nullable|string',
            'status'             => 'nullable|in:active,inactive,suspended',
        ]);

        $gang->update([
            'company_id'         => $data['company_id'] ?? null,
            'name'               => $data['name'],
            'slug'               => Str::slug($data['name']),
            'description'        => $data['description'] ?? null,
            'boss_name'          => $data['boss_name'] ?? null,
            'contact_discord'    => $data['contact_discord'] ?? null,
            'commission_percent' => $data['commission_percent'] ?? 10,
            'matrix_percent'     => $data['matrix_percent'] ?? 10,
            'status'             => $data['status'] ?? 'active',
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(Gang $gang): JsonResponse
    {
        $gang->delete();
        return response()->json(['success' => true]);
    }

    private function generateGangCode(): string
    {
        do {
            $code = 'GAN-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while (Gang::withTrashed()->where('gang_code', $code)->exists());
        return $code;
    }
}
