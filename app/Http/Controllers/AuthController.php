<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Purifier;
use Hash;
use Response;
use App\User;
use JWTAuth;
use File;

class AuthController extends Controller
{


    public function index()
      {
        return File::get('index.html');
      }



  public function signUp(Request $request)
  {
    $rules=[
      "email"=>"required",
      "username"=>"required",
      "password"=>"required",
    ];
    $validator=Validator::make(Purifier::clean($request->all()),$rules);
    if($validator->fails())
    {
      return Response::json(["error"=>"You need all fields."]);
    }

    $check = User::where("email","=",$request->input("email"))->orWhere("name","=",$request->input("username"))->first();
    if(!empty($check))
    {
        return Response::json(["error"=>"User already exists."]);
    }


    $user = new User;
    $user->name = $request->input("username");
    $user->password = Hash::make($request->input("password"));
    $user->email = $request->input("email");
    $user->save();
    return Response::json(["success"=>"Thankyou for signing up."]);

  }

  public function signIn(Request $request)
  {
    $rules=[
      "email"=>"required",
      "password"=>"required",
    ];
    $validator = Validator::make(Purifier::clean($request->all()),$rules);
    if($validator->fails())
    {
      return Response::json(["error"=>"Your inputs were either invalid or do not exist."]);
    }

    $email = $request->input("email");
    $password = $request->input("password");

    $cred = compact("email","password",["email","password"]);

    $token = JWTAuth::attempt($cred);
    return Response::json(compact("token"));


  }



}
