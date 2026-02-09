<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'start_date',
        'end_date',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function updateStatusBasedOnTasks(): void
    {
        $tasks = $this->tasks;

        // If no tasks, set to pending
        if ($tasks->isEmpty()) {
            $this->update(['status' => 'pending']);

            return;
        }

        // If any task is in_progress, set project to in_progress
        if ($tasks->contains('status', 'in_progress')) {
            $this->update(['status' => 'in_progress']);

            return;
        }

        // If all tasks are completed, set project to completed
        if ($tasks->every(fn ($task) => $task->status === 'completed')) {
            $this->update(['status' => 'completed']);

            return;
        }

        // Otherwise, set to pending
        $this->update(['status' => 'pending']);
    }
}
