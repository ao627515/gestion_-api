<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    public function accessRights()
    {
        return $this->belongsToMany(AccessRight::class, 'user_role_access_rights', 'user_role_id', 'access_right_id');
    }
}
