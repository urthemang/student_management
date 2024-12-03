<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $guarded = [];

    public function subjectUsers()
    {
        return $this->hasMany(UserSubject::class,);
    }
}
