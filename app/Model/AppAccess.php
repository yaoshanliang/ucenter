<?php
namespace App\Model;

use App\Model\Model;

class AppAccess extends Model
{
    protected $table = 'app_access';
    protected $fillable = ['user_id', 'app_id', 'type', 'title', 'description', 'handler_id', 'result', 'reason', 'read_at', 'handled_at'];
}
