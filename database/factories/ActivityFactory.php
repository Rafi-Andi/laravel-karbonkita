<?php

namespace Database\Factories;

use App\Models\Mission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'mission_id' => Mission::factory(),
            'file_path' => fake()->text(rand(5, 6)),
            'description' => fake()->text(rand(7, 10)),
            'status' => 'pending',
            'admin_notes' => fake()->text(rand(7, 10)),
            'point_pending' => 20
        ];
    }
}
