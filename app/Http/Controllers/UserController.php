<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function login(Request $request){
        $credential = $request->only('email','password');
        $validate = Validator::make($credential, [
            'email' => 'required|email',
            'password' => 'required|min:8|max:15'
        ]);

        if(!$validate->fails()){
            try {
                if($token = JWTAuth::attempt($credential)){
                    return response()->json([
                       'state' => true,
                       'token' => compact('token')
                    ],200);
                }else{
                    return response()->json([
                        'state' => false,
                        'token' => 'El correo o la contraseñan no coinciden'
                    ],400);
                }
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json([
                    'state' => false,
                    'token' => $e->getMessage()
                ],400);
            }
            
        }else{
            return response()->json([
                'state' => false,
                 'data' => $validate->errors()
             ], 400);
        }
    }

    public function register(Request $request){
       $credential = $request->only('name','email','password','roll' );
        $validate = Validator::make($credential, [
            'name' => 'required|min:3|max:15',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:15',
            'roll' => 'required'
        ]);

        if(!$validate->fails()){

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->roll = $request->roll;
            $user->save();

            if($token = JWTAuth::attempt($credential)){
                return response()->json([
                   'state' => true,
                   'data' => 'Usuario Creado Satisfatoriamente',
                   'token' => compact('token')
                ],200);
            }else{
                return response()->json([
                    'state' => false,
                    'data' => 'No se ha podido guardar el usuario',
                 ],500);
            }
        }else{
            return response()->json([
                'state' => false,
                'error' => $validate->errors()
             ],400);
        } 
      

    }
}
