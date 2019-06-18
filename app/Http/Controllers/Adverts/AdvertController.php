<?php

namespace App\Http\Controllers\Adverts;

use App\Http\Router\AdvertsPath;
use App\Models\Adverts\Advert\Advert;
use App\Models\Adverts\Category;
use App\Models\Region;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdvertController extends Controller
{
    public function index(AdvertsPath $path)
    {

	    $region = $path->region;
	    $category = $path->category;
        $query = Advert::active()->with(['category', 'regoin'])->orderBy('id');
	    
	    $regions = $region
		    ? $region->children()->orderBy('name')->getModel()
		    : Region::roots()->orderBy('name')->getModel();
	    
	    $categories = $category
		    ? $category->children()->defaultOrder()->getModels()
	        : Category::whereIsRoot()->defaultOrder()->getModels();
        
        $adverts = $query->paginate(20);
        
        return view('adverts.index', compact('category', 'region', 'regions', 'categories', 'adverts'));
    }
    
    public function show(Advert $advert)
    {
    	if (!($advert->isActive() || Gate::allows('show-advert', $advert))) {
    		abort(403);
	    }
	    $user = Auth::user();
    	
	    return view('adverts.show', compact('advert', 'user'));
    }
	
	public function phone(Advert $advert)
	{
		if (!($advert->isActive() || Gate::allows('show-advert', $advert))) {
			abort(403);
		}
		
		return $advert->user->phone;
	}
}
