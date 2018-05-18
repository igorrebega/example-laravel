<?php

namespace App\Foundation;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use App\Services\Backend\Providers\BackendServiceProvider;
use App\Services\Api\Providers\ApiServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->register(ApiServiceProvider::class);
    }
}
