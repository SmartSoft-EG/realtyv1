<?php

namespace App\Http\Controllers;

use App\Models\User as ModelsUser;
use App\Notifications\Note;
use App\Models\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{

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
    public function login(Request $request)
    {
        //return request();

        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        if (!Auth::attempt($attr)) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }
        return response()->json(['status' => 'success'], 200)->header('Authorization', auth()->user()->createToken('API Token')->plainTextToken);
    }


    public function logout()
    {

        //  auth()->logout();

        //  return response()->json(['message' => 'Successfully logged out']);
        Auth::user()->tokens()->delete();
        Auth::guard('web')->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.',
        ], 200);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());


        // return response()->json(['status' => 'success'], 200)->header('Authorization', (string) Str::uuid());
    }


    public function user()
    {
        return response()->json([
            'status' => 'success',
            'data' => Auth::user()->only('id', 'name', 'permissions', 'telephone') //->append(['permissions'])
        ]);
    }






    public function resetPassword(Request $request)
    {
        // $user = User::where('reset', $request->code)->first();
        // if ($user == null) {
        //     return 'invalid link';
        // }
        // $user->update(['password' => bcrypt($request->new_password)]);
        // return ('password changed you can login with new password');
    }

    public function account_activation(Request $request)
    {
        // if (Auth::user()->r_password === $request->serial) {
        //     Auth::user()->account_state = 'active';
        //     Auth::user()->save();
        //     return 'account activated';
        // } else {
        //     return response()->json(['error' => 'incorrect serial'], 404);
        // }
    }
}
