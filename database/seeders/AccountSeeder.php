<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Str;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::transaction(function () {
            $admin = User::query()->firstOrNew(['id' => 1]);
            $admin->fill([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ]);

            $role = Role::query()->where('name', 'Admin')->firstOrFail();
            // $role->users()->save($admin);
            $admin->role()->associate($role);
            $admin->save();
        });
    }
}
