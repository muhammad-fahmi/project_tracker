<?php

namespace App\Livewire\Subtasks;

use App\Models\Subtask;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Subtasks')]
class Index extends Component
{
    use WithPagination;

    public function mount()
    {
        $this->authorize('viewAny', Subtask::class);
    }

    public function render()
    {
        $user = auth()->user();

        // Filter subtasks based on role
        $query = Subtask::with(['task.project', 'assignedUser', 'creator']);

        if ($user->isHead()) {
            // Head can only see subtasks they created
            $query->where('created_by', $user->id);
        } elseif ($user->isStaff()) {
            // Staff can only see subtasks assigned to them
            $query->where('assigned_to', $user->id);
        }
        // Admin sees all subtasks

        $subtasks = $query->latest()->paginate(10);

        return view('livewire.subtasks.index', [
            'subtasks' => $subtasks,
        ])->layout('components.layouts.app');
    }

    public function delete($subtaskId)
    {
        $subtask = Subtask::findOrFail($subtaskId);
        $this->authorize('delete', $subtask);

        $subtask->delete();

        session()->flash('message', 'Subtask deleted successfully.');
    }

    public function updateStatus($subtaskId, $status)
    {
        $subtask = Subtask::findOrFail($subtaskId);
        $this->authorize('update', $subtask);

        $subtask->update(['status' => $status]);
    }
}
