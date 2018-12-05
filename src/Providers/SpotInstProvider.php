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

        $accountId = config('SPOTINST_ACCOUNT_ID');
        $accessToken = config('SPOTINST_ACCESS_TOKEN');
        
        $this->app->singleton(\SpotInst\SpotInstClientInterface::class, function () {
            return new \SpotInst\SpotInstClient($accountId, $accessToken);
        });

    }
}