<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class CredendialFactory extends Factory
{
    protected static ?string $token;
    
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->username(),
            'token' => static::$token ??= Hash::make('token'),
            'active' => fake()->active(),
        ];
    }
}
