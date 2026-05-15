<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_title' => 'NACOS Awards 2026',
            'vote_price' => '5000', // ₦50 in kobo
            'voting_enabled' => '1',
            'event_date' => '2026-06-15 18:00:00',
            'bank_name' => 'First Bank of Nigeria',
            'account_number' => '0123456789',
            'account_name' => 'NACOS Awards Committee',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
