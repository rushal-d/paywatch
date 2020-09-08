<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequestRequest extends FormRequest
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
            'staff_central_id' => 'required',
            'leave_id' => 'required',
            'from_leave_day_np' => 'required',
            'from_leave_day' => 'required',
            'to_leave_day_np' => 'required',
            'to_leave_day' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'staff_central_id.required' => 'You must enter the Staff Name!',
            'leave_id.required' => 'You must enter the Staff Name!',
            'from_leave_day_np.required' => 'You must select leave date from!',
            'from_leave_day.required' => 'You must select leave date from!',
            'to_leave_day_np.required' => 'You must select leave date to!',
            'to_leave_day.required' => 'You must select leave date to!',
        ];
    }
}
