<?php

namespace TypiCMS\Modules\Tags\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use TypiCMS\Modules\Core\Facades\TypiCMS;
use TypiCMS\Modules\Tags\Http\Controllers\AdminController;
use TypiCMS\Modules\Tags\Http\Controllers\ApiController;
use TypiCMS\Modules\Tags\Http\Controllers\PublicController;

class RouteServiceProvider extends ServiceProvider
{
    public function map(): void
    {
        /*
         * Front office routes
         */
        if ($page = TypiCMS::getPageLinkedToModule('tags')) {
            $middleware = $page->private ? ['public', 'auth'] : ['public'];
            foreach (locales() as $lang) {
                if ($page->isPublished($lang) && $uri = $page->uri($lang)) {
                    Route::middleware($middleware)->prefix($uri)->name($lang.'::')->group(function (Router $router) {
                        $router->get('/', [PublicController::class, 'index'])->name('index-tags');
                        $router->get('{slug}', [PublicController::class, 'show'])->name('tag');
                    });
                }
            }
        }

        /*
         * Admin routes
         */
        Route::middleware('admin')->prefix('admin')->name('admin::')->group(function (Router $router) {
            $router->get('tags', [AdminController::class, 'index'])->name('index-tags')->middleware('can:read tags');
            $router->get('tags/create', [AdminController::class, 'create'])->name('create-tag')->middleware('can:create tags');
            $router->get('tags/{tag}/edit', [AdminController::class, 'edit'])->name('edit-tag')->middleware('can:read tags');
            $router->post('tags', [AdminController::class, 'store'])->name('store-tag')->middleware('can:create tags');
            $router->put('tags/{tag}', [AdminController::class, 'update'])->name('update-tag')->middleware('can:update tags');
        });

        /*
         * API routes
         */
        Route::middleware('api')->prefix('api')->group(function (Router $router) {
            $router->get('tags-list', [ApiController::class, 'tagsList']);
            $router->middleware('auth:api')->group(function (Router $router) {
                $router->get('tags', [ApiController::class, 'index'])->middleware('can:read tags');
                $router->patch('tags/{tag}', [ApiController::class, 'updatePartial'])->middleware('can:update tags');
                $router->delete('tags/{tag}', [ApiController::class, 'destroy'])->middleware('can:delete tags');
            });
        });
    }
}
