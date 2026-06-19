<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    public function groups()
    {
        return $this->belongsToMany(PermissionGroup::class, 'permission_group_items');
    }
}
