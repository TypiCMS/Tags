<?php

namespace TypiCMS\Modules\Tags\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Tags\Http\Requests\FormRequest;
use TypiCMS\Modules\Tags\Models\Tag;
use TypiCMS\Modules\Tags\Repositories\EloquentTag;

class ApiController extends BaseApiController
{
    public function __construct(EloquentTag $tag)
    {
        parent::__construct($tag);
    }

    public function index(Request $request)
    {
        $models = QueryBuilder::for(Tag::class)
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

        return $models;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \TypiCMS\Modules\Tags\Models\Tag $tag
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Tag $tag)
    {
        $deleted = $this->repository->delete($tag);

        return response()->json([
            'error' => !$deleted,
        ]);
    }
}
