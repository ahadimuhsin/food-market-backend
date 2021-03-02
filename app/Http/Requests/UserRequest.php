<?php

namespace App\Http\Requests;

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    use PasswordValidationRules;
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
            //
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => $this->passwordRules(),
            'role' => 'string|nullable|in:USER,ADMIN',
            'picture_path' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2048',
            'profile_photo_path' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2048',
            'address' => 'required',
            'houseNumber' => 'nullable',
            'contact' => 'nullable',
            'city' => 'nullable'
        ];
    }
}
