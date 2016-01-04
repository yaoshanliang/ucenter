<?php namespace App\Jobs;

use App\Jobs\UserLog;
use App\Model\UserLog as UserLogModel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class UserLog extends Job implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	private $log;

	/**
	 * 用户日志
	 *
	 * @param 应用id， 用户id， 操作类型(A, D, U, S)， 操作说明， 操作数据， 操作sql， 用户ip， 代理服务器、路由等ip(逗号分割)
	 * @return void
	 */
	public function __construct($app_id, $user_id, $type = 'S', $title = '', $data = '', $sql = '', $ip = '', $ips = '')
	{
		$this->log = array('app_id' => $app_id,
							'user_id' => $user_id,
							'type' => $type,
							'title' => $title,
							'data' => $data,
							'sql' => $sql,
							'ip' => $ip,
							'ips' => $ips,
							'pushed_at' => date("Y-m-d H:i:s")
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
		$user_log = UserLogModel::create(array_merge($this->log, array('created_at' => date('Y-m-d H:i:s'))));
		echo 'OK!';
	}

}
