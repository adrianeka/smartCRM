<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['VIP', 'Prioritas Tinggi', 'B2B', 'Retail', 'Prospek Hangat'] as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }
    }
}
