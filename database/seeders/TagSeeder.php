<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['VIP', 'Priority Tinggi', 'B2B', 'Retail', 'Warm Prospect'] as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }
    }
}
