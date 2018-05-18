<?php

namespace App\Services\Api\Providers;

use Illuminate\Routing\Router;
use Lucid\Foundation\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Read the routes from the "api.php" and "web.php" files of this Service
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function map(Router $router)
    {
        $namespace = 'App\Services\Api\Http\Controllers';
        $pathApi = __DIR__ . '/../routes/api.php';

        $router->group([
            'middleware' => 'api',
            'namespace'  => $namespace,
            'prefix'     => 'api/v1',
        ], function ($router) use ($pathApi) {
            require $pathApi;
        });

        $router->group([
            'middleware' => 'api',
            'prefix'     => 'api/v1/oauth',
        ], function ($router) {
            $router->post('/token', [
                'uses'       => '\App\Services\Api\Http\Controllers\OAuth\AccessTokenController@issueToken',
                'middleware' => 'throttle',
            ]);

            $router->group(['middleware' => ['web', 'auth']], function ($router) {
                $router->get('/tokens', [
                    'uses' => '\Laravel\Passport\Http\ControllersAuthorizedAccessTokenController@forUser',
                ]);

                $router->delete('/tokens/{token_id}', [
                    'uses' => '\Laravel\Passport\Http\ControllersAuthorizedAccessTokenController@destroy',
                ]);
            });
        });


    }
}
