<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function postLogin(Request $request)
    {
        /**
         * Validating User Input
         */
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response([
                "message" => "Invalid field"
            ], 422);
        }
        if (!Auth::attempt($request->all())) {
            return response([
                "message" => "Unauthorized"
            ], 401);
        }

        $user = $request->user();
        $token = $user->createToken('token')->plainTextToken;
        $user->update([
            'remember_token' => $token
        ]);

        return response([
            "message" => "Login success",
            "data" => $user
        ])->withCookie(cookie('authorization', $token));
    }

    public function postLogout(Request $request)
    {
        $request->user()->tokens()->delete();
        $request->user()->update(['remember_token' => null]);
        return response([
            "message" => "Logout success"
        ])->withCookie(Cookie::forget('authorization'));
    }

    public function postRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email|unique:users",
            "password" => "required",
            "confirm_password" => "required",
            "username" => "required",
            "image" => "image|mimes:jpeg,png,jpg"
        ]);
        if ($validator->fails()) {
            return response([
                "message" => "Invalid field"
            ], 422);
        }
        if ($request->password != $request->confirm_password) {
            return response([
                "message" => "Password are not the same",
            ], 422);
        }

        $file = $request->file('image');
        $filename = uniqid() . "." . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/uploads', $filename);

        $user = User::create([
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "username" => $request->username,
            "profile" => Storage::url($path)
        ]);

        $token = $user->createToken('token')->plainTextToken;
        $user->update([
            'remember_token' => $token
        ]);

        return response([
            "message" => "Register success",
            "data" => User::find($user->id)
        ])->withCookie(cookie('authorization', $token));
    }
    public function updateMe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "username" => "required",
        ]);
        
        if ($validator->fails()) {
            return response([
                "message" => "Invalid field",
                "data"=>$validator->validated()
            ], 422);
        }

        $creds = [
            "username" => $request->username,
            "bio" => $request->bio
        ];

        if ($request->update_status == 'true') {
            $file = $request->file('image');
            $filename = uniqid() . "." . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/uploads', $filename);
            $creds['profile'] = Storage::url($path);
        }

        $request->user()->update($creds);
        return response([
            "message" => "Update profile succes"
        ]);
    }
    public function getMe(Request $request)
    {
        return response([
            "message" => "Find user success",
            "data" => $request->user()
        ]);
    }
}
