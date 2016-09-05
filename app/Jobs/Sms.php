<?php

namespace App\Jobs;

use App\Model\Sms as SmsModel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

use PhpSms;

class Sms extends Job implements SelfHandling, ShouldBeQueued
{

    use InteractsWithQueue, SerializesModels;

    private $log;

    /**
     * 短信日志
     *
     */
    public function __construct($appId, $userId, $phone, $content)
    {
        $this->log = array('app_id' => $appId,
                            'user_id' => $userId,
                            'phone' => $phone,
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

        // PhpSms::queue(false);
        // $sms = $this->log;
        // $result = PhpSms::make()->to($sms['phone'])->content($sms['content'])->send();

        $user_log = SmsModel::create(array_merge($this->log, array('poped_at' => $popedAt, 'created_at' => date('Y-m-d H:i:s'))));
    }

}
