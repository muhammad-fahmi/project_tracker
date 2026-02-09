<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    /** @use HasFactory<\Database\Factories\SubtaskFactory> */
    use HasFactory;

    protected $fillable = [
        'task_id',
        'name',
        'description',
        'status',
        'due_date',
        'assigned_to',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    // Relationships
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
