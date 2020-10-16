<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

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

    public function getUsers(){
        $users = User::all();
        return response()->json([
           'status' => true,
           'data' => $users
        ],200);
    } 

    public function getUser($name){

       $user = DB::table('users')->where('name', $name)->first();
       if(!is_null($user)){
            return response()->json([
                'status' => true,
                'data' => $user
            ],200);
       }else{
            return response()->json([
                'status' => false,
                'data' => 'El usuario con el nombre '. $name . ' no existe en los registros'
            ],400);
       }
    }

    public function updateUser(Request $request,   $id){
       $credential = $request->only('name','email','password','roll');
       $user = User::find($id);
       if(!$user){
           return response()->json([
               'status' => false,
               'data' => 'Este usuario no existe en los registros'
           ], 400);
       }
       $validate = Validator::make($credential ,[
          'name' => 'min:3|max:15',
          'email' => 'email|unique:users',
          'password' => 'min:8|max:15',
          'roll' => 'required'
       ]);
       if(!$validate->fails()){
           $updateuser = $user->fill($request->all())->save();
           if($updateuser){
                return response()->json([
                    'status' => true,
                    'data' => 'El usuario se ha actualizado'
                ], 200);
           }else{
                return response()->json([
                    'status' => false,
                    'data' => 'No se ha podido actualizar el usuario'
                ], 500);
           }
       }else{
           return response()->json([
               'status' => false,
               'error' => $validate->errors()
           ]);
       }
    }

    public function deleteUser($id){
       $user = User::find($id);
       if($user){
           if($user->delete()){
               return response()->json([
                 'status' => true,
                 'data' => 'El usuario se ha eliminado'
              ],200);
           }else{
                return response()->json([
                    'status' => false,
                    'data' => 'No se ha podido eliminar el usuario'
                ],500);
           }
       }else{
            return response()->json([
                'status' => false,
                'data' => 'Este usuario no existe en los registros'
            ],400);
       }
    }
}
