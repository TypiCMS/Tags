<?php

namespace TypiCMS\Modules\Tags\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use TypiCMS\Modules\Core\EloquentRepository;
use TypiCMS\Modules\Tags\Models\Tag;
use stdClass;

class EloquentTag extends EloquentRepository
{
    protected $repositoryId = 'tags';

    protected $model = Tag::class;

    /**
     * Get paginated models.
     *
     * @param int   $page  Number of models per page
     * @param int   $limit Results per page
     * @param bool  $all   get published models or all
     * @param array $with  Eager load related models
     *
     * @return stdClass Object with $items && $totalItems for pagination
     */
    public function byPage($page = 1, $limit = 10, array $with = [], $all = false)
    {
        $result = new stdClass();
        $result->page = $page;
        $result->limit = $limit;
        $result->totalItems = 0;
        $result->items = [];

        $query = $this->model->select(
            'id',
            'tag',
            'slug',
            DB::raw(
                '(SELECT COUNT(*) FROM `'.
                DB::getTablePrefix().
                'taggables` WHERE `tag_id` = `'.
                DB::getTablePrefix().
                "tags`.`id`) AS 'uses'"
            )
        )
        ->order();

        $models = $query->skip($limit * ($page - 1))
                        ->take($limit)
                        ->get();

        // Put items and totalItems in stdClass
        $result->totalItems = $this->model->count();
        $result->items = $models->all();

        return $result;
    }

    /**
     * Get all models.
     *
     * @param bool  $all  Show published or all
     * @param array $with Eager load related models
     *
     * @return Collection
     */
    public function all(array $with = [], $all = false)
    {
        $query = $this->model->select(
            'id',
            'tag',
            'slug',
            DB::raw(
                '(SELECT COUNT(*) FROM `'.
                DB::getTablePrefix().
                'taggables` WHERE `tag_id` = `'.
                DB::getTablePrefix().
                "tags`.`id`) AS 'uses'"
            )
        )
        ->order();

        return $query->get();
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
        $foundTags = $this->model->whereIn('tag', $tags)->get();

        $returnTags = [];

        if ($foundTags) {
            foreach ($foundTags as $tag) {
                $pos = array_search($tag->tag, $tags);

                // Add returned tags to array
                if ($pos !== false) {
                    $returnTags[] = $tag;
                    unset($tags[$pos]);
                }
            }
        }

        // Add remainings tags as new
        foreach ($tags as $tag) {
            $returnTags[] = $this->model->create([
                'tag'  => $tag,
                'slug' => Str::slug($tag),
            ]);
        }

        return $returnTags;
    }

    /**
     * Get single model by Slug where status = 1.
     *
     * @param string $slug
     * @param array  $attributes
     *
     * @return mixed
     */
    public function bySlug($slug, $attributes = ['*'])
    {
        $model = $this
            ->findBy('slug', $slug, $attributes);

        if (is_null($model)) {
            abort(404);
        }

        return $model;
    }
}
