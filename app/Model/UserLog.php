<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model {
	protected $table = 'user_logs';

	protected $fillable = ['id', 'app_id', 'user_id', 'type', 'title', 'data', 'sql', 'ip', 'ips', 'pushed_at', 'created_at'];

	public $timestamps = false;

}
