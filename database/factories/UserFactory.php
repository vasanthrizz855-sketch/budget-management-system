<?php

namespace Database\Factories;

use App\Enums\RecordStatus;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'role' => UserRole::Admin->value,
            'status' => RecordStatus::Active->value,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'name' => 'Admin User',
            'email' => 'admin@managementpro.test',
            'password' => Hash::make('password'),
            'role' => UserRole::Admin->value,
            'status' => RecordStatus::Active->value,
        ]);
    }
}
