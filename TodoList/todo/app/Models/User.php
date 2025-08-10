<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens; // <-- Add this

class User extends Authenticatable
{
    // ... existing properties and methods ...


    use HasApiTokens; // <-- Add this
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    /**
     * Get all tasks for the user
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}