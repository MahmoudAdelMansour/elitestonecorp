<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->firstOrCreate(['email' => 'admin@elitestone-corp.com'], [
            'name' => 'Elite Stone Admin',
            'locale' => 'ar',
            'password' => env('ADMIN_PASSWORD', 'ChangeMe!2026'),
        ]);

        $this->call(ContentSeeder::class);
    }
}
