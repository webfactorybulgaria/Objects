<?php

namespace TypiCMS\Modules\Objects\Providers;

use Illuminate\Support\Facades\Route;
use TypiCMS\Modules\Core\Shells\Facades\TypiCMS;
use TypiCMS\Modules\Core\Shells\Providers\BaseRouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'TypiCMS\Modules\Objects\Shells\Http\Controllers';

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        Route::group(['namespace' => $this->namespace], function () {

            /*
             * Front office routes
             */
            if ($page = TypiCMS::getPageLinkedToModule('objects')) {
                $options = $page->private ? ['middleware' => 'auth'] : [];
                foreach (config('translatable.locales') as $lang) {
                    if ($page->translate($lang)->status && $uri = $page->uri($lang)) {
                        Route::get($uri, $options + ['as' => $lang.'.objects', 'uses' => 'PublicController@index']);
                        Route::get($uri.'/{slug}', $options + ['as' => $lang.'.objects.slug', 'uses' => 'PublicController@show']);
                    }
                }
            }

            /*
             * Admin routes
             */
            Route::get('admin/objects', 'AdminController@index')->name('admin::index-objects');
            Route::get('admin/objects/create', 'AdminController@create')->name('admin::create-object');
            Route::get('admin/objects/{object}/edit', 'AdminController@edit')->name('admin::edit-object');
            Route::post('admin/objects', 'AdminController@store')->name('admin::store-object');
            Route::put('admin/objects/{object}', 'AdminController@update')->name('admin::update-object');

            /*
             * API routes
             */
            Route::get('api/objects', 'ApiController@index')->name('api::index-objects');
            Route::put('api/objects/{object}', 'ApiController@update')->name('api::update-object');
            Route::delete('api/objects/{object}', 'ApiController@destroy')->name('api::destroy-object');
        });
    }
}
