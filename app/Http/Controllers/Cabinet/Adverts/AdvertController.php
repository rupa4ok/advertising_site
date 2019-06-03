<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Http\Middleware\FilledProfile;
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
        return view('cabinet.adverts.index');
    }
}
