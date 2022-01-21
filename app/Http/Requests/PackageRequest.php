<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = auth()->user();
        return $user->can('add staycation package') || $user->can('edit staycation package');
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
            'pax' => 'required|numeric|min:1|max:1000',
            'amount' => 'required|numeric|min:0',
            'days' => 'required|min:0',
            'time_in' => 'required',
            'time_out' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'pax' => str_replace(',','',$this->pax),
            'amount' => str_replace(',','',$this->amount),
        ]);
    }
}
