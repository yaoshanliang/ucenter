<?php namespace App\Jobs;

use App\Model\AppLog as AppLogModel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class AppLog extends Job implements SelfHandling, ShouldBeQueued
{

	use InteractsWithQueue, SerializesModels;

	private $log;

	/**
	 * 应用日志
	 *
	 * @param 应用id， 用户id， 操作类型(A, D, U, S)， 操作说明， 操作数据， 操作sql， 用户ip， 代理服务器、路由等ip(逗号分割)
	 * @return void
	 */
	public function __construct($appId, $userId, $type = 'S', $title = '', $data = '', $sql = '', $ip = '', $ips = '')
	{
		$this->log = array('app_id' => $appId,
							'user_id' => $userId,
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
        $popedAt = date('Y-m-d H:i:s');
		$user_log = AppLogModel::create(array_merge($this->log, array('poped_at' => $popedAt, 'created_at' => date('Y-m-d H:i:s'))));
	}

}
