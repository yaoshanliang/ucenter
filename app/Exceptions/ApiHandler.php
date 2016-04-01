<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Exceptions\ApiException;
use League\OAuth2\Server\Exception\AccessDeniedException;
use League\OAuth2\Server\Exception\InvalidRequestException;
use League\OAuth2\Server\Exception\InvalidRefreshException;
use App\Services\Api;

class ApiHandler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        'Symfony\Component\HttpKernel\Exception\HttpException',
        'App\Exceptions\ApiException'
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof AccessDeniedException) {
            return Api::apiReturn(UNAUTHORIZED, 'access_token错误');
        }
        if ($e instanceof InvalidRequestException || $e instanceof InvalidRefreshException) {
            return Api::apiReturn(BADREQUEST, '参数错误, ' . $e->getMessage());
        }
        if ($e instanceof ApiException){
            return Api::apiReturn(ERROR, $e->getMessage());
        }

        return parent::render($request, $e);
    }
}
