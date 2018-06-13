<?php

namespace TypiCMS\Modules\Tags\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use stdClass;
use TypiCMS\Modules\Core\Repositories\EloquentRepository;
use TypiCMS\Modules\Tags\Models\Tag;

class EloquentTag extends EloquentRepository
{
    protected $repositoryId = 'tags';

    protected $model = Tag::class;

    public function paginate($perPage = null, $attributes = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        return $this->executeCallback(get_called_class(), __FUNCTION__, array_merge(func_get_args(), compact('page')), function () use ($perPage, $attributes, $pageName, $page) {
            return $this->prepareQuery($this->createModel())->order()->paginate($perPage, $attributes, $pageName, $page);
        });
    }

    /**
     * Get all tags with uses count.
     *
     * @return Collection
     */
    public function allWithUses()
    {
        $query = $this->createModel()->select(
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
        ->orderBy('uses', 'desc');

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
        $foundTags = $this->findWhereIn(['tag', $tags]);

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
            $returnTags[] = $this->create([
                'tag' => $tag,
                'slug' => str_slug($tag),
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
