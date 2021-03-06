<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;
    protected $table = 'UserRoles';

    protected $fillable = [ 'user_id', 'role', 'role_name' ];
    public $timestamps = false;
}
