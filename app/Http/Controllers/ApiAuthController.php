<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {

        try
        {
            $response = Http::post(
                config('services.passport.login_endpoint'),
                [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->email,
                    'password' => $request->password,
                    'scope' => '',
                ]
            );
            return $response->json();
        }
        catch (HttpException $e)
        {
            if ($e->getCode() === 400)
            {
                return response()->json('Invalid request. Please enter a username or a password', $e->getCode());
            }
            else if ($e->getCode() === 401)
            {
                return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
            }
            return response()->json('Somthing went wrong on the server', $e->getCode());
        }
    }


    public function register(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:255',
        ]);

        return User::create([
            'user_name' => $request->user_name,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }

    public function logout()
    {
        auth()->user()->token()->revoke();

        return response()->json(['messages' => 'Logged out successfully !😁']);
    }
}
