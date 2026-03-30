<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Gang;
use Illuminate\Console\Command;

class BackfillEntityCodes extends Command
{
    protected $signature = 'app:backfill-entity-codes';
    protected $description = 'Genera códigos únicos para empresas y bandas existentes';

    public function handle(): int
    {
        Company::withTrashed()->whereNull('company_code')->get()->each(function ($company) {
            $company->updateQuietly([
                'company_code' => $this->generateCompanyCode(),
            ]);
            $this->info($company->company_code);
        });

        Gang::withTrashed()->whereNull('gang_code')->get()->each(function ($gang) {
            $gang->updateQuietly([
                'gang_code' => $this->generateGangCode(),
            ]);
            $this->info($gang->gang_code);
        });

        $this->info('Códigos generados correctamente.');

        return self::SUCCESS;
    }

    private function generateCompanyCode(): string
    {
        do {
            $code = 'COM-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while (Company::withTrashed()->where('company_code', $code)->exists());

        return $code;
    }

    private function generateGangCode(): string
    {
        do {
            $code = 'GAN-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while (Gang::withTrashed()->where('gang_code', $code)->exists());

        return $code;
    }
}
