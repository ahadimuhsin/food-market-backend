<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FoodRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:foods,name|max:255',
            'picturePath' => 'required|image',
            'description' => 'required',
            'ingredients' => 'required',
            'price' => 'required|integer',
            'rating' => 'required|numeric',
            'type' => 'nullable|in:recommended,popular,new_food'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama Makanan harus diisi',
            'picturePath.required' => 'Photo harus ada',
            'description.required' => 'Deskripsi harus diisi',
            'ingredients.required' => 'Bahan-bahan harus diisi',
            'price.required' => 'Harga harus diisi',
            'rating.required' => 'Rating harus diisi',
            'type.in' => 'Tipe yang harus diisi adalah Recommended, Popular, dan New Food'
        ];
    }
}
