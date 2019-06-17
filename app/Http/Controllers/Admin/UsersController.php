<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Adverts\AdvertFilter;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
	use AdvertFilter;
	
	public function index(Request $request)
	{
		$query = User::orderByDesc('id');
		
		$this->filter($request, $query);
		
		$users = $query->paginate(20);
		$statuses = [
			User::STATUS_WAIT => 'Waiting',
			User::STATUS_ACTIVE => 'Active',
		];
		$roles = User::rolesList();
		return view('admin.users.index', compact('users', 'statuses', 'roles'));
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
    
        $roles = User::rolesList();
        
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
