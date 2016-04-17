<?php

namespace Api;

class SmsTest extends ApiTest
{
    public function testPostCode()
    {
        $sms = $this->post('/api/sms/code', ['phone' => '18896581232', 'access_token' => 'test'])
            ->seeJson(['code' => 1]);
    }

}
