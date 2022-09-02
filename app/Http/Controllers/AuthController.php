<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JsonException;

class AuthController extends Controller
{
    public function getLogin()
    {
        if (auth()->check()) {
            return redirect()->route('getDashboard');
        }
        return view('auth.login');
    }

    /**
     * @throws JsonException
     */
    public function postLogin(Request $request)
    {
        $user = User::where('username', $request['username'])->first();
        if (Hash::check($request['password'], $user['password'])) {
            auth()->login($user);
            return json_encode([
                'error' => false,
                'msg' => 'Login successful',
            ]);
        }
        return json_encode([
            'error' => true,
            'msg' => 'Invalid Username/Password',
        ]);
    }

    public function postLogout()
    {
        auth()->logout();
        return redirect()->route('getLogin');
    }
}
