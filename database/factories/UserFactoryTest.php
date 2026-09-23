<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


/**
 * @extends Factory<User>
 */
class UserFactoryTest extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'first_name' => fake()->first_name(),
            'last_name' => fake()->last_name(),
            'email' => fake()->email(),
            'password' => static::$password ??= Hash::make('password'),
        ];
    }
}
