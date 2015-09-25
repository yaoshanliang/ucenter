<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class UserLog extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	private $app_id, $user_id, $type, $object, $data, $sql, $ip;
	private $log;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($app_id, $user_id, $type = 'S', $object = '', $data = '', $sql = '', $ip = '', $ips = '')
	{
		$this->app_id = $app_id;
		$this->user_id = $user_id;
		$this->type = $type;
		$this->object = $object;
		$this->data = $data;
		$this->sql = $sql;
		$this->ip = $ip;
		$this->log = array('app_id' => $app_id,
							'user_id' => $user_id,
							'type' => $type,
							'object' => $object,
							'data' => $data,
							'sql' => $sql,
							'ip' => $ip,
							'ips' => $ips,
							'created_at' => date("Y-m-d H:i:s")
					);
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		echo '[', date('Y-m-d H:i:s'), ']', '[User Log]';
		$user_log = \App\UserLog::create($this->log);
		echo 'OK!';
	}

}
