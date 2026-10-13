<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SchoolClass extends Model
{
    protected $fillable = [
        'name',
        'section',
        'academic_year',
        'teacher_id',
    ];

    /**
     * Get the teacher assigned to this class.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the students enrolled in this class.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'class_student',
            'school_class_id',
            'student_id'
        )->withTimestamps();
    }
}