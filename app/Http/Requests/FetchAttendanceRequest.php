<?php

namespace App\Http\Requests;

use App\Rules\FutureTime;
use Illuminate\Foundation\Http\FormRequest;

class FetchAttendanceRequest extends FormRequest
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
            'staff_central_id' => 'required|exists:staff_main_mast,id',
            'attendance_date_np' => 'required',
            'punchin_datetime_np' => ['required', new FutureTime()],
            'tiffinout_datetime_np' => ['nullable', new FutureTime()],
            'tiffinin_datetime_np' => ['nullable', new FutureTime(), 'after:tiffinout_datetime_np'],
            'punchout_datetime_np' => ['nullable','after:punchin_datetime_np', new FutureTime()],
        ];
    }

    public function messages()
    {
        return [
            'staff_central_id.required' => 'Please select a staff member!',
            'staff_central_id.exists' => 'Please select an existing staff member!',
            'punchout_datetime_np.after' => 'The Punch Out time should be greater than the Punch In time',
            'tiffinin_datetime_np.after' => 'The Tiffin In time should be greater than the Tiffin Out time',
        ];
    }
}
