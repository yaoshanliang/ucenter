<?php
namespace App\Model;

use App\Model\BaseModel as Model;

class AppLog extends Model
{
    protected $table = 'log_app';

    protected $fillable = ['id', 'app_id', 'user_id', 'request_method', 'request_url', 'request_params', 'request_ip', 'response_code', 'response_message', 'response_data', 'request_at', 'pushed_at', 'poped_at', 'created_at', 'request_time', 'user_agent'];

    public $timestamps = false;

}
