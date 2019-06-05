<?php

namespace App\Http\Requests\Cabinet\Advert;

use App\Models\Adverts\Category;
use App\Models\Region;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property Category $category
 * @property Region $region
 * Class CreateRequest
 * @package App\Http\Requests\Cabinet\Advert
 */
class CreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
    	$items = [];
    	
    	foreach ($this->category->allAttributes() as $attribute) {
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
    		$items['attribute.' . $attribute->id] = $rules;
	    }
    	
        return array_merge([
            'title' => 'required|string',
	        'content' => 'required|string',
	        'price' => 'required|integer',
	        'address' => 'required|string',
        ], $items);
    }
}
