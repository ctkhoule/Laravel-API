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
    
}
