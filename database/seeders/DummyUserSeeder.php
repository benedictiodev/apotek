<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'name' => 'Dummy Company',
            'logo' => null,
            'address' => null,
            'phone_number' => null,
            'type_subscription' => 'basic',
            'subscription_fee' => 30000,
            'expired_date' => "2030-12-31 23:59:59",
            'grace_days_ended_at' => "2031-12-31 23:59:59",
        ]);

        User::factory()->create([
            'name' => 'Dummy User',
            'email' => 'dummyuser@example.com',
            'company_id' => 1,
            'username' => 'dummyuser',
        ]);
    }
}
