<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Projects')]
class Index extends Component
{
    use WithPagination;

    public function mount()
    {
        $this->authorize('viewAny', Project::class);
    }

    public function render()
    {
        $projects = Project::with(['creator', 'tasks'])
            ->latest()
            ->paginate(10);

        return view('livewire.projects.index', [
            'projects' => $projects,
        ])->layout('components.layouts.app');
    }

    public function delete($projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('delete', $project);

        $project->delete();

        session()->flash('message', 'Project deleted successfully.');
    }
}
