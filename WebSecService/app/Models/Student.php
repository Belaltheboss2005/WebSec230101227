<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public $timestamps = false;

    protected $table = 'student'; // Explicitly defining the table

    protected $fillable = [
        'name',
        'email',
        'password',
        'age',
        'major',
        'created_at',
        'updated_at'
    ]; // Allowing mass assignment
}
