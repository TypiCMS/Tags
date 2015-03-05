<?php
namespace TypiCMS\Modules\Tags\Http\Controllers;

use Illuminate\Support\Str;
use View;
use TypiCMS;
use TypiCMS\Modules\Tags\Repositories\TagInterface;
use TypiCMS\Http\Controllers\BasePublicController;

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
        TypiCMS::setModel($this->repository->getModel());

        $tags = $this->repository->getAll();

        return view('tags::public.index')
            ->with(compact('tags'));
    }

    /**
     * Show news.
     *
     * @return Response
     */
    public function show($slug)
    {
        $model = $this->repository->bySlug($slug);

        TypiCMS::setModel($model);

        return view('tags::public.show')
            ->with(compact('model'));
    }
}
