<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);


        if (Auth::attempt($credentials)) {

            $user = \auth('sanctum')->user();
            Auth::login($user, true);
            $user->token = $user->createToken('authToken')->plainTextToken;;
            return $user;

            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return response()->json(["error" => true, "message" => __('Les informations d’identification fournies ne correspondent pas à nos dossiers.')],200);
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
