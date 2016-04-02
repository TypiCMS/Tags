<?php

namespace TypiCMS\Modules\Tags\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
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
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function (Router $router) {

            /*
             * Front office routes
             */
            if ($page = TypiCMS::getPageLinkedToModule('tags')) {
                $options = $page->private ? ['middleware' => 'auth'] : [];
                foreach (config('translatable.locales') as $lang) {
                    if ($page->translate($lang)->status && $uri = $page->uri($lang)) {
                        $router->get($uri, $options + ['as' => $lang.'.tags', 'uses' => 'PublicController@index']);
                        $router->get($uri.'/{slug}', $options + ['as' => $lang.'.tags.slug', 'uses' => 'PublicController@show']);
                    }
                }
            }

            /*
             * Admin routes
             */
            $router->get('admin/tags', 'AdminController@index')->name('admin::index-tags');
            $router->get('admin/tags/create', 'AdminController@create')->name('admin::create-tags');
            $router->get('admin/tags/{tag}/edit', 'AdminController@edit')->name('admin::edit-tags');
            $router->post('admin/tags', 'AdminController@store')->name('admin::store-tags');
            $router->put('admin/tags/{tag}', 'AdminController@update')->name('admin::update-tags');

            /*
             * API routes
             */
            $router->get('api/tags', 'ApiController@index')->name('api::index-tags');
            $router->put('api/tags/{tag}', 'ApiController@update')->name('api::update-tags');
            $router->delete('api/tags/{tag}', 'ApiController@destroy')->name('api::destroy-tags');
        });
    }
}
