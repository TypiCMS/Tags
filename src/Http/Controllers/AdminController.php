<?php

namespace TypiCMS\Modules\Tags\Http\Controllers;

use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Tags\Http\Requests\FormRequest;
use TypiCMS\Modules\Tags\Models\Tag;

class AdminController extends BaseAdminController
{
    /**
     * List models.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('tags::admin.index');
    }

    /**
     * Create form for a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = new;

        return view('tags::admin.create')
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
        return view('tags::admin.edit')
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
        $tag = ::create($request->all());

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
        ::update($request->id, $request->all());

        return $this->redirect($request, $tag);
    }
}
