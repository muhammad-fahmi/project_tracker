<?php

namespace App\Livewire\Subtasks;

use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Create Subtask')]
class Create extends Component
{
    #[Validate('required|exists:tasks,id')]
    public $task_id = '';

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string')]
    public $description = '';

    #[Validate('required|in:pending,in_progress,completed')]
    public $status = 'pending';

    #[Validate('nullable|date')]
    public $due_date = '';

    #[Validate('nullable|exists:users,id')]
    public $assigned_to = '';

    public function mount()
    {
        $this->authorize('create', Subtask::class);
    }

    public function save()
    {
        $this->authorize('create', Subtask::class);

        $validated = $this->validate();

        Subtask::create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        session()->flash('message', 'Subtask created successfully.');

        return $this->redirect('/subtasks', navigate: true);
    }

    public function render()
    {
        $user = auth()->user();

        // Filter tasks based on role for dropdown
        $tasksQuery = Task::with('project')->latest();

        if ($user->isHead()) {
            $tasksQuery->where('created_by', $user->id);
        }

        $tasks = $tasksQuery->get();
        $users = User::where('role', 'staff')->get();

        return view('livewire.subtasks.create', [
            'tasks' => $tasks,
            'users' => $users,
        ])->layout('components.layouts.app');
    }
}
