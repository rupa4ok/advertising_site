<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegionController extends Controller
{
    public function get(Request $request): array
    {
    	$parent = $request->get('parent') ?: null;
    	
    	return Region::where('parent_id', $parent)
		    ->orderBy('name')
		    ->select('id','name')
		    ->get()
		    ->toArray();
    }
}
