<?php

namespace TypiCMS\Modules\Tags\Http\Requests;

use TypiCMS\Modules\Core\Http\Requests\AbstractFormRequest;

class FormRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'tag' => 'required|max:255',
            'slug' => 'required|max:255|alpha_dash',
        ];
    }
}
