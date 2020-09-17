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
    /**
     * Define the routes for the application.
     */
    public function map()
    {
        Route::namespace($this->namespace)->group(function (Router $router) {
            /*
             * Front office routes
             */
            if ($page = TypiCMS::getPageLinkedToModule('tags')) {
                $router->middleware('public')->group(function (Router $router) use ($page) {
                    $options = $page->private ? ['middleware' => 'auth'] : [];
                    foreach (locales() as $lang) {
                        if ($page->translate('status', $lang) && $uri = $page->uri($lang)) {
                            $router->get($uri, $options + ['uses' => [PublicController::class, 'index']])->name($lang.'::index-tags');
                            $router->get($uri.'/{slug}', $options + ['uses' => [PublicController::class, 'show']])->name($lang.'::tag');
                        }
                    }
                });
            }

            /*
             * Admin routes
             */
            $router->middleware('admin')->prefix('admin')->group(function (Router $router) {
                $router->get('tags', [AdminController::class, 'index'])->name('admin::index-tags')->middleware('can:read tags');
                $router->get('tags/create', [AdminController::class, 'create'])->name('admin::create-tag')->middleware('can:create tags');
                $router->get('tags/{tag}/edit', [AdminController::class, 'edit'])->name('admin::edit-tag')->middleware('can:update tags');
                $router->post('tags', [AdminController::class, 'store'])->name('admin::store-tag')->middleware('can:create tags');
                $router->put('tags/{tag}', [AdminController::class, 'update'])->name('admin::update-tag')->middleware('can:update tags');
            });

            /*
             * API routes
             */
            $router->middleware('api')->prefix('api')->group(function (Router $router) {
                $router->get('tags-list', [ApiController::class, 'tagsList']);
                $router->middleware('auth:api')->group(function (Router $router) {
                    $router->get('tags', [ApiController::class, 'index'])->middleware('can:read tags');
                    $router->patch('tags/{tag}', [ApiController::class, 'updatePartial'])->middleware('can:update tags');
                    $router->delete('tags/{tag}', [ApiController::class, 'destroy'])->middleware('can:delete tags');
                });
            });
        });
    }
}
