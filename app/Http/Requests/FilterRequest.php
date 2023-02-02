<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
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
            'area_id' => 'required|string|min:32|max:36',
            'minPrice' => 'required|numeric|min:1|max:100000',
            'maxPrice' => 'required|numeric|min:1|max:100000',
        ];
    }
}
