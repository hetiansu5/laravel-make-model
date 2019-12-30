<?php

namespace MakeModel\Providers;

use MakeModel\Console\Commands\MakeModelCommand;
use Illuminate\Support\ServiceProvider;

class MakeModelProvider extends ServiceProvider
{

    public function register()
    {

    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([MakeModelCommand::class]);
        }
    }

}