<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Silber\Bouncer\BouncerFacade as Bouncer;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Bouncer::is($this->user())->notAn('instructor')) {
            abort(403, 'Only instructors can manage the courses');
        }

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
            'name' => 'required|max:60',
            'description' => 'required|max:255',
            'max_students' => 'required|min:1',
            'price' => 'required|numeric|min:0|max:10000',
            'area_id' => 'required|string|min:32|max:36',
            'modality' => 'required|string|max:30',
            'init_date' => 'required|date'
        ];
    }
}
