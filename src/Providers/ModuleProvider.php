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
use TypiCMS\Modules\Tags\Services\Form\TagForm;
use TypiCMS\Modules\Tags\Services\Form\TagFormLaravelValidator;
use TypiCMS\Services\Cache\LaravelCache;
use View;

class ModuleProvider extends ServiceProvider
{

    public function boot()
    {
        // Bring in the routes
        require __DIR__ . '/../routes.php';

        // Add dirs
        View::addNamespace('tags', __DIR__ . '/../views/');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'tags');
        $this->publishes([
            __DIR__ . '/../config/' => config_path('typicms/tags'),
        ], 'config');
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

        $app->bind('TypiCMS\Modules\Tags\Services\Form\TagForm', function (Application $app) {
            return new TagForm(
                new TagFormLaravelValidator($app['validator']),
                $app->make('TypiCMS\Modules\Tags\Repositories\TagInterface')
            );
        });
    }
}
