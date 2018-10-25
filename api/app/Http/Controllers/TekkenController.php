<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Response;

class TekkenController extends BaseController
{

    public function registerAccount($login, $pass) {

        if (empty($user) || empty($pass)){
            return array('status' => 'error', 'message' => 'Login lub Hasło nie może być puste!'); exit;
        }

        $user = DB::table('tekken_user')->where([['user','=',$login]])->get();
        $user = json_decode($user, true);

        if (!empty($user[1])){
            return array('status' => 'error', 'message' => 'Konto o takiej nazwie już istnieje!'); exit;
        } else {
            $insertUser = DB::table('tekken_user')->insert(
                [
                'login' => $login,
                'password' => $pass,
                ]
            );

            return array('status' => 'success', 'message' => 'Konto zostało stworzone!'); exit;    
        }

    }



}