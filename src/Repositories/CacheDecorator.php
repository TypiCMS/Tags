<?php

namespace TypiCMS\Modules\Tags\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Request;
use TypiCMS\Modules\Core\Repositories\CacheAbstractDecorator;
use TypiCMS\Modules\Core\Services\Cache\CacheInterface;

class CacheDecorator extends CacheAbstractDecorator implements TagInterface
{
    public function __construct(TagInterface $repo, CacheInterface $cache)
    {
        $this->repo = $repo;
        $this->cache = $cache;
    }

    /**
     * Get paginated pages.
     *
     * @param int  $page  Number of pages per page
     * @param int  $limit Results per page
     * @param bool $all   Show published or all
     *
     * @return stdClass Object with $items && $totalItems for pagination
     */
    public function byPage($page = 1, $limit = 10, array $with = [], $all = false)
    {
        $cacheKey = md5(config('app.locale').'byPage.'.$page.$limit.$all.implode('.', Request::except('page')));

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $models = $this->repo->byPage($page, $limit, $with, $all);

        // Store in cache for next request
        $this->cache->put($cacheKey, $models);

        return $models;
    }

    /**
     * Get all tags.
     *
     * @param bool $all Show published or all
     *
     * @return Collection
     */
    public function all(array $with = [], $all = false)
    {
        $cacheKey = md5(config('app.locale').'all'.implode('.', $with).$all);

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $models = $this->repo->all($with, $all);

        // Store in cache for next request
        $this->cache->put($cacheKey, $models);

        return $models;
    }

    /**
     * Find existing tags or create if they don't exist.
     *
     * @param array $tags Array of strings, each representing a tag
     *
     * @return array Array or Arrayable collection of Tag objects
     */
    public function findOrCreate(array $tags)
    {
        $this->cache->flush();

        return $this->repo->findOrCreate($tags);
    }

    /**
     * Get single model by slug.
     *
     * @param string $slug of model
     * @param array  $with
     *
     * @return object object of model information
     */
    public function bySlug($slug, array $with = [])
    {
        // Build the cache key, unique per model slug
        $cacheKey = md5(config('app.locale').'bySlug'.$slug.implode('.', $with).implode('.', Request::all()));

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        // Item not cached, retrieve it
        $model = $this->repo->bySlug($slug, $with);

        // Store in cache for next request
        $this->cache->put($cacheKey, $model);

        return $model;
    }
}
