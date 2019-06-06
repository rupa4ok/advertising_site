<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RejectRequest extends FormRequest
{
    public function authorize()
    {
        return true();
    }
    
    public function rules(): array
    {
        return [
            'reason' => 'required|string'
        ];
    }
}
