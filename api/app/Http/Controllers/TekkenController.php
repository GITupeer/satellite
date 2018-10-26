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
            sleep(1);
            return array('status' => 'error', 'message' => 'Login lub Haslo nie moze byc puste!'); exit;
        } else if (!empty($user[0])){
            sleep(1);
            return array('status' => 'error', 'message' => 'Konto o takiej nazwie juz istnieje!'); exit;
        } else {
            $insertUser = DB::table('tekken_user')->insert(
                [
                'user' => $login,
                'password' => $pass,
                ]
            );
            sleep(1);
            return array('status' => 'success', 'message' => 'Konto zostalo stworzone!'); exit;    
        }

    }




    public function logowanie(Request $request) {
        $user = $request['user'];
        $pass = $request['pass'];

        $user = DB::table('tekken_user')->where([['user','=',$user], ['password','=',$pass]])->get();
        $user = json_decode($user, true);

        if (empty($user[0])){
            $return['status'] = 'error';
            $return['message']['logowanie'] = 'Login lub haslo jest nieprawidlowe';
        } else {
            $return['status'] = 'success';
            $return['user'] = $user[0];
        }
        sleep(1);
        return $return;


    }


    public function checkIfExsist($UID, $id){
        $user = DB::table('tekken_gracze')->where([['UID_rozgrywki','=',$UID], ['id_gracza','=',$id]])->get();
        $user = json_decode($user, true);

        if (empty($user[0])){
            return false;
        } else {
            return true;
        }

    }

    public function dolaczDoTurnieju(Request $request) {
 
        $UID = $request['UID'];

        $user = DB::table('tekken_user')->where([['id','=',$request['User']['id']]])->get();
        $user = json_decode($user, true);

        $user = $user[0];

        $checkIfExsist = $this->checkIfExsist($UID, $user['id']);
        if ($checkIfExsist == false){
            $insertGracz = DB::table('tekken_gracze')->insert(
                [
                'id_gracza' => $user['id'],
                'nazwa_gracza' => $user['user'],
                'punkty' => 0,
                'UID_rozgrywki' => $UID
                ]
            );                 
        }

    }




    public function getTurnieje() {
        $tekken_turnieje = DB::table('tekken_turnieje')->orderBy('id', 'desc')->get();
        $tekken_turnieje = json_decode($tekken_turnieje, true); 
        
        return $tekken_turnieje;
    }


    public function stworzTurniej(Request $request) {

        // Request $request
        $user = $request['user'];
        $pass = $request['pass'];
        $nazwa = $request['nazwa'];
        $ban = $request['ban'];
        $user = DB::table('tekken_user')->where([['user','=',$user], ['password','=',$pass]])->get();
        $user = json_decode($user, true);
        $return['status'] = 'success';
        $return['message'] = array();

        if (empty($user[0])){
            $return['status'] = 'error';
            $return['message']['logowanie'] = 'Login lub haslo jest nieprawidlowe';

        } 

        if (empty($nazwa) || $nazwa == ''){
            $return['status'] = 'error';
            $return['message']['nazwa'] = 'Nazwa Turnieju nie moze byc pusta';
        }

        if(!is_numeric($ban)){
            $return['status'] = 'error';
            $return['message']['ban'] = 'Nieprawidlowy format danych';
        } else {
            if ($ban > 10 || $ban < 0){
                $return['status'] = 'error';
                $return['message']['ban'] = 'Zakres 0 - 10';               
            }
        }


        if ($return['status'] == 'success'){
            $UID = uniqid().'-'.uniqid().'-'.uniqid().'-'.uniqid().'-'.uniqid();
            $insertTurniej = DB::table('tekken_turnieje')->insert(
                [
                'admin' => $user[0]['id'],
                'nazwa' => $nazwa,
                'ban' => $ban,
                'uid' => $UID
                ]
            );    

            $insertGracz = DB::table('tekken_gracze')->insert(
                [
                'id_gracza' => $user[0]['id'],
                'nazwa_gracza' => $user[0]['user'],
                'punkty' => 0,
                'UID_rozgrywki' => $UID
                ]
            );         
            
            
            $return['UID'] = $UID;
            $return['user'] = $user[0];

        }

        sleep(1);
        return $return;


    }



    public function turniejInfo($UID) {

        $turniej = DB::table('tekken_turnieje')->where([['UID','=',$UID]])->get();
        $turniej = json_decode($turniej, true);
        $gracze = DB::table('tekken_gracze')->where([['UID_rozgrywki','=',$UID]])->get();
        $gracze = json_decode($gracze, true);

        $return = array(
            'turniej' => $turniej[0],
            'gracze' => $gracze,
        );

        return $return;

    }


    public function zaktualizujStatus($UID, $status){
        $updateStatus = DB::table('tekken_turnieje')->where([['UID','=',$UID]])->update(['status' => $status]);
    }


    public function tworzenieRozgrywki($UID) {
        $this->zaktualizujStatus($UID, 'rozpoczynanie');
        sleep(1);

        $this->zaktualizujStatus($UID, 'tworzenie');
        sleep(1);       

        
    }


}