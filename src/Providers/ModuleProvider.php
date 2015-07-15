<?php
namespace TypiCMS\Modules\Tags\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Laracasts\Utilities\JavaScript\JavaScriptFacade as JavaScript;
use TypiCMS\Modules\Core\Facades\TypiCMS;
use TypiCMS\Modules\Core\Services\Cache\LaravelCache;
use TypiCMS\Modules\Tags\Models\Tag;
use TypiCMS\Modules\Tags\Repositories\CacheDecorator;
use TypiCMS\Modules\Tags\Repositories\EloquentTag;

class ModuleProvider extends ServiceProvider
{

    public function boot()
    {

        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php', 'typicms.tags'
        );

        $modules = $this->app['config']['typicms']['modules'];
        $this->app['config']->set('typicms.modules', array_merge(['tags' => ['linkable_to_page']], $modules));

        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'tags');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'tags');

        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/tags'),
        ], 'views');
        $this->publishes([
            __DIR__ . '/../database' => base_path('database'),
        ], 'migrations');

        AliasLoader::getInstance()->alias(
            'Tags',
            'TypiCMS\Modules\Tags\Facades\Facade'
        );

        try {
            JavaScript::put([
                'tags' => Tag::lists('tag')->all()
            ]);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
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
            if (! config('typicms.cache')) {
                return $repository;
            }
            $laravelCache = new LaravelCache($app['cache'], 'tags', 10);

            return new CacheDecorator($repository, $laravelCache);
        });

        /**
         * Return the page linked to this module (for @inject in views)
         */
        $app->singleton('typicms.tags.page', function (Application $app) {
            return TypiCMS::getPageLinkedToModule('tags');
        });

    }
}
