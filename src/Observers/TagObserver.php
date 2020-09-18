<?php

namespace TypiCMS\Modules\Tags\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tags;
use TypiCMS\Modules\Tags\Models\Tag;

class TagObserver
{
    public function saved(Model $model)
    {
        $tags = $this->processTags(request('tags'));
        $this->syncTags($model, $tags);
    }

    /**
     * Convert string of tags to array.
     *
     * @param mixed $tags
     */
    protected function processTags($tags): array
    {
        if (!$tags) {
            return [];
        }

        $tags = explode(',', $tags);

        foreach ($tags as $key => $tag) {
            $tags[$key] = trim($tag);
        }

        return $tags;
    }

    protected function syncTags(Model $model, array $tags)
    {
        if (!method_exists($model, 'tags')) {
            Log::info('Model doesn’t have a method called tags');

            return false;
        }

        // Create or add tags
        $tagIds = [];

        if ($tags) {
            $foundTags = Tag::whereIn('tag', $tags)->get();

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
                $returnTags[] = Tag::create([
                    'tag' => $tag,
                    'slug' => Str::slug($tag),
                ]);
            }

            foreach ($returnTags as $tag) {
                $tagIds[] = $tag->id;
            }
        }

        // Assign tags to model
        $model->tags()->sync($tagIds);
    }
}
