<?php

namespace App\Services\Api\Annotations;

// ====== Security

/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host=API_HOST,
 *     produces={"application/json"},
 *     consumes={"application/json"},
 *     basePath="/api/v1",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="This is API for P.aid app",
 *         description="If you have questions please contact Chicago team",
 *         @SWG\Contact(
 *             email="i.rebega@bvblogic.com"
 *         )
 *     )
 * )
 */

// ====== Urls


/**
 * RouteRegistrar::forAccessTokens
 *
 * POST /oauth/token
 * GET /oauth/tokens
 * DELETE /oauth/{token_id}
 *
 */

/**
 * @SWG\Post(
 *      path="/oauth/token",
 *      summary="Requests a access token",
 *      tags={"OAuth"},
 *      operationId="refreshToken",
 *      consumes={"application/x-www-form-urlencoded"},
 *      @SWG\Parameter(
 *          name="grant_type",
 *          in="formData",
 *          description="refresh_token or password",
 *          required=true,
 *          type="string"
 *      ),
 *      @SWG\Parameter(
 *          name="client_id",
 *          in="formData",
 *          description="OAuth Client ID",
 *          required=true,
 *          type="string"
 *      ),
 *      @SWG\Parameter(
 *          name="client_secret",
 *          in="formData",
 *          description="OAuth Client Secret",
 *          required=true,
 *          type="string"
 *      ),
 *      @SWG\Parameter(
 *          name="username",
 *          in="formData",
 *          description="User`s email  (if grant_type = password)",
 *          required=false,
 *          type="string"
 *      ),
 *      @SWG\Parameter(
 *          name="password",
 *          in="formData",
 *          description="Password for the user (if grant_type = password)",
 *          required=false,
 *          type="string"
 *      ),
 *      @SWG\Parameter(
 *          name="refresh_token",
 *          in="formData",
 *          description="Refresh toke (if grant_type = refresh_token)",
 *          required=false,
 *          type="string"
 *      ),
 *      @SWG\Response(
 *          response=200,
 *          description="successful operation",
 *          @SWG\Schema(ref="#/definitions/Token"),
 *      ),
 *      @SWG\Response(
 *          response=400,
 *          description="user is not activated or user is not admin",
 *      )
 *  )
 */

/**
 * @SWG\Get(
 *      path="/oauth/tokens",
 *      summary="Returns all client's tokens for authenticated user",
 *      tags={"OAuth"},
 *      operationId="getTokens",
 *      @SWG\Response(
 *          response=200,
 *          description="successful operation",
 *          @SWG\Schema(
 *              type="array",
 *              @SWG\Items(ref="#/definitions/Token")
 *          )
 *      )
 *  )
 */

/**
 * @SWG\Delete(
 *      path="/oauth/token/{token_id}",
 *      summary="Deny the token",
 *      tags={"OAuth"},
 *      operationId="denyToken",
 *      @SWG\Parameter(
 *          name="token_id",
 *          in="path",
 *          description="",
 *          required=true,
 *          type="integer"
 *      ),
 *      @SWG\Response(
 *          response=200,
 *          description="successful operation",
 *      )
 *  )
 */

/**
 * RouteRegistrar::forPersonalAccessTokens
 *
 * GET /oauth/scopes
 * GET /oauth/personal-access-tokens
 * POST /oauth/personal-access-tokens
 * DELETE /oauth/personal-access-tokens
 */

// Models

/**
 * @see vendor/laravel/passport/src/Token.php
 *
 * @SWG\Definition(
 *   definition="Token",
 *   required={},
 *   type="object"
 * )
 */
class Token
{

    /**
     * @SWG\Property(
     *     type="string",
     *     property="token_type",
     *     description="Token_type",
     *     example="Bearer"
     * )
     */

    /**
     * @SWG\Property(
     *     type="integer",
     *     property="expires_in",
     *     description="Token lifetime",
     *     example="31536000"
     * )
     */

    /**
     * @SWG\Property(
     *     type="string",
     *     property="access_token",
     *     description="Access token"
     * )
     */

    /**
     * @SWG\Property(
     *     type="string",
     *     property="refresh_token",
     *     description="Refresh token"
     * )
     */
}
