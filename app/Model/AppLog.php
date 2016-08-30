<?php
namespace App\Model;

use App\Model\BaseModel as Model;

class AppLog extends Model
{
    protected $table = 'log_app';

    protected $fillable = ['id', 'app_id', 'user_id', 'request_method', 'request_url', 'request_params',
        'response_code', 'response_message', 'response_data', 'user_ip', 'user_client', 'user_agent', 'server_ip', 'request_at', 'pushed_at', 'poped_at', 'created_at', 'request_time'];

    public $timestamps = false;

}
