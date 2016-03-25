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
            $router->get('admin/tags', ['as' => 'admin.tags.index', 'uses' => 'AdminController@index']);
            $router->get('admin/tags/create', ['as' => 'admin.tags.create', 'uses' => 'AdminController@create']);
            $router->get('admin/tags/{tag}/edit', ['as' => 'admin.tags.edit', 'uses' => 'AdminController@edit']);
            $router->post('admin/tags', ['as' => 'admin.tags.store', 'uses' => 'AdminController@store']);
            $router->put('admin/tags/{tag}', ['as' => 'admin.tags.update', 'uses' => 'AdminController@update']);

            /*
             * API routes
             */
            $router->get('api/tags', ['as' => 'api.tags.index', 'uses' => 'ApiController@index']);
            $router->put('api/tags/{tag}', ['as' => 'api.tags.update', 'uses' => 'ApiController@update']);
            $router->delete('api/tags/{tag}', ['as' => 'api.tags.destroy', 'uses' => 'ApiController@destroy']);
        });
    }
}
