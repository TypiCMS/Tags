<?php
namespace TypiCMS\Modules\Tags\Http\Controllers;

use Illuminate\Support\Str;
use Input;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use TypiCMS;
use TypiCMS\Http\Controllers\BasePublicController;
use TypiCMS\Modules\Tags\Repositories\TagInterface;
use View;

class PublicController extends BasePublicController
{

    public function __construct(TagInterface $tag)
    {
        parent::__construct($tag);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
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
     * @return Response
     */
    public function show($slug)
    {
        $model = $this->repository->bySlug($slug);
        return view('tags::public.show')
            ->with(compact('model'));
    }
}
