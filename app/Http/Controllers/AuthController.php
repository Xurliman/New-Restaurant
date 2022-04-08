<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|unique:users,phone', 
        ]);
        if ($validation->fails()) {
            return ResponseController::error($validation->errors()->first(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        return ResponseController::response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if (!$user or $user->name != $request->name) {
            return ResponseController::error('Credentials do not match our records!', Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('Personal access token')->plainTextToken;

        return ResponseController::response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout()
    {
        //
    }

    public function getme(Request $request)
    {
        return $request->user();
    }
}
