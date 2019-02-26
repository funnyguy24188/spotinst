<?php
namespace SpotInst\Providers;

class SpotInstProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $accountId = config('devbox.resources.spotinst.account_id');
        $accessToken = config('devbox.resources.spotinst.access_token');

        $this->app->singleton(\SpotInst\SpotInstClientInterface::class, function () use($accountId, $accessToken) {
            return new \SpotInst\SpotInstClient($accountId, $accessToken);
        });

    }
}