<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Http\Middleware\FilledProfile;
use App\Models\Adverts\Category;
use App\Models\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CreateController extends Controller
{
    public function __construct()
    {
    	$this->middleware(FilledProfile::class);
    }
    
    public function category()
    {
        $categories = Category::defaultOrder()->withDepth()->get()->toTree();
        
        return view('cabinet.adverts.create.category', compact('categories'));
    }
    
    public function region(Category $category, Region $region = null)
    {
        $regions = Region::where('parent_id', $region ? $region->id : null)->orderBy('name')->get();
    
        return view('cabinet.adverts.create.region', compact('category','region', 'region'));
    }
    
    public function advert(Category $category, Region $region = null)
    {
    	return view('cabinet.adverts.create.adverts', compact('category', 'region'));
    }
    
    public function store(CreateRequest $request, $category, Region $region = null)
    {
    	try {
		    $adverts = $this->service->create(
		    Auth::id(),
			$category->id,
			$region ? $region->id : null,
			$request
		    );
	    } catch (\DomainException $e) {
    	    return back()->with('error', $e->getMessage());
	    }
	    
	    return redirect()->route('adverts.show', $adverts);
    }
}
