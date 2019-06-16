<?php

namespace App\Http\Controllers\Adverts;

use App\Models\Adverts\Advert\Advert;
use App\Models\Adverts\Category;
use App\Models\Region;
use App\Http\Controllers\Controller;

class AdvertController extends Controller
{
    public function index(Region $region = null, Category $category = null)
    {
        $query = Advert::with(['category', 'regoin'])->orderBy('id');
        
        if ($category) {
        	$query->forCategory($category);
        }
	
	    if ($region) {
		    $query->forRegion($region);
	    }
	    
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
        return view('adverts.show', compact('advert'));
    }
}
