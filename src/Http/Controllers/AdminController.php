<?php

namespace TypiCMS\Modules\Tags\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Tags\Http\Requests\FormRequest;
use TypiCMS\Modules\Tags\Models\Tag;

class AdminController extends BaseAdminController
{
    public function index(): View
    {
        return view('tags::admin.index');
    }

    public function create(): View
    {
        $model = new Tag();

        return view('tags::admin.create')
            ->with(compact('model'));
    }

    public function edit(Tag $tag): View
    {
        return view('tags::admin.edit')
            ->with(['model' => $tag]);
    }

    public function store(FormRequest $request): RedirectResponse
    {
        $tag = Tag::create($request->validated());

        return $this->redirect($request, $tag);
    }

    public function update(Tag $tag, FormRequest $request): RedirectResponse
    {
        $tag->update($request->validated());

        return $this->redirect($request, $tag);
    }
}
