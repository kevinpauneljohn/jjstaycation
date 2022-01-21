<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StayCationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('add staycation') || auth()->user()->can('edit staycation');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'address_number' => 'required',
            'region' => 'required',
            'province' => 'required',
            'city' => 'required'
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'address_number.required' => 'This field is required'
        ];
    }
}
