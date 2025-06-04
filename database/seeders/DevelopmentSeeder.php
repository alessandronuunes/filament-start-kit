<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'first_name'        => 'Admin',
            'last_name'         => 'AiHub',
            'username'          => 'admin.aihub',	
            'email'             => 'admin@admin.com',
            'password'          => bcrypt('admin'),
            'email_verified_at' => now(),
        ]);

        $team = Team::create([
            'name' => 'AiHub',
            'slug' => 'aihub',
        ]);

        $user->teams()->attach($team);
    }
}
