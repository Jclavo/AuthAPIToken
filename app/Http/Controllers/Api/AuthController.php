<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    //
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        
        
        $user = new User();
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        //$user->api_token = Str::random(80);
        
        $user->save();       
        
        return response()->json(['user'=> $user, 'token' => $user->api_token ]);
    }
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        
        if(!auth()->attempt($request->all()))
        {
            return response()->json(['message'=> 'Invalid credentials' ]);
        }
        
        //Auth::user()->api_token = Str::random(80);
        
        //$token = Str::random(80);
        
        /*Auth::user()->forceFill([
            'api_token' => $token
        ])->save();*/
        
        Auth::user()->api_token = Str::random(80);
        Auth::user()->save();
        
        return response()->json(['user'=> Auth::user(), 'token' => Auth::user()->api_token]);
 
    }
    
    public function details()
    {
        return response()->json(['user'=> Auth::user()]);
    }
    
    
     
    
}
