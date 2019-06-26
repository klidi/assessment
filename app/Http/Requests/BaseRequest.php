<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * i am addding validation only for the 2 params we are going
     * to use more can be added in feature.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'year'  => 'integer|min:1900', //
            'limit' => 'integer',
        ];
    }
}
