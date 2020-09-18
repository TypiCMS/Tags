<?php

namespace TypiCMS\Modules\Tags\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Core\Facades\TypiCMS;
use TypiCMS\Modules\Tags\Composers\SidebarViewComposer;
use TypiCMS\Modules\Tags\Facades\Tags;
use TypiCMS\Modules\Tags\Models\Tag;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'typicms.tags');
        $this->mergeConfigFrom(__DIR__.'/../config/permissions.php', 'typicms.permissions');

        $modules = $this->app['config']['typicms']['modules'];
        $this->app['config']->set('typicms.modules', array_merge(['tags' => ['linkable_to_page']], $modules));

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'tags');

        $this->publishes([
            __DIR__.'/../database/migrations/create_tags_table.php.stub' => getMigrationFileName('create_tags_table'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/tags'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../resources/scss' => resource_path('scss'),
        ], 'resources');

        AliasLoader::getInstance()->alias('Tags', Tags::class);

        /*
         * Sidebar view composer
         */
        $this->app->view->composer('core::admin._sidebar', SidebarViewComposer::class);

        /*
         * Add the page in the view.
         */
        $this->app->view->composer('tags::public.*', function ($view) {
            $view->page = TypiCMS::getPageLinkedToModule('tags');
        });
    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register(RouteServiceProvider::class);

        $app->bind('Tags', Tag::class);
    }
}
