<?php

namespace App\Livewire\Tasks;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Create Task')]
class Create extends Component
{
    #[Validate('required|exists:projects,id')]
    public $project_id = '';

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string')]
    public $description = '';

    #[Validate('required|in:pending,in_progress,completed')]
    public $status = 'pending';

    #[Validate('required|in:low,medium,high')]
    public $priority = 'medium';

    #[Validate('nullable|date')]
    public $due_date = '';

    #[Validate('nullable|exists:users,id')]
    public $assigned_to = '';

    public function mount()
    {
        $this->authorize('create', Task::class);
    }

    public function save()
    {
        $this->authorize('create', Task::class);

        $validated = $this->validate();

        Task::create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        session()->flash('message', 'Task created successfully.');

        return $this->redirect('/tasks', navigate: true);
    }

    public function render()
    {
        $projects = Project::latest()->get();
        $users = User::where('role', 'staff')->get();

        return view('livewire.tasks.create', [
            'projects' => $projects,
            'users' => $users,
        ])->layout('components.layouts.app');
    }
}
