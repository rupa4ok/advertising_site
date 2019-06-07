<?php

namespace App\Http\Requests\Cabinet\Advert;

use Illuminate\Validation\Rule;
use App\Models\Adverts\Category;
use App\Models\Adverts\Advert\Advert;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property Category $category
 * @property Advert $advert
 */
class AttributesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        $items = [];

        foreach ($this->advert->category->allAttributes() as $attribute) {
            $rules = [
                $attribute->required ? 'required' : 'nullable',
            ];
            if ($attribute->isInteger()) {
                $rules[] = 'integer';
            } elseif ($attribute->isFloat()) {
                $rules[] = 'numeric';
            } else {
                $rules[] = 'string';
                $rules[] = 'max:255';
            }
            if ($attribute->isSelect()) {
                $rules[] = Rule::in($attribute->variants);
            }
            $items['attribute.'.$attribute->id] = $rules;
        }

        return $items;
    }
}
