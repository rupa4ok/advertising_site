<?php

namespace App\Http\Controllers\Admin;

use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegionController extends Controller
{
    public function index(Request $request)
    {
        $regions = Region::orderBy('name')->paginate(20);
        
        return view('admin.regions.index', compact('regions'));
    }
    
    public function create()
    {
        return view('admin.regions.create');
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:regions,slug,NULL,id,parent_id' . ($request['parent'] ?: 'NULL'),
            'slug' => 'required|string|max:255|unique:regions,slug,NULL,id,parent_id' . ($request['parent'] ?: 'NULL'),
            'parent' => 'optional|exist:regions,id'
        ]);
        
        $user = Region::create([
            'name' => $request[ 'name' ],
            'slug' => $request[ 'slug' ],
            'parent_id' => $request[ 'parent' ]
        ]);
        
        return redirect()->route('admin.region.show', $user);
    }
    
    public function show(Region $region)
    {
        $regions = Region::where('parent_id', $region->id)->orderBy('name')->get();
        
        return view('admin.users.show', compact('region', 'regions'));
    }
    
    public function edit(Region $region)
    {
        return view('admin.regions.edit', compact('region'));
    }
    
    public function update(Request $request, Region $region)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:regions,name' . $region->id . ',id,parent_id' . $region->parent_id,
            'slug' => 'required|string|max:255|unique:regions,name' . $region->id . ',id,parent_id' . $region->parent_id,
        ]);
        
        $region->update([
            'name' => $request['name'],
            'slug' => $request['slug']
        ]);
        
        return redirect()->route('admin.regions.show', $region);
    }
    
    public function destroy(Region $region)
    {
        $region->delete();
    
        return redirect()->route('admin.regions.index', $region);
    }
}
