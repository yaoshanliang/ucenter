<?php

namespace App\Exceptions;

use Exception;

class ApiValidationException extends Exception
{
    public $validator;

    public $response;

    public function __construct($response = 'ApiValidationException', $validator = null)
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
