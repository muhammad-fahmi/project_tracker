<?php

namespace Tests\Feature;

use App\Livewire\Projects\Create;
use App\Livewire\Projects\Index;
use App\Livewire\Projects\Show;
use App\Livewire\Subtasks\Create as SubtaskCreate;
use App\Livewire\Subtasks\Index as SubtaskIndex;
use App\Livewire\Tasks\Create as TaskCreate;
use App\Livewire\Tasks\Index as TaskIndex;
use App\Models\Project;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

describe('Authentication', function () {
    test('unauthenticated user redirected to login', function () {
        $this->get('/projects')->assertRedirect('/login');
    });

    test('user can login with valid credentials', function () {
        $user = User::factory()->admin()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ])->assertRedirect('/projects');

        $this->assertAuthenticated();
    });

    test('user cannot login with invalid credentials', function () {
        User::factory()->admin()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect();
        $this->assertGuest();
    });

    test('user can logout', function () {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    });
});

describe('Projects - Admin Access', function () {
    test('admin can view all projects', function () {
        $admin = User::factory()->admin()->create();
        Project::factory(3)->create();

        $this->actingAs($admin)
            ->get('/projects')
            ->assertSeeLivewire(Index::class);

        Livewire::actingAs($admin)->test(Index::class)
            ->assertViewHas('projects', function ($projects) {
                return $projects->count() === 3;
            });
    });

    test('admin can create project', function () {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/projects/create')
            ->assertSeeLivewire(Create::class);

        Livewire::actingAs($admin)->test(Create::class)
            ->set('name', 'New Project')
            ->set('description', 'Project Description')
            ->set('status', 'pending')
            ->set('start_date', '2026-01-20')
            ->set('end_date', '2026-02-20')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('projects', [
            'name' => 'New Project',
            'created_by' => $admin->id,
        ]);
    });

    test('admin can delete project', function () {
        $admin = User::factory()->admin()->create();
        $project = Project::factory()->create();

        Livewire::actingAs($admin)->test(Index::class)
            ->call('delete', $project->id);

        $this->assertModelMissing($project);
    });

    test('admin can view project details', function () {
        $admin = User::factory()->admin()->create();
        $project = Project::factory()->create();
        Task::factory(3)->for($project)->create();

        $this->actingAs($admin)
            ->get('/projects/'.$project->id)
            ->assertSeeLivewire(Show::class);
    });
});

describe('Projects - Supervisor Access', function () {
    test('supervisor can view all projects', function () {
        $supervisor = User::factory()->supervisor()->create();
        Project::factory(3)->create();

        Livewire::actingAs($supervisor)->test(Index::class)
            ->assertViewHas('projects');
    });

    test('supervisor cannot create project', function () {
        $supervisor = User::factory()->supervisor()->create();

        $this->actingAs($supervisor)
            ->get('/projects/create')
            ->assertForbidden();
    });

    test('supervisor can view project details', function () {
        $supervisor = User::factory()->supervisor()->create();
        $project = Project::factory()->create();

        $this->actingAs($supervisor)
            ->get('/projects/'.$project->id)
            ->assertSeeLivewire(Show::class);
    });

    test('supervisor cannot delete project', function () {
        $supervisor = User::factory()->supervisor()->create();
        $project = Project::factory()->create();

        Livewire::actingAs($supervisor)->test(Index::class)
            ->call('delete', $project->id)
            ->assertForbidden();
    });
});

describe('Projects - Head Access', function () {
    test('head can view all projects', function () {
        $head = User::factory()->head()->create();
        Project::factory(3)->create();

        Livewire::actingAs($head)->test(Index::class)
            ->assertViewHas('projects');
    });

    test('head cannot create project', function () {
        $head = User::factory()->head()->create();

        $this->actingAs($head)
            ->get('/projects/create')
            ->assertForbidden();
    });

    test('head can view project details', function () {
        $head = User::factory()->head()->create();
        $project = Project::factory()->create();

        $this->actingAs($head)
            ->get('/projects/'.$project->id)
            ->assertSeeLivewire(Show::class);
    });
});

describe('Projects - Staff Access', function () {
    test('staff cannot view projects', function () {
        $staff = User::factory()->staff()->create();

        $this->actingAs($staff)
            ->get('/projects')
            ->assertForbidden();
    });

    test('staff cannot view project details', function () {
        $staff = User::factory()->staff()->create();
        $project = Project::factory()->create();

        $this->actingAs($staff)
            ->get('/projects/'.$project->id)
            ->assertForbidden();
    });
});

describe('Tasks - Admin Access', function () {
    test('admin can view all tasks', function () {
        $admin = User::factory()->admin()->create();
        Task::factory(5)->create();

        Livewire::actingAs($admin)->test(TaskIndex::class)
            ->assertViewHas('tasks');
    });

    test('admin can create task', function () {
        $admin = User::factory()->admin()->create();
        $project = Project::factory()->create();
        $user = User::factory()->staff()->create();

        Livewire::actingAs($admin)->test(TaskCreate::class)
            ->set('project_id', $project->id)
            ->set('name', 'New Task')
            ->set('description', 'Task Description')
            ->set('assigned_to', $user->id)
            ->set('priority', 'high')
            ->set('status', 'pending')
            ->set('due_date', '2026-02-01')
            ->call('save');

        $this->assertDatabaseHas('tasks', [
            'name' => 'New Task',
            'project_id' => $project->id,
            'created_by' => $admin->id,
        ]);
    });

    test('admin can update task status', function () {
        $admin = User::factory()->admin()->create();
        $task = Task::factory()->create();

        Livewire::actingAs($admin)->test(TaskIndex::class)
            ->call('updateStatus', $task->id, 'in_progress');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'in_progress',
        ]);
    });
});

describe('Tasks - Head Access', function () {
    test('head can view only own tasks', function () {
        $head = User::factory()->head()->create();
        $other = User::factory()->head()->create();

        Task::factory(3)->create(['created_by' => $head->id]);
        Task::factory(2)->create(['created_by' => $other->id]);

        Livewire::actingAs($head)->test(TaskIndex::class)
            ->assertViewHas('tasks', function ($tasks) {
                return $tasks->count() === 3;
            });
    });

    test('head can create task', function () {
        $head = User::factory()->head()->create();
        $project = Project::factory()->create();
        $staff = User::factory()->staff()->create();

        Livewire::actingAs($head)->test(TaskCreate::class)
            ->set('project_id', $project->id)
            ->set('name', 'Head Task')
            ->set('assigned_to', $staff->id)
            ->set('priority', 'medium')
            ->set('status', 'pending')
            ->call('save');

        $this->assertDatabaseHas('tasks', [
            'name' => 'Head Task',
            'created_by' => $head->id,
        ]);
    });

    test('head can update own task status', function () {
        $head = User::factory()->head()->create();
        $task = Task::factory()->create(['created_by' => $head->id]);

        Livewire::actingAs($head)->test(TaskIndex::class)
            ->call('updateStatus', $task->id, 'completed');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
    });

    test('head cannot update other task status', function () {
        $head = User::factory()->head()->create();
        $other = User::factory()->head()->create();
        $task = Task::factory()->create(['created_by' => $other->id]);

        Livewire::actingAs($head)->test(TaskIndex::class)
            ->call('updateStatus', $task->id, 'completed')
            ->assertForbidden();
    });
});

describe('Tasks - Staff Access', function () {
    test('staff can view only assigned tasks', function () {
        $staff = User::factory()->staff()->create();
        $other = User::factory()->staff()->create();

        Task::factory(3)->create(['assigned_to' => $staff->id]);
        Task::factory(2)->create(['assigned_to' => $other->id]);

        Livewire::actingAs($staff)->test(TaskIndex::class)
            ->assertViewHas('tasks', function ($tasks) {
                return $tasks->count() === 3;
            });
    });

    test('staff cannot create task', function () {
        $staff = User::factory()->staff()->create();

        $this->actingAs($staff)
            ->get('/tasks/create')
            ->assertForbidden();
    });

    test('staff can update assigned task status', function () {
        $staff = User::factory()->staff()->create();
        $task = Task::factory()->create(['assigned_to' => $staff->id]);

        Livewire::actingAs($staff)->test(TaskIndex::class)
            ->call('updateStatus', $task->id, 'in_progress');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'in_progress',
        ]);
    });
});

describe('Subtasks - Admin Access', function () {
    test('admin can view all subtasks', function () {
        $admin = User::factory()->admin()->create();
        Subtask::factory(5)->create();

        Livewire::actingAs($admin)->test(SubtaskIndex::class)
            ->assertViewHas('subtasks');
    });

    test('admin can create subtask', function () {
        $admin = User::factory()->admin()->create();
        $task = Task::factory()->create();
        $user = User::factory()->staff()->create();

        Livewire::actingAs($admin)->test(SubtaskCreate::class)
            ->set('task_id', $task->id)
            ->set('name', 'New Subtask')
            ->set('description', 'Subtask Description')
            ->set('assigned_to', $user->id)
            ->set('status', 'pending')
            ->set('due_date', '2026-02-01')
            ->call('save');

        $this->assertDatabaseHas('subtasks', [
            'name' => 'New Subtask',
            'task_id' => $task->id,
            'created_by' => $admin->id,
        ]);
    });

    test('admin can update subtask status', function () {
        $admin = User::factory()->admin()->create();
        $subtask = Subtask::factory()->create();

        Livewire::actingAs($admin)->test(SubtaskIndex::class)
            ->call('updateStatus', $subtask->id, 'completed');

        $this->assertDatabaseHas('subtasks', [
            'id' => $subtask->id,
            'status' => 'completed',
        ]);
    });
});

describe('Subtasks - Staff Access', function () {
    test('staff can view only assigned subtasks', function () {
        $staff = User::factory()->staff()->create();
        $other = User::factory()->staff()->create();

        Subtask::factory(3)->create(['assigned_to' => $staff->id]);
        Subtask::factory(2)->create(['assigned_to' => $other->id]);

        Livewire::actingAs($staff)->test(SubtaskIndex::class)
            ->assertViewHas('subtasks', function ($subtasks) {
                return $subtasks->count() === 3;
            });
    });

    test('staff can update assigned subtask status', function () {
        $staff = User::factory()->staff()->create();
        $subtask = Subtask::factory()->create(['assigned_to' => $staff->id]);

        Livewire::actingAs($staff)->test(SubtaskIndex::class)
            ->call('updateStatus', $subtask->id, 'completed');

        $this->assertDatabaseHas('subtasks', [
            'id' => $subtask->id,
            'status' => 'completed',
        ]);
    });
});

describe('Role Helper Methods', function () {
    test('user can check if admin', function () {
        $admin = User::factory()->admin()->create();
        $other = User::factory()->staff()->create();

        expect($admin->isAdmin())->toBeTrue();
        expect($other->isAdmin())->toBeFalse();
    });

    test('user can check if supervisor', function () {
        $supervisor = User::factory()->supervisor()->create();
        $other = User::factory()->staff()->create();

        expect($supervisor->isSupervisor())->toBeTrue();
        expect($other->isSupervisor())->toBeFalse();
    });

    test('user can check if head', function () {
        $head = User::factory()->head()->create();
        $other = User::factory()->staff()->create();

        expect($head->isHead())->toBeTrue();
        expect($other->isHead())->toBeFalse();
    });

    test('user can check if staff', function () {
        $staff = User::factory()->staff()->create();
        $other = User::factory()->head()->create();

        expect($staff->isStaff())->toBeTrue();
        expect($other->isStaff())->toBeFalse();
    });
});
