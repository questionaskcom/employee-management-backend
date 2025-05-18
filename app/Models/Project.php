<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'description'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Optional if you want to assign employees to projects directly
    public function employees()
    {
        return $this->belongsToMany(User::class);
    }
    
}
