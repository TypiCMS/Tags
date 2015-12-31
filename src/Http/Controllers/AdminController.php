<?php

namespace TypiCMS\Modules\Tags\Http\Controllers;

use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Tags\Http\Requests\FormRequest;
use TypiCMS\Modules\Tags\Models\Tag;
use TypiCMS\Modules\Tags\Repositories\TagInterface;

class AdminController extends BaseAdminController
{
    public function __construct(TagInterface $tag)
    {
        parent::__construct($tag);
    }

    /**
     * Create form for a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = $this->repository->getModel();

        return view('core::admin.create')
            ->with(compact('model'));
    }

    /**
     * Edit form for the specified resource.
     *
     * @param \TypiCMS\Modules\Tags\Models\Tag $tag
     *
     * @return \Illuminate\View\View
     */
    public function edit(Tag $tag)
    {
        return view('core::admin.edit')
            ->with(['model' => $tag]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \TypiCMS\Modules\Tags\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FormRequest $request)
    {
        $tag = $this->repository->create($request->all());

        return $this->redirect($request, $tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \TypiCMS\Modules\Tags\Models\Tag                $model
     * @param \TypiCMS\Modules\Tags\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Tag $tag, FormRequest $request)
    {
        $this->repository->update($request->all());

        return $this->redirect($request, $tag);
    }
}
