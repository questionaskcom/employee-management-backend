<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AuthLog extends Model
{
    //
    use HasFactory;
    protected $fillable = ['user_id','email', 'action','method', 'ip_address','location'];

}
