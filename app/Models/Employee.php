<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $fillable = ['name', 'email', 'department', 'phone'];

    public function department() {
        return $this->belongsTo(Department::class);
    }
    

}
