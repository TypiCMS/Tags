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

        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php', 'typicms.tags'
        );

        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'tags');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'tags');

        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/tags'),
        ], 'views');
        $this->publishes([
            __DIR__ . '/../database' => base_path('database'),
        ], 'migrations');
        $this->publishes([
            __DIR__ . '/../../tests' => base_path('tests'),
        ], 'tests');

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
        $app->view->composer('core::admin._sidebar', 'TypiCMS\Modules\Tags\Composers\SidebarViewComposer');

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
