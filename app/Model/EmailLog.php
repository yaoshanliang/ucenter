<?php
namespace App\Model;

use App\Model\Model;

class EmailLog extends Model
{
	protected $table = 'email_logs';

	protected $fillable = ['id', 'app_id', 'user_id', 'email', 'content', 'pushed_at', 'poped_at', 'created_at'];

	public $timestamps = false;

}
