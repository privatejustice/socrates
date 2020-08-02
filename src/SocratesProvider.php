<?php

namespace Socrates;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
// use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Cerebro\CerebroProvider;
use Tramite\TramiteProvider;
use BotMan\Tinker\TinkerServiceProvider;
use BotMan\BotMan\BotManServiceProvider;
use BotMan\Studio\Providers\StudioServiceProvider;
use Socrates\Services\SocratesService;
use Socrates\Facades\Socrates as SocratesFacade;
use Socrates\Services\ApiService;
use Socrates\Facades\Api as ApiFacade;

use BotMan\Studio\Providers\DriverServiceProvider as ServiceProvider;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\FacebookDriver;
use BotMan\Drivers\Slack\SlackDriver;
use BotMan\Drivers\Telegram\TelegramDriver;
use BotMan\Drivers\Web\WebDriver;

class SocratesProvider extends ServiceProvider
{
    /**
     * The drivers that should be loaded to
     * use with BotMan
     *
     * @var array
     */
    protected $drivers = [
        SlackDriver::class,
        WebDriver::class
    ];

    /**
     * Alias the services in the boot.
     */
    public function boot()
    {
        parent::boot();

        foreach ($this->drivers as $driver) {
            DriverManager::loadDriver($driver);
        }

        /**
         * Socrates Routes
         */
        Route::group([
            'namespace' => '\Socrates\Http\Controllers',
        ], function (/**$router**/) {
            require __DIR__.'/../routes/web.php';
        });

        $this->mapBotManCommands();


        $this->registerViewComposers();
        
        // Register configs, migrations, etc
        $this->publishConfigs();
        $this->publishAssets();
        $this->publishMigrations();
    }

    /**
     * Defines the BotMan "hears" commands.
     *
     * @return void
     */
    protected function mapBotManCommands()
    {
        Route::group([
            'namespace' => '\Socrates\Http\Controllers',
        ], function (/**$router**/) {
            require __DIR__.'/../routes/botman.php';
        });
    }

    /**
     * Register the services.
     */
    public function register()
    {
        $this->loadConfigs();
        
        // Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // View namespace
        $viewsPath = __DIR__.'/../resources/views';
        $this->loadViewsFrom($viewsPath, 'socrates');
        $this->publishes(
            [
                $viewsPath => base_path('resources/views/vendor/socrates'),
            ], 'views'
        );

        $loader = AliasLoader::getInstance();
        
        $loader->alias('Socrates', SocratesFacade::class);
        $this->app->singleton(
            'socrates', function () {
                return new SocratesService();
            }
        );
        
        $loader->alias('Api', ApiFacade::class);
        $this->app->singleton(
            'api', function () {
                return new ApiService();
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            CerebroProvider::class,
            TramiteProvider::class,
            /*
             * Package Service Providers...
             */
            TinkerServiceProvider::class,
    
            /*
             * BotMan Service Providers...
             */
            BotManServiceProvider::class,
            StudioServiceProvider::class,
        ];
    }


    /**
     * Register view composers.
     */
    protected function registerViewComposers()
    {
        // // Register alerts
        // View::composer(
        //     'socrates::*', function ($view) {
        //         $view->with('alerts', SocratesFacade::alerts());
        //     }
        // );
    }


    protected function loadConfigs()
    {
        
        // Merge own configs into user configs 
        $this->mergeConfigFrom($this->getPublishesPath('config/botman/config.php'), 'botman.config');
        $this->mergeConfigFrom($this->getPublishesPath('config/botman/discord.php'), 'botman.discord');
        $this->mergeConfigFrom($this->getPublishesPath('config/botman/facebook.php'), 'botman.facebook');
        $this->mergeConfigFrom($this->getPublishesPath('config/botman/slack.php'), 'botman.slack');
        $this->mergeConfigFrom($this->getPublishesPath('config/botman/twitter.php'), 'botman.twitter');
        $this->mergeConfigFrom($this->getPublishesPath('config/botman/telegram.php'), 'botman.telegram');
        $this->mergeConfigFrom($this->getPublishesPath('config/botman/web.php'), 'botman.web');
    }

    protected function publishMigrations()
    {
        
       
    }
       
    protected function publishAssets()
    {
        
        // // Publish socrates css and js to public directory
        // $this->publishes(
        //     [
        //     $this->getDistPath('socrates') => public_path('assets/socrates')
        //     ], ['public',  'socrates', 'socrates-public']
        // );

    }

    protected function publishConfigs()
    {
        
        // Publish config files
        $this->publishes(
            [
            // Paths
            $this->getPublishesPath('config/botman') => config_path('botman'),
            // $this->getPublishesPath('config/chat') => config_path('chat'),
            // $this->getPublishesPath('config/managed') => config_path('managed'),
            // $this->getPublishesPath('config/settings') => config_path('settings'),
            // Files
            $this->getPublishesPath('config/socrates.php') => config_path('socrates.php')
            ], ['config',  'socrates', 'socrates-config']
        );

    }

    protected function getPublishesPath($path)
    {
        return __DIR__.'/../publishes/'.$path;
    }
}
