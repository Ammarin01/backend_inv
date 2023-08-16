<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        //Validate
        $fields = $request->validate([
            'fullname' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|string|unique:Users,email',
            'password' => 'required|string|confirmed',
            'tel' => 'required|',
            'role' => 'required|integer',
        ]);

        //Create user
        $user = User::create([
            'fullname' => $fields['fullname'],
            'username' => $fields['username'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'tel' => $fields['tel'],
            'role' => $fields['role'],
        ]);

        //Create token
        $token = $user->createToken($request->userAgent(), ["$user->role"])->plainTextToken;
        
        $response = [
            'user' => $user,
            'token' => $token,
        ];
        return response($response, 201);
    }

    //login
    public function login(Request $request)
    {

        //Validate
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            ]);

        //Check email
        $user = User::where('email', $fields['email'])->first();

        //Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            # code...
            return response([
                'message'=>'login failed'
            ], 401); 
        }else{
            //delete token เก่าออกแล้วค่อยสร้างใหม่
            $user->tokens()->delete();

                    //Create token
                    $token = $user->createToken($request->userAgent(), ["$user->role"])->plainTextToken;
            
                    $response = [
                        'user' => $user,
                        'token' => $token,
                    ];
                    return response($response, 201);
        }
    }

    //logout
    public function logout(Request $request){
        
            auth()->user()->tokens()->delete();     //auth()->user() ยูสเซอร์ที่กำลัง login
            return  [
                'message'=>'logged out'
            ] ;
    }

}
