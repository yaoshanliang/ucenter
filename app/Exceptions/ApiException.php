<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    public $validator;

    public $response;

    public function __construct($response = 'ApiException', $validator = null)
    {
        parent::__construct($response);

        $this->response = $response;
        $this->validator = $validator;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
