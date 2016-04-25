<?php
namespace App\Model;

use App\Model\Model;

class UserInfo extends Model
{
    protected $table = 'user_info';
    protected $fillable = ['user_id', 'field_id', 'value'];
}
