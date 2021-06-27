<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);
        User::create([
            'name' => 'super admin',
            'email' => 'super-admin@example.com',
            'email_verified_at' => now(),
            'type' => 'super admin',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);
        User::factory()->count(20)->create();
        // factory(User::class, 20)->create();
    }
}
