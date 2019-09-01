<?php

namespace TypiCMS\Modules\Tags\Http\Controllers;

use TypiCMS;
use TypiCMS\Modules\Core\Http\Controllers\BasePublicController;

class PublicController extends BasePublicController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $perPage = config('typicms.tags.per_page');
        $models = $this->model->paginate($perPage);

        return view('tags::public.index')
            ->with(compact('models'));
    }

    /**
     * Show news.
     *
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $model = $this->model->bySlug($slug);

        return view('tags::public.show')
            ->with(compact('model'));
    }
}
