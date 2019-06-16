<?php

namespace App\Http\Controllers\Adverts;

use App\Models\Adverts\Advert\Advert;
use App\Models\Adverts\Category;
use App\Models\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdvertController extends Controller
{
    public function index(Region $region = null, Category $category = null)
    {
        $query = Advert::with(['category', 'regoin'])->orderBy('id');
        
        $adverts = $query->paginate(20);
        
        return view('adverts.index', compact('category', 'region', 'adverts'));
    }
    
    public function show(Advert $advert)
    {
        return view('adverts.show', compact('advert'));
    }
}
