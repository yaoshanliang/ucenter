<?php
namespace App\Model;

use App\Model\Model;

class AppLog extends Model
{
	protected $table = 'app_logs';

	protected $fillable = ['id', 'app_id', 'user_id', 'type', 'title', 'data', 'sql', 'ip', 'ips', 'pushed_at', 'poped_at', 'created_at'];

	public $timestamps = false;

}
