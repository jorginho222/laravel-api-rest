<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRatingRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'value' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|max:500',
            'user_id' => 'required|string|min:32|max:36',
            'course_id' => 'required|string|min:32|max:36',
        ];
    }
}
