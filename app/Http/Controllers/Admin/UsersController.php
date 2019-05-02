<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function __construct()
    {
    
    }
    
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }
    
    public function create()
    {
        return view('admin.users.create');
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);
        
        $user = User::create([
            'name' => $request[ 'name' ],
            'email' => $request[ 'email' ],
            'status' => User::STATUS_ACTIVE,
            'password' => Hash::make('password')
        ]);
        
        return redirect()->route('admin.users.show', $user);
    }
    
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }
    
    public function edit(User $user)
    {
        $statuses = [
            User::STATUS_ACTIVE => 'Active',
            User::STATUS_WAIT => 'Waiting'
        ];
    
        $roles = [
            User::ROLE_ADMIN => 'Admin',
            User::ROLE_USER => 'User'
        ];
        
        return view('admin.users.edit', compact('user', 'statuses', 'roles'));
    }
    
    public function update(Request $request, User $user)
    {
        $data = $this->validate($request, [
            'name' => 'required', 'string', 'max:255',
            'email' => 'required', 'string', 'email', 'max:255', 'unique:users',
            'status' => 'required', 'string', Rule::in(User::STATUS_WAIT, User::STATUS_ACTIVE),
            'role' => 'required', 'string', Rule::in(User::ROLE_USER, User::ROLE_ADMIN)
        ]);
        //TODO сделать редактирование статуса для юзера
        $user->update($data);
        
        return redirect()->route('admin.users.show', $user);
    }
    
    public function destroy(User $user)
    {
        $user->delete();
    
        return redirect()->route('admin.users.index', $user);
    }
}
