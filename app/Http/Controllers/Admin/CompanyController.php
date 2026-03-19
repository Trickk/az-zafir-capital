<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        $companies = Company::latest()->paginate(12);

        return view('admin.companies.index', compact('companies'));
    }

    public function create(): View
    {
        return view('admin.companies.create');
    }

    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $logoPath = null;
        $invoiceImagePath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('companies/logos', 'public');
        }

        if ($request->hasFile('invoice_image')) {
            $invoiceImagePath = $request->file('invoice_image')->store('companies/invoice-images', 'public');
        }

        Company::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'legal_name' => $data['legal_name'] ?? null,
            'type' => $data['type'],
            'country' => $data['country'] ?? null,
            'city' => $data['city'] ?? null,
            'address' => $data['address'] ?? null,
            'tax_id' => $this->generateTaxId($data['country'] ?? null),
            'responsible_name' => $data['responsible_name'] ?? null,
            'description' => $data['description'] ?? null,
            'logo_path' => $logoPath,
            'invoice_image_path' => $invoiceImagePath,
            'status' => $data['status'],
        ]);

        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Empresa creada correctamente.');
    }

    public function edit(Company $company): View
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(UpdateCompanyRequest $request, Company $company): RedirectResponse
    {
        $data = $request->validated();

        $logoPath = $company->logo_path;
        $invoiceImagePath = $company->invoice_image_path;

        if ($request->hasFile('logo')) {
            if ($company->logo_path) {
                //Storage::disk('public')->delete($company->logo_path);
            }

            $logoPath = $request->file('logo')->store('companies/logos', 'public');
        }

        if ($request->hasFile('invoice_image')) {
            if ($company->invoice_image_path) {
                //Storage::disk('public')->delete($company->invoice_image_path);
            }

            $invoiceImagePath = $request->file('invoice_image')->store('companies/invoice-images', 'public');
        }
        $tax_id = null;
        if($company->tax_id == null){
            $tax_id = $this->generateTaxId($data['country'] ?? null);
        }else{
            $tax_id = $company->tax_id;
        }

        $company->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'legal_name' => $data['legal_name'] ?? null,
            'type' => $data['type'],
            'country' => $data['country'] ?? null,
            'city' => $data['city'] ?? null,
            'address' => $data['address'] ?? null,
            'tax_id' => $tax_id ?? null,
            'responsible_name' => $data['responsible_name'] ?? null,
            'description' => $data['description'] ?? null,
            'logo_path' => $logoPath,
            'invoice_image_path' => $invoiceImagePath,
            'status' => $data['status'],
        ]);

        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Empresa actualizada correctamente.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        if ($company->logo_path) {
            //Storage::disk('public')->delete($company->logo_path);
        }

        if ($company->invoice_image_path) {
            //Storage::disk('public')->delete($company->invoice_image_path);
        }

        $company->delete();

        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Empresa eliminada correctamente.');
    }

    private function generateTaxId(?string $country = null): string
{
    $prefix = match (strtoupper((string) $country)) {
        'UAE' => 'UAE',
        'ESPAÑA', 'SPAIN', 'ES' => 'ES',
        'UK', 'UNITED KINGDOM', 'GB' => 'UK',
        'USA', 'US' => 'US',
        default => 'INT',
    };

    do {
        $taxId = sprintf(
            '%s-AZ-%s',
            $prefix,
            strtoupper(substr(bin2hex(random_bytes(4)), 0, 8))
        );
    } while (\App\Models\Company::withTrashed()->where('tax_id', $taxId)->exists());

    return $taxId;
}
}
