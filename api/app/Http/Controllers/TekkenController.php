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


    public function noweRozdanie($UID){
        // gracz_1 != '' ORDER BY `stan_gry` DESC
      

        $rozgrywki = DB::table('tekken_rozgrywka')->where([['UID_rozgrywki','=',$UID], ['status','=','oczekiwanie'], ['gracz_1','!=','NULL']])->orderBy('id', 'ASC')->get();
        $rozgrywki = json_decode($rozgrywki, true);           
        


        if (empty($rozgrywki[0])){
            $punkty = DB::table('tekken_gracze')->where([['UID_rozgrywki','=',$UID]])->orderBy('punkty', 'DESC')->get();
            $punkty = json_decode($punkty, true);     
            $count = count($punkty);


            $tekken_turnieje = DB::table('tekken_turnieje')->where([['UID','=',$UID]])->get();
            $tekken_turnieje = json_decode($tekken_turnieje, true);   
            $odpada = $tekken_turnieje[0]['odpada'];


            $sprRundy = DB::table('tekken_rozgrywka')->where([['UID_rozgrywki','=',$UID], ['status','=','oczekiwanie']])->orderBy('id', 'ASC')->get();
            $sprRundy = json_decode($sprRundy, true);   
            
            if ($sprRundy[0]['tura'] == 'Final'){

                $newGamer = array();
                $j=0;
                for($j=0; $j<2; $j++){
 
                    $newGamer[] = $punkty[$j];
                }     

                $update = DB::table('tekken_rozgrywka')->where([['UID_rozgrywki','=',$UID], ['tura','=','Final']])->update(['gracz_1' => $newGamer[0]['id_gracza']]);
                $update = DB::table('tekken_rozgrywka')->where([['UID_rozgrywki','=',$UID], ['tura','=','Final']])->update(['gracz_1_nazwa' => $newGamer[0]['nazwa_gracza']]);
                $update = DB::table('tekken_rozgrywka')->where([['UID_rozgrywki','=',$UID], ['tura','=','Final']])->update(['gracz_2' => $newGamer[1]['id_gracza']]);
                $update = DB::table('tekken_rozgrywka')->where([['UID_rozgrywki','=',$UID], ['tura','=','Final']])->update(['gracz_2_nazwa' => $newGamer[1]['nazwa_gracza']]);
            } else {
                echo 'Rundy';
                $runda = $sprRundy[0]['tura'] - 1;
                
                $odejmij = $runda*$odpada;

                $newGamer = array();
                $j=0;
                for($j=0; $j<$count-$odejmij; $j++){
 
                    $newGamer[] = $punkty[$j];
                }            
                shuffle($newGamer);
                
                $noweRundy = DB::table('tekken_rozgrywka')->where([['UID_rozgrywki','=',$UID], ['tura','=',$sprRundy[0]['tura']]])->get();
                $noweRundy = json_decode($noweRundy, true);  
                
          
                
                $graczeCount = 0;
                foreach($noweRundy as $noweRozgrywkiDlaTur) {
                    $update = DB::table('tekken_rozgrywka')->where([['id','=',$noweRozgrywkiDlaTur['id']]])->update(['gracz_1' => $newGamer[$graczeCount]['id_gracza']]);
                    $update = DB::table('tekken_rozgrywka')->where([['id','=',$noweRozgrywkiDlaTur['id']]])->update(['gracz_1_nazwa' => $newGamer[$graczeCount]['nazwa_gracza']]);
                    if (empty($newGamer[$graczeCount+1])) {
                        $update = DB::table('tekken_rozgrywka')->where([['id','=',$noweRozgrywkiDlaTur['id']]])->update(['gracz_2' => $newGamer[0]['id_gracza']]);
                        $update = DB::table('tekken_rozgrywka')->where([['id','=',$noweRozgrywkiDlaTur['id']]])->update(['gracz_2_nazwa' => $newGamer[0]['nazwa_gracza']]);
                    } else {
                        $update = DB::table('tekken_rozgrywka')->where([['id','=',$noweRozgrywkiDlaTur['id']]])->update(['gracz_2' => $newGamer[$graczeCount+1]['id_gracza']]);
                        $update = DB::table('tekken_rozgrywka')->where([['id','=',$noweRozgrywkiDlaTur['id']]])->update(['gracz_2_nazwa' => $newGamer[$graczeCount+1]['nazwa_gracza']]);
                    }
                    $graczeCount++;
                }
            }


  
        } 

    }


    public function updateWynikSrozgrywki($id, $gracz1, $gracz2) {
        $updateBad = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->update(['wynik_gracz_1' => $gracz1]);
        $updateBad = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->update(['wynik_gracz_2' => $gracz2]);
    }

    public function zakonczRozgrywke($id) {
        $updateBad = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->update(['status' => 'zakonczony']);
        $updateBad = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->update(['stan_gry' => 'zakonczony']);

        $punktyGraczy = DB::table('tekken_rozgrywka')->where([['id','=',$id]])->get();
        $punktyGraczy = json_decode($punktyGraczy, true);

        if (!empty($punktyGraczy[0])){
            $wynik1 = $punktyGraczy[0]['wynik_gracz_1'];
            $gracz1 = $punktyGraczy[0]['gracz_1'];
            $wynik2 = $punktyGraczy[0]['wynik_gracz_2'];
            $gracz2 = $punktyGraczy[0]['gracz_2'];

            $gracz1Dane = DB::table('tekken_gracze')->where([['UID_rozgrywki','=',$punktyGraczy[0]['UID_rozgrywki']], ['id_gracza','=',$gracz1]])->get();
            $gracz1Dane = json_decode($gracz1Dane, true);

            $gracz2Dane = DB::table('tekken_gracze')->where([['UID_rozgrywki','=',$punktyGraczy[0]['UID_rozgrywki']], ['id_gracza','=',$gracz2]])->get();
            $gracz2Dane = json_decode($gracz2Dane, true);

            $wynikCorrect1 = $wynik1 + $gracz1Dane[0]['punkty'];
            $wynikCorrect2 = $wynik2 + $gracz2Dane[0]['punkty'];

            $updateBad = DB::table('tekken_gracze')->where([['UID_rozgrywki','=',$punktyGraczy[0]['UID_rozgrywki']], ['id_gracza','=',$gracz1]])->update(['punkty' => $wynikCorrect1]);
            $updateBad = DB::table('tekken_gracze')->where([['UID_rozgrywki','=',$punktyGraczy[0]['UID_rozgrywki']], ['id_gracza','=',$gracz2]])->update(['punkty' => $wynikCorrect2]);

            $this->noweRozdanie($punktyGraczy[0]['UID_rozgrywki']);
        }

        

  

    }



}