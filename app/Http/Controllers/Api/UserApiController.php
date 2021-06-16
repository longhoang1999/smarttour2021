<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserApiController extends Controller
{
    public function resetToken($idUser) {
        if($idUser != "")
        {
            $user = User::where('us_id',$idUser)->first();
            Auth()->user()->tokens()->delete();
            $token = $user->createToken('myapptoken')->plainTextToken;
            return $token;
        }
    }

    public function getAllUser(Request $req)
    {   
        $token = $this->resetToken($req->idUser);
        $user = User::all();
        return response([
            'user' => $user,
            'token' => $token,
        ],200);
    }
    public function getUser($idUser)
    {
        $user = User::where("us_id",$idUser)->first();
        return response($user,200);
    }
    public function createUser(Request $req)
    {
        return response($req,200);
    }

    public function updateUser($idUser, Request $req)
    {
        $req['idUser'] = $idUser;
        return response($req,200);
    }
    public function deleteUser($idUser, Request $req)
    {
        $req['idUser'] = $idUser;
        return response($req,200);
    }
    
}
