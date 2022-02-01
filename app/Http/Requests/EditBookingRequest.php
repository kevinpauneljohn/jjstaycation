<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('edit booking');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
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
