<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Response;

class TekkenController extends BaseController
{

    public function registerAccount($login, $pass) {
        header("Access-Control-Allow-Origin: *");
        
        $user = DB::table('tekken_user')->where([['user','=',$login]])->get();
        $user = json_decode($user, true);

        if (empty($login) || empty($pass)){
            return array('status' => 'error', 'message' => 'Login lub Haslo nie moze byc puste!'); exit;
        } else if (!empty($user[0])){
            return array('status' => 'error', 'message' => 'Konto o takiej nazwie juz istnieje!'); exit;
        } else {
            $insertUser = DB::table('tekken_user')->insert(
                [
                'user' => $login,
                'password' => $pass,
                ]
            );

            return array('status' => 'success', 'message' => 'Konto zostalo stworzone!'); exit;    
        }

    }



    public function stworzTurniej() {
        // Request $request
        $user = 'upeer';
        $pass = 'pass';
        $user = DB::table('tekken_user')->where([['user','=',$user], ['password','=',$pass]])->get();
        $user = json_decode($user, true);


        if (empty($user[0])){
            return array('status' => 'error', 'message' => array( 'logowanie' => 'Login lub haslo jest nieprawidlowe')); exit;
        }


    }




}