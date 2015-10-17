<?php

namespace TypiCMS\Modules\Tags\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Input;
use TypiCMS;
use TypiCMS\Modules\Core\Http\Controllers\BasePublicController;
use TypiCMS\Modules\Tags\Repositories\TagInterface;

class PublicController extends BasePublicController
{
    public function __construct(TagInterface $tag)
    {
        parent::__construct($tag);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function index()
    {
        $page = Input::get('page');
        $perPage = config('typicms.tags.per_page');
        $data = $this->repository->byPage($page, $perPage, ['translations']);
        $models = new Paginator($data->items, $data->totalItems, $perPage, null, ['path' => Paginator::resolveCurrentPath()]);

        return view('tags::public.index')
            ->with(compact('models'));
    }

    /**
     * Show news.
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function show($slug)
    {
        $model = $this->repository->bySlug($slug);

        return view('tags::public.show')
            ->with(compact('model'));
    }
}
