<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CasteRequest extends FormRequest
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
     *
     * @return array
     */
    public function rules()
    {
        return [
            'caste_name' => 'required'
        ];
    }

    public function message()
    {
        return [
            'caste_name.required' => 'You must enter the caste name!',
        ];
    }
}
