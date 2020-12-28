<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditStaffRequest extends FormRequest
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
            "first_name"=>"required|min:3",
            "last_name"=>"required|min:3",
            "job"=>"required|min:3",
            "facebook"=>"required|url",
            "instagram"=>"required|url",
            "description"=>"required|min:5",
        ];
    }
}
