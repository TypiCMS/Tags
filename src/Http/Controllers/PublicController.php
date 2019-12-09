<?php

namespace TypiCMS\Modules\Tags\Http\Controllers;

use Illuminate\View\View;
use TypiCMS\Modules\Core\Http\Controllers\BasePublicController;
use TypiCMS\Modules\Tags\Models\Tag;

class PublicController extends BasePublicController
{
    public function index(): View
    {
        $perPage = config('typicms.tags.per_page');
        $models = Tag::paginate($perPage);

        return view('tags::public.index')
            ->with(compact('models'));
    }

    public function show($slug): View
    {
        $model = Tag::where('slug', $slug)->firstOrFail();

        return view('tags::public.show')
            ->with(compact('model'));
    }
}
