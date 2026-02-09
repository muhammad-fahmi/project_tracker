<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Tasks')]
class Index extends Component
{
    use WithPagination;

    public function mount()
    {
        $this->authorize('viewAny', Task::class);
    }

    public function render()
    {
        $user = auth()->user();

        // Filter tasks based on role
        $query = Task::with(['project', 'assignedUser', 'creator', 'subtasks']);

        if ($user->isHead()) {
            // Head can only see tasks they created
            $query->where('created_by', $user->id);
        } elseif ($user->isStaff()) {
            // Staff can only see tasks assigned to them
            $query->where('assigned_to', $user->id);
        }
        // Admin sees all tasks

        $tasks = $query->latest()->paginate(10);

        return view('livewire.tasks.index', [
            'tasks' => $tasks,
        ])->layout('components.layouts.app');
    }

    public function delete($taskId)
    {
        $task = Task::findOrFail($taskId);
        $this->authorize('delete', $task);

        $task->delete();

        session()->flash('message', 'Task deleted successfully.');
    }

    public function updateStatus($taskId, $status)
    {
        $task = Task::findOrFail($taskId);
        $this->authorize('update', $task);

        $task->update(['status' => $status]);
    }
}
