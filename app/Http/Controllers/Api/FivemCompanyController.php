<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FivemCompanyController extends Controller
{
    public function index(): JsonResponse
    {
        $companies = Company::latest()->get()->map(fn($c) => [
            'id'               => $c->id,
            'company_code'     => $c->company_code,
            'name'             => $c->name,
            'legal_name'       => $c->legal_name,
            'type'             => $c->type,
            'country'          => $c->country,
            'city'             => $c->city,
            'address'          => $c->address,
            'responsible_name' => $c->responsible_name,
            'description'      => $c->description,
            'status'           => $c->status,
        ]);

        return response()->json(['data' => $companies]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'             => 'required|string|max:180|unique:companies,name',
            'legal_name'       => 'nullable|string|max:220',
            'type'             => 'nullable|in:cultural,logistics,hospitality,investment,entertainment,security,technology,trading',
            'country'          => 'nullable|string|max:100',
            'city'             => 'nullable|string|max:120',
            'address'          => 'nullable|string|max:255',
            'responsible_name' => 'nullable|string|max:150',
            'description'      => 'nullable|string',
            'status'           => 'nullable|in:active,inactive',
        ]);

        Company::create([
            'company_code'     => $this->generateCompanyCode(),
            'name'             => $data['name'],
            'slug'             => Str::slug($data['name']),
            'legal_name'       => $data['legal_name'] ?? null,
            'type'             => $data['type'] ?? 'investment',
            'country'          => $data['country'] ?? null,
            'city'             => $data['city'] ?? null,
            'address'          => $data['address'] ?? null,
            'tax_id'           => $this->generateTaxId($data['country'] ?? null),
            'responsible_name' => $data['responsible_name'] ?? null,
            'description'      => $data['description'] ?? null,
            'status'           => $data['status'] ?? 'active',
        ]);

        return response()->json(['success' => true], 201);
    }

    public function update(Request $request, Company $company): JsonResponse
    {
        $data = $request->validate([
            'name'             => "required|string|max:180|unique:companies,name,{$company->id}",
            'legal_name'       => 'nullable|string|max:220',
            'type'             => 'nullable|in:cultural,logistics,hospitality,investment,entertainment,security,technology,trading',
            'country'          => 'nullable|string|max:100',
            'city'             => 'nullable|string|max:120',
            'address'          => 'nullable|string|max:255',
            'responsible_name' => 'nullable|string|max:150',
            'description'      => 'nullable|string',
            'status'           => 'nullable|in:active,inactive',
        ]);

        $company->update([
            'name'             => $data['name'],
            'slug'             => Str::slug($data['name']),
            'legal_name'       => $data['legal_name'] ?? null,
            'type'             => $data['type'] ?? $company->type,
            'country'          => $data['country'] ?? null,
            'city'             => $data['city'] ?? null,
            'address'          => $data['address'] ?? null,
            'responsible_name' => $data['responsible_name'] ?? null,
            'description'      => $data['description'] ?? null,
            'status'           => $data['status'] ?? $company->status,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(Company $company): JsonResponse
    {
        $company->delete();
        return response()->json(['success' => true]);
    }

    private function generateCompanyCode(): string
    {
        do {
            $code = 'COM-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while (Company::withTrashed()->where('company_code', $code)->exists());
        return $code;
    }

    private function generateTaxId(?string $country = null): string
    {
        $prefix = match (strtoupper((string) $country)) {
            'UAE'                         => 'UAE',
            'ESPAÑA', 'SPAIN', 'ES'      => 'ES',
            'UK', 'UNITED KINGDOM', 'GB' => 'UK',
            'USA', 'US'                  => 'US',
            default                      => 'INT',
        };
        do {
            $taxId = sprintf('%s-AZ-%s', $prefix, strtoupper(substr(bin2hex(random_bytes(4)), 0, 8)));
        } while (Company::withTrashed()->where('tax_id', $taxId)->exists());
        return $taxId;
    }
}
