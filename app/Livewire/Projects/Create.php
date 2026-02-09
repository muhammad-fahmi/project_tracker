<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Create Project')]
class Create extends Component
{
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string')]
    public $description = '';

    #[Validate('required|in:pending,in_progress,completed,on_hold')]
    public $status = 'pending';

    #[Validate('nullable|date')]
    public $start_date = '';

    #[Validate('nullable|date|after_or_equal:start_date')]
    public $end_date = '';

    public function mount()
    {
        $this->authorize('create', Project::class);
    }

    public function save()
    {
        $this->authorize('create', Project::class);

        $validated = $this->validate();

        Project::create([
            ...$validated,
            'created_by' => Auth::id(),
        ]);

        session()->flash('message', 'Project created successfully.');

        return $this->redirect('/projects', navigate: true);
    }

    public function render()
    {
        return view('livewire.projects.create');
    }
}
