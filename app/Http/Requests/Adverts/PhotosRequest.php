<?php

namespace App\Http\Requests\Adverts;

use Illuminate\Foundation\Http\FormRequest;

class PhotosRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'files.*' => 'required|image|mimes:jpg,jpeg,png'
        ];
    }
}
