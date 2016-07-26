<?php

namespace App\Model;

use App\Model\BaseModel as Model;

class RolePermission extends Model {

    protected $table = 'role_permission';
    protected $fillable = ['role_id', 'permission_id'];

}
