<?php

namespace App\Jobs;

use App\Model\EmailLog as EmailLogModel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Mail;

class Email extends Job implements SelfHandling, ShouldBeQueued
{

    use InteractsWithQueue, SerializesModels;

    private $log;

    /**
     * 邮件日志
     *
     */
    public function __construct($appId, $userId, $email, $subject, $content)
    {
        $this->log = array('app_id' => $appId,
                            'user_id' => $userId,
                            'email' => $email,
                            'subject' => $subject,
                            'content' => $content,
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
        echo '[', $popedAt, ']', '[Email Log]...';

        // 发送邮件
        $mail = $this->log;
        $mail = Mail::send('emails.custom', $mail, function($message) use ($mail) {
            $message->from(env('MAIL_USERNAME'), env('MAIL_FROMNAME'));
            $message->to($mail['email'])->subject($mail['subject']);
        });

        // 记录日志
        $user_log = EmailLogModel::create(array_merge($this->log, array('poped_at' => $popedAt, 'created_at' => date('Y-m-d H:i:s'))));

        echo 'OK!';
    }
}
