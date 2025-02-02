<?php

namespace Database\Seeders;

use App\Models\athlet;
use App\Models\Box;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Fee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Seed Boxes
        $box1 = Box::create([
            'box_number' => 'B001',
            'expire_date' => now()->addMonth(),
        ]);

        $box2 = Box::create([
            'box_number' => 'B002',
            'expire_date' => now()->addMonth(),
        ]);

        $box3 = Box::create([
            'box_number' => 'B003',
            'expire_date' => now()->addMonth(),
        ]);

        // Seed Fees
        $fee1 = Fee::create([
            'fees' => 500,
            'athlet_id' => 1,
        ]);

        $fee2 = Fee::create([
            'fees' => 600,
            'athlet_id' => 1,
        ]);

        $fee3 = Fee::create([
            'fees' => 700,
            'athlet_id' => 1,
        ]);

        // Seed Athlets
        athlet::create([
            'name' => 'John Doe',
            'father_name' => 'Mr. Doe',
            'phone_number' => '123456789',
            'photo' => null,
            'admission_type' => 'Monthly',
            'admission_expiry_date' => now()->addMonth(),
            'box_id' => $box1->id,
            'details' => 'First athlete details',
            'status' => true,
        ]);

        Athlet::create([
            'name' => 'Jane Smith',
            'father_name' => 'Mr. Smith',
            'phone_number' => '987654321',
            'photo' => null,
            'admission_type' => 'Quarterly',
            'admission_expiry_date' => now()->addMonths(3),
            'box_id' => $box2->id,
            'details' => 'Second athlete details',
            'status' => true,
        ]);

        Athlet::create([
            'name' => 'Sam Brown',
            'father_name' => 'Mr. Brown',
            'phone_number' => '456123789',
            'photo' => null,
            'admission_type' => 'Yearly',
            'admission_expiry_date' => now()->addYear(),
            'box_id' => $box3->id,
            'details' => 'Third athlete details',
            'status' => true,
        ]);

        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            BoxesTableSeeder::class,
            FeesTableSeeder::class, // Seed fees before athlets
            AthletsTableSeeder::class,
        ]);
        // Create user
        // Create Super Admin User
        $admin = User::create([
            'name' => 'super-admin',
            'email' => 'dev@dev.com',
            'password' => Hash::make('dev'),
            'email_verified_at' => now(),
        ]);

        // Assign Filament Super Admin Role
        $admin->assignRole('panel_user');

    }
}
