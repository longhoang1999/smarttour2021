<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AuthApiController extends Controller
{
    public function login(Request $req)
    {
        $fields = $req->validate([
            'email'=>'required|email',
            'password'=>'required|min:0|max:32',
        ]);
        if($fields['email'] != '' && $fields['password'] != ''){
            $user = User::where('us_email',$fields['email'])->first();
            if($user && Hash::check($fields['password'], $user->us_password)){         
                $token = $user->createToken('myapptoken')->plainTextToken;
                $infoUser = array();
                array_push($infoUser, $user->us_id, $user->us_email);
                $response = [
                    'result' => 'ok',
                    'userLogin' => $infoUser,
                    'token' => $token
                ];
                return response($response,200);
            }else{
                $toEncode["Error"] = "Username or password is incorrected!";            
                $toEncode["result"] = "fail";
                return response($toEncode,401);
            }
        }else{
            $toEncode["Error"] = "Wrong credentials!";      
            $toEncode["result"] = "fail";
            return response($toEncode,400); 
        }       
    }
    public function logout(Request $req) {
        Auth()->user()->tokens()->delete();
        return response([
            'mes' => 'Logged out'
        ],200);
    }
}
