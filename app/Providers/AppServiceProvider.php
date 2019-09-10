<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url){
<<<<<<< HEAD
        $url->forceScheme('https');
=======
        //$url->forceScheme('https');
>>>>>>> d90d22caf04b0821bf4d48cff2ed22720bba8c92
        Schema::defaultStringLength(191);
    }
}
