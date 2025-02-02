<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AthletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('athlets')->insert([
            [
                'name' => 'John Doe',
                'father_name' => 'Richard Roe',
                'phone_number' => '1234567890',
                'photo' => null,
                'admission_type' => 'Regular',
                'admission_expiry_date' => '2025-12-31',
                'box_id' => 1,
                'details' => 'Sample details'
            ],
            [
                'name' => 'Jane Smith',
                'father_name' => 'John Smith',
                'phone_number' => '0987654321',
                'photo' => null,
                'admission_type' => 'Regular',
                'admission_expiry_date' => '2025-12-31',
                'box_id' => 2,
                'details' => 'Sample details'
            ],
        ]);
    }
}
