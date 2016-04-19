<?php

namespace Api;

class OauthTest extends ApiTest
{
    public function testGetAccessToken()
    {
        $accessToken = $this->post('/api/oauth/accessToken', [
            'grant_type' => 'password',
            'client_id' => 'ucenter',
            'client_secret' => 'ucenter_secret',
            'username' => 'admin',
            'password' => '123456'
        ])->seeJson(['code' => 1]);
    }
}
