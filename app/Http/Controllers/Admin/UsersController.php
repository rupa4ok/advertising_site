<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(20);
        
        return view('admin.users.home', compact('users'));
    }
    
    public function create()
    {
        return view('admin.users.create');
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
           'name' => 'required', 'string', 'max:255',
           'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
        ]);
        
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email']
        ]);
        
        return redirect()->route('admin.users.show', ['id' => $user->id]);
    }
    
    public function show(User $user)
    {
        return view('admin.users.show', compact($user));
    }
    
    public function edit($id)
    {
        //
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
