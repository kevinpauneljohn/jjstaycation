<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('add booking')
            || auth()->user()->can('add customer')
            || auth()->user()->can('edit booking')
            || auth()->user()->can('edit customer');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'nullable|email',
            'mobile_number' => 'required|regex:/^[+]?[\d]+([\-][\d]+)*\d$/',
            'facebook_url' => 'nullable|url',
            'preferred_date' => 'required',
            'package' => 'required',
            'total_amount' => 'required|numeric|min:0',
            'pax' => 'required:numeric:min:0',
            'status' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'total_amount' => str_replace(',','',$this->total_amount),
        ]);
    }
}
