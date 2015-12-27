<?php namespace App\Jobs;

use App\Jobs\Job;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

use Mail;
class SendEmail extends Job implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	private $mail;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($type, $subject, $content, $to)
	{
		$this->mail = array('type' => $type,
							'subject' => $subject,
							'content' => $content,
							'to' => $to
						);
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$mail = $this->mail;
		echo '[', date('Y-m-d H:i:s'), ']', '[Send Email]...', $mail['type'], '...', $mail['to'], '...';
		switch($mail['type']) {
			case 'invite' :
				Mail::send('emails.invite', $mail, function($message) use ($mail) {
					$message->from(env('MAIL_USERNAME'), env('MAIL_FROMNAME'));
					$message->to($mail['to'])->subject($mail['subject']);
				});
				break;
			case 'notice' :
				break;
			default :
				break;
		}
		echo 'OK!';
	}

}
