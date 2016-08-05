<?php

namespace TypiCMS\Modules\Objects\Http\Requests;

use TypiCMS\Modules\Core\Custom\Http\Requests\AbstractFormRequest;

class FormRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            '*.title' => 'max:255',
            '*.slug'  => 'max:255',
        ];
    }
}
