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

        $accountId = env('SPOTINST_ACCOUNT_ID');
        $accessToken = env('SPOTINST_ACCESS_TOKEN');

        $this->app->singleton(\SpotInst\SpotInstClientInterface::class, function () use($accountId, $accessToken) {
            return new \SpotInst\SpotInstClient($accountId, $accessToken);
        });

    }
}