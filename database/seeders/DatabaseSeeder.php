<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
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
        // Create users with different roles
        $admin = User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        $supervisor = User::factory()->supervisor()->create([
            'name' => 'Supervisor User',
            'email' => 'supervisor@example.com',
        ]);

        $head = User::factory()->head()->create([
            'name' => 'Head User',
            'email' => 'head@example.com',
        ]);

        $staff1 = User::factory()->staff()->create([
            'name' => 'Staff User 1',
            'email' => 'staff1@example.com',
        ]);

        $staff2 = User::factory()->staff()->create([
            'name' => 'Staff User 2',
            'email' => 'staff2@example.com',
        ]);

        // Create projects by admin and head
        $projects = collect();

        for ($i = 1; $i <= 3; $i++) {
            $project = Project::factory()->create([
                'name' => "Project {$i}",
                'created_by' => $admin->id,
            ]);
            $projects->push($project);
        }

        $headProject = Project::factory()->create([
            'name' => 'Head Project',
            'created_by' => $head->id,
        ]);
        $projects->push($headProject);

        // Create tasks for each project
        $projects->each(function ($project) use ($admin, $head, $staff1, $staff2) {
            // Tasks created by head and assigned to staff
            Task::factory()->count(2)->create([
                'project_id' => $project->id,
                'created_by' => $head->id,
                'assigned_to' => $staff1->id,
            ])->each(function ($task) use ($staff1) {
                // Create subtasks
                Subtask::factory()->count(2)->create([
                    'task_id' => $task->id,
                    'created_by' => $task->created_by,
                    'assigned_to' => $staff1->id,
                ]);
            });

            // Tasks created by admin
            Task::factory()->count(2)->create([
                'project_id' => $project->id,
                'created_by' => $admin->id,
                'assigned_to' => $staff2->id,
            ])->each(function ($task) use ($staff2) {
                // Create subtasks
                Subtask::factory()->count(2)->create([
                    'task_id' => $task->id,
                    'created_by' => $task->created_by,
                    'assigned_to' => $staff2->id,
                ]);
            });
        });
    }
}
