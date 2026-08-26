<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class SkillArticlesSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('blog:import', ['--force' => true]);
    }
}
