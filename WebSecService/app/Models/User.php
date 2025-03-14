<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public $timestamps = false;
    protected $table = 'users2'; // Make sure this matches your DB table

    protected $fillable = [
        'name',
        'email',
        'password',
        'created_at',
        'updated_at'
    ];
}
