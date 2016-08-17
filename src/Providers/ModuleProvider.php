<?php

namespace TypiCMS\Modules\Objects\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Core\Shells\Facades\TypiCMS;
use TypiCMS\Modules\Core\Shells\Observers\FileObserver;
use TypiCMS\Modules\Core\Shells\Observers\SlugObserver;
use TypiCMS\Modules\Core\Shells\Services\Cache\LaravelCache;
use TypiCMS\Modules\Objects\Shells\Models\Object;
use TypiCMS\Modules\Objects\Shells\Models\ObjectTranslation;
use TypiCMS\Modules\Objects\Shells\Repositories\CacheDecorator;
use TypiCMS\Modules\Objects\Shells\Repositories\EloquentObject;

class ModuleProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'typicms.objects'
        );

        $modules = $this->app['config']['typicms']['modules'];
        $this->app['config']->set('typicms.modules', array_merge(['objects' => ['linkable_to_page', 'srcDir' => __DIR__.'/../']], $modules));

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'objects');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'objects');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/objects'),
        ], 'views');
        $this->publishes([
            __DIR__.'/../database' => base_path('database'),
        ], 'migrations');

        AliasLoader::getInstance()->alias(
            'Objects',
            'TypiCMS\Modules\Objects\Shells\Facades\Facade'
        );

        // Observers
        ObjectTranslation::observe(new SlugObserver());
        Object::observe(new FileObserver());
    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register('TypiCMS\Modules\Objects\Shells\Providers\RouteServiceProvider');

        /*
         * Sidebar view composer
         */
        $app->view->composer('core::admin._sidebar', 'TypiCMS\Modules\Objects\Shells\Composers\SidebarViewComposer');

        /*
         * Add the page in the view.
         */
        $app->view->composer('objects::public.*', function ($view) {
            $view->page = TypiCMS::getPageLinkedToModule('objects');
        });

        $app->bind('TypiCMS\Modules\Objects\Shells\Repositories\ObjectInterface', function (Application $app) {
            $repository = new EloquentObject(new Object());
            if (!config('typicms.cache')) {
                return $repository;
            }
            $laravelCache = new LaravelCache($app['cache'], 'objects', 10);

            return new CacheDecorator($repository, $laravelCache);
        });
    }
}
