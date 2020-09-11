<?php

namespace TypiCMS\Modules\Tags\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Filters\FilterOr;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Tags\Models\Tag;

class ApiController extends BaseApiController
{
    public function index(Request $request): LengthAwarePaginator
    {
        $data = QueryBuilder::for(Tag::class)
            ->selectFields($request->input('fields.tags'))
            ->allowedSorts(['tag', 'uses'])
            ->allowedFilters([
                AllowedFilter::custom('tag', new FilterOr()),
            ])
            ->addSelect(
                DB::raw(
                    '(SELECT COUNT(*) FROM `'.
                    DB::getTablePrefix().
                    'taggables` WHERE `tag_id` = `'.
                    DB::getTablePrefix().
                    "tags`.`id`) AS 'uses'"
                )
            )
            ->paginate($request->input('per_page'));

        return $data;
    }

    public function tagsList(Request $request)
    {
        $models = QueryBuilder::for(Tag::class)
            ->get();

        return $models;
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
    }
}
