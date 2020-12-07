<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // Login method
    public function login()
    {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
           $user = Auth::user();
           $success['token'] = $user->createToken('appToken')->accessToken; 
           // Apres une authentification reussi, renvoie des parametres JSON
           return response()->json([
               'success' => true,
               'token' => $success,
               'user' => $user
           ]);
        }
        else {
            // Si l'authentification echoue, renvoie des parametres JSON
            return reponse()->json([
                'success' => false,
                'message' => 'Email ou mot de passe invalide'
            ], 401);
        }
    }
    
    // Register method
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required',
            'email' => 'required|unique:users|regex:/(0)[0-9]{10}/',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return reponse()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('appToken')->accessToken;
        return response()->json([
            'success' => true,
            'token' => $success,
            'user' => $user
        ]);
    }

    // Logout methode
    public function logout(Request $res){
        if(Auth::user()){
            $user = Auth::user()->token();
            $user->revoke;

            return response()->json([
                'success' => true,
                'message' => 'Logout successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unable to Logout'
            ]);
        }
    }
}
