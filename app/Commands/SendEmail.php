<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

use Mail;
class SendEmail extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		echo '[', date('Y-m-d H:i:s'), ']', '[Send Email]...';
		Mail::raw('Text to e-mail', function($message)
		{
			$message->from('support@iat.net.cn', 'Laravel');

			$message->to('1329517386@qq.com')->cc('iatboy@163.com');
		});
		echo 'OK!';
	}

}
