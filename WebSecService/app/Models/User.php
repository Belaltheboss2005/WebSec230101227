<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ Use this instead of Model
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users'; // Explicitly defining the table

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'privilege',
        'remember_token',
        'created_at',
        'updated_at'
    ]; // Allowing mass assignment
}
