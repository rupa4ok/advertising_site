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
    
    public function create()
    {
	    return view('cabinet.adverts.create');
    }
    
    public function edit($id)
    {
	    return view('cabinet.adverts.edit');
    }
    
    public function update(Request $request, $id)
    {
        //
    }
    
    public function destroy($id)
    {
        //
    }
}
