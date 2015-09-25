<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class UserLog extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	private $log;

	/**
	 * 用户日志
	 *
	 * @param 应用id， 用户id， 操作类型(A, D, U, S)， 操作对象， 操作数据， 操作sql， 用户ip， 代理服务器、路由等ip(逗号分割)
	 * @return void
	 */
	public function __construct($app_id, $user_id, $type = 'S', $object = '', $data = '', $sql = '', $ip = '', $ips = '')
	{
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
		echo '[', date('Y-m-d H:i:s'), ']', '[User Log]...';
		$user_log = \App\UserLog::create($this->log);
		echo 'OK!';
	}

}
