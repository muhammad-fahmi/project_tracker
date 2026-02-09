<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Project Details')]
class Show extends Component
{
    public Project $project;

    public function mount(Project $project)
    {
        $this->authorize('view', $project);
        $this->project = $project;
    }

    public function render()
    {
        $tasks = $this->project->tasks()
            ->with(['assignedUser', 'creator'])
            ->latest()
            ->get();

        return view('livewire.projects.show', [
            'tasks' => $tasks,
        ])->layout('components.layouts.app');
    }
}
