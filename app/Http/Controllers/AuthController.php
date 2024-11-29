<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
       $validator = Validator::make($request->all(),[
            "name"=>"required|string|max:100",
            "email"=>"required|email|unique:users,email",
            "password"=>"required|string|min:6|confirmed"
        ]);

        if($validator->fails())
        {
            return response()->json([
                "msg"=>$validator->errors()
            ],409);
        }

        $newpassword = bcrypt($request->password);
        $access_token = Str::random(65);

        User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>$newpassword,
            "access_token"=>$access_token
        ]);

        return response()->json([
            "msg"=>"register created successfuly"
        ],201);
    }

    public function login(Request $request)
    {
       $validator = Validator::make($request->all(),[
            "email"=>"required|email",
            "password"=>"required|string|min:6"
        ]);

        if($validator->fails())
        {
            return response()->json([
                "msg"=>$validator->errors()
            ],409);
        }

        $user = User::where("email","=",$request->email)->first();

        if($user != null)
        {
            $passwordcheck = Hash::check($request->password,$user->password);

          if($passwordcheck)
          {

            $access_token = Str::random(64);

            $user->update([
                "access-_token"=>$access_token
            ]);

            return response()->json([
                "msg"=>"welcome back",
                "access-_token"=>$access_token
            ],200);
          }
          else
          {
            return response()->json([
                "msg"=>"creadintial not correct"
            ],404);
          }
        }
        else
        {
            return response()->json([
                "msg"=>"creadintial not correct"
            ],404);
        }
    }

    public function logout(Request $request )
    {
        $access_token = $request->header("access_token");

        if($access_token !=null)
        {
           $user = User::where("access_token","=",$access_token)->first();
            if($user !=null)
            {
                return response()->json([
                    "msg"=>"logged out successfully"
                ]);
            }
            else
            {
                return response()->json([
                    "msg"=>"access token not correct"
                ],404);
            }
        }
        else
        {
            return response()->json([
                "msg"=>"access token not found"
            ],404);
        }
    }

}
