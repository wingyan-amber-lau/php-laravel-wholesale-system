<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserAuthController extends Controller
{

    public function register(Request $request)
    {

        Log::debug($request->input());
        // $data = $request->validate([
        //     'name' => 'required|max:255',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|confirmed'
        // ]);

        $data = $request->input();

        Log::debug($data);

        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        $token = $user->createToken('API Token')->accessToken;

        return response([ 'user' => $user, 'token' => $token]);
    }

    public function login(Request $request)
    {
        // $data = $request->validate([
        //     'email' => 'email|required',
        //     'password' => 'required'
        // ]);

        $data = $request->input();

        if (!auth()->attempt($data)) {
            return response(['error_message' => 'Incorrect Details. 
            Please try again']);
        }

        $token = auth()->user()->createToken('API Token')->accessToken;

        return response(['user' => auth()->user(), 'token' => $token]);

    }
}
