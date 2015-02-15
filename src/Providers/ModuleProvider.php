<?php
namespace TypiCMS\Modules\Tags\Providers;

use Config;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Lang;
use TypiCMS\Modules\Tags\Models\Tag;
use TypiCMS\Modules\Tags\Repositories\CacheDecorator;
use TypiCMS\Modules\Tags\Repositories\EloquentTag;
use TypiCMS\Services\Cache\LaravelCache;
use View;

class ModuleProvider extends ServiceProvider
{

    public function boot()
    {
        // Add dirs
        View::addNamespace('tags', __DIR__ . '/../views/');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'tags');
        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php', 'typicms.tags'
        );
        $this->publishes([
            __DIR__ . '/../migrations/' => base_path('/database/migrations'),
        ], 'migrations');

        AliasLoader::getInstance()->alias(
            'Tags',
            'TypiCMS\Modules\Tags\Facades\Facade'
        );
    }

    public function register()
    {

        $app = $this->app;

        /**
         * Register route service provider
         */
        $app->register('TypiCMS\Modules\Tags\Providers\RouteServiceProvider');

        /**
         * Sidebar view composer
         */
        $app->view->composer('core::admin._sidebar', 'TypiCMS\Modules\Tags\Composers\SideBarViewComposer');

        $app->bind('TypiCMS\Modules\Tags\Repositories\TagInterface', function (Application $app) {
            $repository = new EloquentTag(new Tag);
            if (! Config::get('app.cache')) {
                return $repository;
            }
            $laravelCache = new LaravelCache($app['cache'], 'tags', 10);

            return new CacheDecorator($repository, $laravelCache);
        });

    }
}
