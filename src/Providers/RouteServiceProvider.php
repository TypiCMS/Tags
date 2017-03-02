<?php

namespace TypiCMS\Modules\Tags\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use TypiCMS\Modules\Core\Facades\TypiCMS;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'TypiCMS\Modules\Tags\Http\Controllers';

    /**
     * Define the routes for the application.
     *
     * @return null
     */
    public function map()
    {
        Route::group(['namespace' => $this->namespace], function (Router $router) {

            /*
             * Front office routes
             */
            if ($page = TypiCMS::getPageLinkedToModule('tags')) {
                $options = $page->private ? ['middleware' => 'auth'] : [];
                foreach (locales() as $lang) {
                    if ($page->translate('status', $lang) && $uri = $page->uri($lang)) {
                        $router->get($uri, $options + ['uses' => 'PublicController@index'])->name($lang.'::index-tags');
                        $router->get($uri.'/{slug}', $options + ['uses' => 'PublicController@show'])->name($lang.'::tag');
                    }
                }
            }

            /*
             * Admin routes
             */
            $router->group(['middleware' => 'admin', 'prefix' => 'admin'], function (Router $router) {
                $router->get('tags', 'AdminController@index')->name('admin::index-tags');
                $router->get('tags/create', 'AdminController@create')->name('admin::create-tag');
                $router->get('tags/{tag}/edit', 'AdminController@edit')->name('admin::edit-tag');
                $router->post('tags', 'AdminController@store')->name('admin::store-tag');
                $router->put('tags/{tag}', 'AdminController@update')->name('admin::update-tag');
                $router->patch('tags/{ids}', 'AdminController@ajaxUpdate')->name('admin::update-tag');
                $router->delete('tags/{ids}', 'AdminController@destroyMultiple')->name('admin::destroy-tag');
            });

            $router->group(['middleware' => 'api', 'prefix' => 'api'], function (Router $router) {
                $router->get('tags', 'ApiController@index')->name('api::index-tags');
            });

        });
    }
}
