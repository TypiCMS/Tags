<?php
namespace TypiCMS\Modules\Tags\Providers;

use Config;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'TypiCMS\Modules\Tags\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        $router->model('tags', 'TypiCMS\Modules\Tags\Models\Tag');
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function($router) {

            /**
             * Front office routes
             */
            $routes = $this->app->make('TypiCMS.routes');
            foreach (Config::get('translatable.locales') as $lang) {
                if (isset($routes['tags'][$lang])) {
                    $uri = $routes['tags'][$lang];
                    $router->get($uri, array('as' => $lang.'.tags', 'uses' => 'PublicController@index'));
                    $router->get($uri.'/{slug}', array('as' => $lang.'.tags.slug', 'uses' => 'PublicController@show'));
                }
            }

            /**
             * Admin routes
             */
            $router->resource('admin/tags', 'AdminController');

            /**
             * API routes
             */
            $router->resource('api/tags', 'ApiController');
        });
    }

}
