<?php
namespace App\Model;

use App\Model\Model;

class UserLog extends Model
{
    protected $table = 'log_user';

    protected $fillable = ['id', 'app_id', 'user_id', 'type', 'title', 'data', 'sql', 'ip', 'ips', 'pushed_at', 'created_at'];

    public $timestamps = false;

}
