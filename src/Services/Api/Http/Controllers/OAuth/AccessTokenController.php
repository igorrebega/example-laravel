<?php

namespace App\Services\Api\Http\Controllers\OAuth;

use Laravel\Passport\Http\Controllers\AccessTokenController as BaseController;
use Laravel\Passport\Http\Controllers\ConvertsPsrResponses;
use League\OAuth2\Server\Exception\OAuthServerException;
use Zend\Diactoros\Response as Psr7Response;

class AccessTokenController extends BaseController
{
    use ConvertsPsrResponses;

    /**
     * Perform the given callback with exception handling.
     *
     * @param  \Closure $callback
     * @return \Illuminate\Http\Response
     * @throws \InvalidArgumentException
     */
    protected function withErrorHandling($callback)
    {
        try {
            return $callback();
        } catch (OAuthServerException $e) {
            $this->exceptionHandler()->report($e);

            return $this->convertResponse(
                $e->generateHttpResponse(new Psr7Response)
            );
        }
    }
}