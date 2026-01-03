<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $created = Carbon::now()->subMonth()->addDays(fake()->numberBetween(0,30));
        return [
            //
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'due_date' => $created->copy()->addDays(fake()->numberBetween(0,30)),
            'status' => fake()->randomElement(['pendiente','en_progreso','completado']),
            'user_id' => User::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'created_at' => $created,
            'updated_at' => $created
        ];
    }
}
