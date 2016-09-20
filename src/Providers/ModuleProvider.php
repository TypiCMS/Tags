<?php

namespace TypiCMS\Modules\Tags\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Core\Facades\TypiCMS;
use TypiCMS\Modules\Tags\Repositories\EloquentTag;

class ModuleProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'typicms.tags'
        );

        $modules = $this->app['config']['typicms']['modules'];
        $this->app['config']->set('typicms.modules', array_merge(['tags' => ['linkable_to_page']], $modules));

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'tags');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'tags');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/tags'),
        ], 'views');
        $this->publishes([
            __DIR__.'/../database' => base_path('database'),
        ], 'migrations');

        AliasLoader::getInstance()->alias(
            'Tags',
            'TypiCMS\Modules\Tags\Facades\Tags'
        );
    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register('TypiCMS\Modules\Tags\Providers\RouteServiceProvider');

        /*
         * Sidebar view composer
         */
        $app->view->composer('core::admin._sidebar', 'TypiCMS\Modules\Tags\Composers\SidebarViewComposer');

        /*
         * Add the page in the view.
         */
        $app->view->composer('tags::public.*', function ($view) {
            $view->page = TypiCMS::getPageLinkedToModule('tags');
        });

        $app->bind('Tags', EloquentTag::class);
    }
}
