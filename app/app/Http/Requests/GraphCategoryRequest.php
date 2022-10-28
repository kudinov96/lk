<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GraphCategoryRequest extends FormRequest
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
            "title"        => "required",
            "color_title"  => "required",
            "color_border" => "required",
        ];
    }
}
