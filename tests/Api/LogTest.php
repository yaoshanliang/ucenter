<?php

namespace Api;

class LogTest extends ApiTest
{
    public function testPostLog()
    {
        $email = $this->post('/api/log', ['type' => 'A', 'title' => 'test',
            'data' => 'test data', 'sql' => 'insert into', 'access_token' => 'test'])
            ->seeJson(['code' => 1]);
    }
}
