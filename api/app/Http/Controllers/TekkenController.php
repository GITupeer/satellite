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
        $odpada = $request['odpada'];
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

        if(!is_numeric($odpada)){
            $return['status'] = 'error';
            $return['message']['odpada'] = 'Nieprawidlowy format danych';
        } else {
            if ($odpada > 3 || $odpada < 1){
                $return['status'] = 'error';
                $return['message']['odpada'] = 'Zakres 1 - 3';               
            }
        }        


        if ($return['status'] == 'success'){
            $UID = uniqid().'-'.uniqid().'-'.uniqid().'-'.uniqid().'-'.uniqid();
            $insertTurniej = DB::table('tekken_turnieje')->insert(
                [
                'admin' => $user[0]['id'],
                'nazwa' => $nazwa,
                'ban' => $ban,
                'uid' => $UID,
                'odpada' => $odpada
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
        sleep(3);

        $gracze = DB::table('tekken_gracze')->inRandomOrder()->where([['UID_rozgrywki','=',$UID]])->get();
        $gracze = json_decode($gracze, true);

        

        $lista = array();
        $i=0;
        foreach($gracze as $user){
            if ($i != 0){
                $lista[$i-1]['gracz_2'] = $gracze[$i]['id_gracza'];
                $lista[$i-1]['gracz_2_name'] = $gracze[$i]['nazwa_gracza'];
            }
            $lista[$i]['gracz_1'] = $gracze[$i]['id_gracza'];
            $lista[$i]['gracz_1_name'] = $gracze[$i]['nazwa_gracza'];
            $i++;
        }
        $lista[$i-1]['gracz_2'] = $gracze[0]['id_gracza'];
        $lista[$i-1]['gracz_2_name'] = $gracze[0]['nazwa_gracza'];

        foreach($lista as $userData){
            $insertGracz = DB::table('tekken_rozgrywka')->insert(
                [
                'UID_rozgrywki' => $UID,
                'gracz_1' => $userData['gracz_1'],
                'gracz_2' => $userData['gracz_2'],
                'tura' => 1,
                'gracz_1_nazwa' => $userData['gracz_1_name'],
                'gracz_2_nazwa' => $userData['gracz_2_name'],               
                ]
            );    
        }


        $tekken_turnieje = DB::table('tekken_turnieje')->where([['UID','=',$UID]])->get();
        $tekken_turnieje = json_decode($tekken_turnieje, true); 
        $turniej = $tekken_turnieje[0];


        $odpada = $turniej['odpada'];
        $graczeCount = count($gracze);
        $tmpCounter = $graczeCount;
        $tura = 1;
        $flag = true;
        while($flag == true) {
            $tura++;
            $tmpCounter = $tmpCounter - $odpada;
            if($tmpCounter > 1){
                if ($tmpCounter == 2){
                    $tmpCounter = $tmpCounter -2;
                }
                for($j=0; $j<$tmpCounter; $j++){
                    $insertGracz = DB::table('tekken_rozgrywka')->insert(
                        [
                        'UID_rozgrywki' => $UID,
                        'gracz_1' => null,
                        'gracz_2' => null,
                        'tura' => $tura,
                        'gracz_1_nazwa' => null,
                        'gracz_2_nazwa' => null,               
                        ]
                    );    
                }

           } else {
                    
                        $insertGracz = DB::table('tekken_rozgrywka')->insert(
                            [
                            'UID_rozgrywki' => $UID,
                            'gracz_1' => null,
                            'gracz_2' => null,
                            'tura' => 'Final',
                            'gracz_1_nazwa' => null,
                            'gracz_2_nazwa' => null,               
                            ]
                        );     
                    
                    
               
             
                $flag = false;
           }

        }


        $this->zaktualizujStatus($UID, 'tworzenie');
        sleep(3);       

 
        $this->zaktualizujStatus($UID, 'trwa');

        
    }




    public function getInforRogrywka($UID) {

        $rozgrywka = DB::table('tekken_rozgrywka')->where([['UID_rozgrywki','=',$UID]])->orderBy('tura', 'ASC')->get();
        $rozgrywka = json_decode($rozgrywka, true); 

        $trwa = DB::table('tekken_rozgrywka')->where([['UID_rozgrywki','=',$UID], ['status','=','trwa']])->orderBy('tura', 'ASC')->limit(1)->get();
        $trwa = json_decode($trwa, true); 

        if (empty($trwa[0])){
            $oczekiwanie = DB::table('tekken_rozgrywka')->where([['UID_rozgrywki','=',$UID], ['status','=','oczekiwanie']])->orderBy('tura', 'ASC')->limit(1)->get();
            $oczekiwanie = json_decode($oczekiwanie, true); 


            if (empty($oczekiwanie[0])){ 
                $Final = DB::table('tekken_rozgrywka')->where([['UID_rozgrywki','=',$UID], ['tura','=','Final']])->orderBy('tura', 'ASC')->limit(1)->get();
                $Final = json_decode($Final, true); 

                $aktualna = (array) $Final[0];

            } else {
                $aktualna = (array) $oczekiwanie[0];
            }


        } else {
            $aktualna = (array) $trwa[0];
        }

        $return = array(
            'rozgrywka' => $rozgrywka,
            'aktualna' => (array) $aktualna
        );

        return  $return;

    }


    public function postacie() {
        $postacie = DB::table('tekken_postacie')->get();
        $postacie = json_decode($postacie, true); 

        return  $postacie;        
    }


    public function stan_gry($id, $stan) {


        if ($stan == 'trwa'){
            $updateStatus = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->update(['status' => 'trwa']);
            $updateStatus = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->update(['wynik_gracz_1' => 0]);
            $updateStatus = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->update(['wynik_gracz_2' => 0]);
            sleep(4);
        }

        $updateStatus = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->update(['stan_gry' => $stan]);

        $tekken_rozgrywka = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->get();
        $tekken_rozgrywka = json_decode($tekken_rozgrywka, true);       
        
        $turniej = DB::table('tekken_turnieje')->where([['UID','=',$tekken_rozgrywka[0]['UID_rozgrywki']]])->get();
        $turniej = json_decode($turniej, true);         

        return $turniej[0];
    }


    public function getInfoRozgrywka($id) {
        $tekken_rozgrywka = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->get();
        $tekken_rozgrywka = json_decode($tekken_rozgrywka, true);       

        return $tekken_rozgrywka[0];       
    }

    public function updateBanPostaci($id, $json, $gracz) {
        $ban = 'gracz_'.$gracz.'_ban';
        $updateBad = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->update([$ban => $json]);
    }

    public function updateWybranaPostac($id, $postacID, $postacNazwa, $gracz) {
        $graczTablePostacID = 'gracz_'.$gracz.'_postac';
        $graczTablePostacNazwa = 'gracz_'.$gracz.'_postac_nazwa';

        $updatePostac = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->update([$graczTablePostacID => $postacID]);
        $updatePostac = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->update([$graczTablePostacNazwa => $postacNazwa]);


    }


    public function noweRozdanie(){
        // gracz_1 != '' ORDER BY `stan_gry` DESC
        $UID = '5bd4aa0342f2f-5bd4aa0342f99-5bd4aa0342fe4-5bd4aa0343024-5bd4aa0343061';

        $rozgrywki = DB::table('tekken_rozgrywka')->where([['UID_rozgrywki','=',$UID], ['status','=','oczekiwanie'], ['gracz_1','!=','NULL']])->orderBy('id', 'ASC')->get();
        $rozgrywki = json_decode($rozgrywki, true);             

        if (empty($rozgrywki[0])){
            $punktyGraczy = DB::table('tekken_rozgrywka')->where([['UID_rozgrywki','=',$UID], ['gracz_1','!=','NULL']])->get();
            $punktyGraczy = json_decode($punktyGraczy, true);            

            echo '<pre>';
            print_r($punktyGraczy);
            echo '</pre>';

        } 

    }


    public function updateWynikSrozgrywki($id, $gracz1, $gracz2) {
        $updateBad = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->update(['wynik_gracz_1' => $gracz1]);
        $updateBad = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->update(['wynik_gracz_2' => $gracz2]);
    }

    public function zakonczRozgrywke($id) {
        $updateBad = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->update(['status' => 'zakonczony']);
        $updateBad = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->update(['stan_gry' => 'zakonczony']);
    }



}