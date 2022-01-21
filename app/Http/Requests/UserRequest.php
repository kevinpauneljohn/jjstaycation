<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = auth()->user();
        return $user->can('add user') || $user->can('edit user');
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
            'date_of_birth' => 'nullable|date',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'roles' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ];
    }
}
