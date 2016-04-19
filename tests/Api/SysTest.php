<?php

namespace Api;

class SysTest extends ApiTest
{
    public function testGetAccessToken()
    {
        $accessToken = $this->get('/api/sys/cache')->seeJson(['code' => 1]);
    }
}
