<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        return $user->load('roles');
    }

    public function index()
    {
        return User::orderBy('updated_at', 'desc')->get();
    }

    public function store()
    {
        request()->validate([
            'name' => 'required|min:10',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'telephone' => 'required|digits:10',
        ]);
        return User::create(request(['name', 'email',  'telephone', 'job']) + ['password' => bcrypt(request()->password)]);
    }

    public function update(User $user)
    {
        request()->validate([
            'name' => 'required|min:10',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'required|min:6',
            'telephone' => 'required|digits:10',
        ]);
        $user->update(request(['name', 'email',  'telephone', 'job']) + ['password' => bcrypt(request()->password)]);
        return $user->fresh();
    }
}
