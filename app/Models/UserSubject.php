<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;


class UserSubject extends Pivot
{
    protected $table = 'user_subjects'; // Explicitly set the pivot table name

    protected $fillable = [
        'user_id',
        'subject_id',
        'grade',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
