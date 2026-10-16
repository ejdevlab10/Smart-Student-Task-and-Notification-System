<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'title',
        'subject',
        'type',
        'description',
        'due_date',
        'status',
        'created_by',
        'school_class_id',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'task_user'
        )
            ->using(TaskUser::class)
            ->withPivot('status', 'completed_at')
            ->withTimestamps();
    }
    public function submissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class);
    }
}