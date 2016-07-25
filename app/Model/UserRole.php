<?php
namespace App\Model;

use App\Model\BaseModel as Model;

class UserRole extends Model
{
    protected $table = 'user_role';
    protected $fillable = ['user_id', 'app_id', 'role_id'];
}
