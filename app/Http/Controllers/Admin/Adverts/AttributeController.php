<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Requests\Admin\Adverts\AttributeRequest;
use App\Models\Adverts\Attribute;
use App\Models\Adverts\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class AttributeController extends Controller
{
    
    public function create(Category $category)
    {
        $types = Attribute::typesList();
        
        return view('admin.adverts.categories.attributes.create', compact('category', 'types'));
    }
    
    public function store(AttributeRequest $request, Category $category)
    {
        $attribute = $category->attributes()->create([
            'name' => $request['name'],
            'type' => $request['type'],
            'required' => (bool)$request['required'],
            'variants' => array_map('trim', preg_split('#[\r\n]+#', $request['variants'])),
            'sort' => $request['sort']
        ]);
        return redirect()->route('admin.adverts.categories.attributes.show', [$category, $attribute]);
    }
    
    public function show(Category $category, Attribute $attribute)
    {
        $types = Attribute::typesList();
        
        return view('admin.adverts.categories.attributes.edit', compact('category', 'attribute', 'types'));
    }
    
    public function edit(Category $category)
    {
        $parents = Category::defaultOrder()->withDepth()->get();
        
        return view('admin.adverts.categories.edit', compact('category', 'parents'));
    }
    
    public function update(AttributeRequest $request, Category $category, Attribute $attribute)
    {
        $category->attributes()->findOrFail($attribute->id)->update([
            'name' => $request['name'],
            'type' => $request['type'],
            'required' => (bool)$request['required'],
            'variants' => array_map('trim', preg_split('#[\r\n]+#', $request['variants'])),
            'sort' => $request['sort']
        ]);
        
        return redirect()->route('admin.adverts.categories.show', $category);
    }
    
    public function destroy(Category $category)
    {
        $category->delete();
        
        return redirect()->route('admin.adverts.categories.index');
    }
}
