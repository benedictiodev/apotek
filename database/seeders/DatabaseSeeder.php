<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\MasterCustomer;
use App\Models\MasterEmployee;
use App\Models\MasterPaymentMethod;
use App\Models\MasterProductCategory;
use App\Models\MasterSales;
use App\Models\MasterSupplier;
use App\Models\MasterUom;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $company = Company::query()->updateOrCreate(
            [
                "name" => "PT Benedictiodev By Cresca",
                "type_subscription" => "free",
            ],
            [
                "name" => "PT Benedictiodev By Cresca",
                "type_subscription" => "free",
                'address' => 'Jalan Admin No 100',
                'phone_number' => '08123456789',
                "subscription_fee" => 100000,
                "expired_date" => "2030-01-01 00:00:00",
                "grace_days_ended_at" => "2030-01-07 00:00:00",
                "settings_printer" => "Eppos 58mm",
            ]
        );

        User::query()->updateOrCreate(
            [
                'name' => 'Admin',
                'email' => 'admin@mail.com',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => Hash::make("admin123"),
                'is_owner' => 1,
                'is_management' => 0,
                'company_id' => $company->id,
                'username' => 'admin',
                'address' => 'Jalan Admin No 100',
                'phone_number' => '08123456789',
            ]
        );

        $uoms = [
            [
                [
                    "name" => "PCS",
                    "company_id" => $company->id,
                ],
                [
                    "name" => "PCS",
                    "company_id" => $company->id,
                ],
            ],
            [
                [
                    "name" => "BOX",
                    "company_id" => $company->id,
                ],
                [
                    "name" => "BOX",
                    "company_id" => $company->id,
                ],
            ],
            [
                [
                    "name" => "DUS",
                    "company_id" => $company->id,
                ],
                [
                    "name" => "DUS",
                    "company_id" => $company->id,
                ],
            ],
        ];

        foreach ($uoms as $key => $value) {
            MasterUom::query()->updateOrCreate($value[0], $value[1]);
        }

        $payment_methods = [
            [
                [
                    "name" => "TUNAI",
                    "company_id" => $company->id,
                ],
                [
                    "name" => "TUNAI",
                    "company_id" => $company->id,
                ],
            ],
            [
                [
                    "name" => "QRIS",
                    "company_id" => $company->id,
                ],
                [
                    "name" => "QRIS",
                    "company_id" => $company->id,
                ],
            ],
            [
                [
                    "name" => "TRANSFER",
                    "company_id" => $company->id,
                ],
                [
                    "name" => "TRANSFER",
                    "company_id" => $company->id,
                ],
            ],
        ];

        foreach ($payment_methods as $key => $value) {
            MasterPaymentMethod::query()->updateOrCreate($value[0], $value[1]);
        }

        $product_categories = [
            [
                [
                    "name" => "SYRUP",
                    "company_id" => $company->id,
                ],
                [
                    "name" => "SYRUP",
                    "company_id" => $company->id,
                ],
            ],
            [
                [
                    "name" => "TABLET",
                    "company_id" => $company->id,
                ],
                [
                    "name" => "TABLET",
                    "company_id" => $company->id,
                ],
            ],
            [
                [
                    "name" => "OBAT LUAR",
                    "company_id" => $company->id,
                ],
                [
                    "name" => "OBAT LUAR",
                    "company_id" => $company->id,
                ],
            ],
        ];

        foreach ($product_categories as $key => $value) {
            MasterProductCategory::query()->updateOrCreate($value[0], $value[1]);
        }

        $suppliers = [
            [
                [
                    "name" => "PHARMACY A",
                    "company_id" => $company->id,
                ],
                [
                    "code" => "SUP-1",
                    "name" => "PHARMACY A",
                    "address" => "Jalan Pharmacy A",
                    "phone_number" => "08123456789",
                    "email" => "pharmacy_a@mail.com",
                    "bank_account_number" => "1234567890",
                    "remarks" => "BANK BRI",
                    "company_id" => $company->id,
                ],
            ],
            [
                [
                    "name" => "PHARMACY B",
                    "company_id" => $company->id,
                ],
                [
                    "code" => "SUP-2",
                    "name" => "PHARMACY B",
                    "address" => "Jalan Pharmacy B",
                    "phone_number" => "08123456789",
                    "email" => "pharmacy_a@mail.com",
                    "bank_account_number" => "0987654321",
                    "remarks" => "BANK BCA",
                    "company_id" => $company->id,
                ],
            ],
        ];

        foreach ($suppliers as $key => $value) {
            MasterSupplier::query()->updateOrCreate($value[0], $value[1]);
        }

        $sales = [
            [
                [
                    "name" => "SALES A",
                    "company_id" => $company->id,
                ],
                [
                    "code" => "SLS-1",
                    "name" => "SALES A",
                    "address" => "Jalan SALES A",
                    "phone_number" => "08123456789",
                    "email" => "sales_a@mail.com",
                    "bank_account_number" => "1234567890",
                    "remarks" => "BANK BRI",
                    "company_id" => $company->id,
                ],
            ],
            [
                [
                    "name" => "SALES B",
                    "company_id" => $company->id,
                ],
                [
                    "code" => "SLS-2",
                    "name" => "SALES B",
                    "address" => "Jalan SALES B",
                    "phone_number" => "08123456789",
                    "email" => "sales_a@mail.com",
                    "bank_account_number" => "0987654321",
                    "remarks" => "BANK BCA",
                    "company_id" => $company->id,
                ],
            ],
        ];

        foreach ($sales as $key => $value) {
            MasterSales::query()->updateOrCreate($value[0], $value[1]);
        }

        $customer = [
            [
                [
                    "name" => "CUSTOMER A",
                    "company_id" => $company->id,
                ],
                [
                    "code" => "CS-1",
                    "name" => "CUSTOMER A",
                    "address" => "Jalan CUSTOMER A",
                    "phone_number" => "08123456789",
                    "email" => "customer_a@mail.com",
                    "bank_account_number" => "1234567890",
                    "remarks" => "BANK BRI",
                    "company_id" => $company->id,
                ],
            ],
            [
                [
                    "name" => "CUSTOMER B",
                    "company_id" => $company->id,
                ],
                [
                    "code" => "CS-2",
                    "name" => "CUSTOMER B",
                    "address" => "Jalan CUSTOMER B",
                    "phone_number" => "08123456789",
                    "email" => "customer_a@mail.com",
                    "bank_account_number" => "0987654321",
                    "remarks" => "BANK BCA",
                    "company_id" => $company->id,
                ],
            ],
        ];

        foreach ($customer as $key => $value) {
            MasterCustomer::query()->updateOrCreate($value[0], $value[1]);
        }

        $employees = [
            [
                [
                    "name" => "EMPLOYEE A",
                    "company_id" => $company->id,
                ],
                [
                    "code" => "EMP-1",
                    "name" => "EMPLOYEE A",
                    "address" => "Jalan EMPLOYEE A",
                    "phone_number" => "08123456789",
                    "email" => "employee_a@mail.com",
                    "bank_account_number" => "1234567890",
                    "remarks" => "BANK BRI",
                    "company_id" => $company->id,
                ],
            ],
            [
                [
                    "name" => "EMPLOYEE B",
                    "company_id" => $company->id,
                ],
                [
                    "code" => "EMP-2",
                    "name" => "EMPLOYEE B",
                    "address" => "Jalan EMPLOYEE B",
                    "phone_number" => "08123456789",
                    "email" => "employee_a@mail.com",
                    "bank_account_number" => "0987654321",
                    "remarks" => "BANK BCA",
                    "company_id" => $company->id,
                ],
            ],
        ];

        foreach ($employees as $key => $value) {
            MasterEmployee::query()->updateOrCreate($value[0], $value[1]);
        }
    }
}
