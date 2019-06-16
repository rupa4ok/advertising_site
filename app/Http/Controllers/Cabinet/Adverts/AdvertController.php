<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Http\Middleware\FilledProfile;
use App\Models\Adverts\Advert\Advert;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdvertController extends Controller
{
	
	public function __construct()
	{
		$this->middleware(FilledProfile::class);
	}
	
	public function index()
    {
    	$adverts = Advert::forUser(Auth::user())->orderByDesc('id')->paginate(20);
    	
        return view('cabinet.adverts.index', compact('adverts'));
    }
}
