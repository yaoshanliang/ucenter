<?php

namespace Api;


class ApiTest extends \TestCase
{
    public $accessToken;

    public function setUp()
    {
        parent::setUp();

        $this->accessToken = $this->getAccessToken();
    }

    public function getAccessToken()
    {
        $accessToken = $this->post('/api/oauth/accessToken', [
            'grant_type' => 'password',
            'client_id' => 'ucenter',
            'client_secret' => 'ucenter_secret',
            'username' => 'admin',
            'password' => '123456'
        ])->seeJson(['code' => 1]);
        $response = (array)$this->response->original;
        return $response['data']->access_token;
    }
}
