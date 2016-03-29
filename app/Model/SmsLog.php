<?php
namespace App\Model;

use App\Model\Model;

class SmsLog extends Model
{
	protected $table = 'log_sms';

	protected $fillable = ['id', 'app_id', 'user_id', 'phone', 'content', 'pushed_at', 'poped_at', 'created_at'];

	public $timestamps = false;

}
