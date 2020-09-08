<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SystemHolidayMastRequest extends FormRequest
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
            'branch_id' => 'required|exists:system_office_mast,office_id',
            'branch_id.*' => 'required',
            'caste_id' => 'required|array',
            'caste_id.*' => 'required|exists:castes,id',
            'religion_id' => 'required|exists:religions,id',
            'name' => 'required',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ];
    }
}
