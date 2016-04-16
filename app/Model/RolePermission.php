<?php

namespace App\Model;

class RolePermission extends Model {

    protected $table = 'role_permission';
    protected $fillable = ['role_id', 'permission_id'];

}
