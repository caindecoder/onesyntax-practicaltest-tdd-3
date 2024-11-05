<?php

namespace Database\Seeders;

use App\Models\Website;
use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Website::factory()->count(5)->create();
    }
}
