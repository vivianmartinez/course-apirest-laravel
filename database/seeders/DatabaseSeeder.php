<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Task;
use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear 5 usuarios
        User::factory(5)->create();
        // Crear 4 categorías
        Category::factory(4)->create();
        // Crear 20 tareas con un comentario del usuario asignado y otro adicional
        Task::factory(30)->create()->each(function($task){
            Comment::factory()->create([
                'task_id' => $task->id,
                'user_id' => $task->user_id
            ]);
            // comentario de otro usuario
            Comment::factory()->create(['task_id' => $task->id]);
        });
    }
}
