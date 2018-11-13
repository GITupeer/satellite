<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Response;

class UpeerFinanseController extends BaseController
{


    public function generateCorrectDate() {
        header("Access-Control-Allow-Origin: *");
        
        $corectDateData = DB::table('upeer-app-transaction')->where([['correctDate','=',NULL]])->get();

        foreach($corectDateData as $row){
            $correctDate = str_replace(
                array('Oct', 'Nov', 'Dec'),
                array('10', '11', '12'),
                $row->date
            );

            $explodeHour = explode(' ', $correctDate);
            $explodeDate = explode('', $explodeHour[0]);
            if (strlen($explodeDate[2]) == 10){
                $correctDate = $explodeDate[0].'-'.$explodeDate[1].'-0'.$explodeDate[2].' '.$explodeHour[1];  
            }
            DB::table('upeer-app-transaction')->where([['id','=',$row->id]])->update(['correctDate' => $correctDate]);
        }


    }


    public function getSaldo() {
        header("Access-Control-Allow-Origin: *");
        $saldo = DB::table('upeer-app-data')->select('*')->get();
        $saldo = json_decode( $saldo, true);

        return $saldo;
    }


    public function getMail() {

            $appData = DB::table('upeer-app-data')->select('*')->get();
            $appData = json_decode($appData, true);

            $lastDate = $appData[0]['last_date_transaction'];
     
        // $#$#$#$#$#$#$#$# Polaczenie do GMAIL $#$#$#$#$#$#$#$#
            $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
            $username = 'notification.alior@gmail.com';
            $password = 'notialior';
            $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
            $emails = imap_search($inbox,'ALL');


        // $#$#$#$#$#$#$#$# Stale Wartosci $#$#$#$#$#$#$#$#
        
            $accountName = array(
                0 => 'Konto Firmowe',
                1 => 'Konto Prywatne',
            );
        
            $cards = array(
                0 => '21...3524',
                1 => '42...8241',
            );
            
            $saldo = array(
                '21...3524' => 0,
                '42...8241' => 0
            );

            $lastTransakciion = array(
                '21...3524' => 0,
                '42...8241' => 0
            ); 

            $autorizations = array(
                0 => '51...9259',
                1 => '55...3623',
            ); 

            $search = array(
                0 =>'Bieżące saldo na rachunku 21...3524 wynosi ',
                1 =>'Bieżące saldo na rachunku 42...8241 wynosi ',
                2 => 'Saldo rachunku po operacji: ',
                3 => 'Kwota transakcji: '
            );

            $searchType = array(
                0 => 'Saldo Rachunku',
                1 => 'Saldo Rachunku',
                2 => ' Rachunku',
                3 => 'Autoryzacja Transakcji'
            );

            $apiData = array();
            
            
            

            if($emails) {
                $output = '';
                rsort($emails);
                
                $i=0;
                foreach($emails as $email_number) {
                    
                    $overview = (array) imap_fetch_overview($inbox,$email_number,0);
                    $infoMessage = (array) $overview[0];
                    
                    $message = (string) imap_fetchbody($inbox,$email_number,1);
                    $message = str_replace('=','%',$message);
                    $zamiana = array (
                    "%C4%99" => "ę",
                    "%C3%B3" => "ó",
                    "%C5%82" => "ł",
                    "%C5%9B" => "ś",
                    "%C4%85" => "ą",
                    "%C5%BC" => "ż",
                    "%C5% %BC" => "ż",
                    "%C5%BA" => "ź",
                    "%C4%87" => "ć",
                    "%C5%84" => "ń",
                    "%C4%98" => "Ę",
                    "%C3%93" => "Ó",
                    "%C5%81" => "Ł",
                    "%C5%9A" => "Ś",
                    "%C4%84" => "Ą",
                    "%C5%BB" => "Ż",
                    "%C5%B9" => "Ź",
                    "%C4%86" => "Ć",
                    "%C5%83" => "Ń",
                    "%C4% %85" => "ą"
                    ); 
                    $text = (string) strtr($message, $zamiana);
                    $text = str_replace(array('%','C5',' BC'), array('','',''), $text);

                

              

                
                    $j =0;
                    $explodeDate = explode(' ', $infoMessage['date']);

                    if ($lastDate == $explodeDate[3].'-'.$explodeDate[2].'-'.$explodeDate[1].' '.$explodeDate[4]){
                        break;
                    }

                    $findeText = array(
                        'date' => $explodeDate[3].'-'.$explodeDate[2].'-'.$explodeDate[1].' '.$explodeDate[4],
                        'status' => 'null',
                        'MCC' => 'null',
                        'saldo' => 'null',
                        'obciazenie' => 'null',
                        'uznanie' => 'null'
                    );
                    
                    
                    foreach($search as $pharse){
                        
                        $explode =  explode($pharse, $text);
                        if (!empty($explode[1])){
                            $findeText['konto'] = 'null';
                            $findeText['card'] = 'null';
                            $cardCounter = 0;
                            
                            foreach($cards as $card) {
                                $explodeCard = explode($card, $text);	
                                if (!empty($explodeCard[1])){
                                    $findeText['card'] = $card;
                                    $findeText['konto'] = $accountName[$cardCounter];
                                    break;
                                }
                                $cardCounter++;
                            }
                            
                            if ($findeText['card'] == 'null'){
                                $autorizationCounter = 0;
                                foreach($autorizations as $autorization) {
                                    $explodeCardAutorization =  explode($autorization, $text);	
                                    if (!empty($explodeCardAutorization[1])){
                                        $findeText['card'] = $cards[$autorizationCounter];
                                        $findeText['konto'] = $accountName[$autorizationCounter];
                                        break;
                                    }
                                    $autorizationCounter++;
                                }			
                            }			
                            
                            $explodeFind = explode(' ',$explode[1]);
                            $explodeFind = explode('PLN',$explode[1]);
                            $findeText['status'] = 'ok';
                            $findeText['index'] = $j;
                            
                            $findeText['messgaeType'] = $searchType[$j];
                            $findeText['status'] = 'ok';
                            
                            
                            if ($findeText['index'] == 3) {
                                
                                $findeText['uznanie'] = 'null';
                                $findeText['obciazenie'] = str_replace(array(',', ' '),array('.', ''),$explodeFind[0]);
                                $findeText['saldo'] = 'null';
                            
                            } else if ($findeText['index'] == 2) { 

                                $findeText['uznanie'] = 'null';
                                $findeText['obciazenie'] = 'null';
                                $findeText['saldo'] = str_replace(array(',', ' '),array('.', ''),$explodeFind[0]);	
                                

                                $checkObciazanyUznany = array(
                                    0 => 'obcią',
                                    1 => 'uznany'
                                );
                                $obciazenieUznanieCount = 0;
                                foreach($checkObciazanyUznany as $check){
                                    $explodeCheck = explode($check, $text);
                                    if (!empty($explodeCheck[1])){
                                        $explodeSpacja = explode(' ',$explodeCheck[1]);
                                        if ($obciazenieUznanieCount == 0){
                                            $findeText['messgaeType'] = 'Obciązenie Rachunku';

                                            
                                            $explodeCorrectPrice = explode(',',$explodeSpacja[2]);
                                            if (!empty($explodeCorrectPrice[1])){
                                                $price = $explodeSpacja[2];
                                            } else {
                                                $price = $explodeSpacja[2].''.$explodeSpacja[3];
                                            }
                                            $findeText['obciazenie'] = str_replace(array(',', ' '),array('.', ''),$price);
                                            $MCCexplode =  explode('MCC ', $text);
                                            if (!empty($MCCexplode[1])){
                                                $findeText['MCC'] = substr($MCCexplode[1], 0, 4);
                                            }

                                        } else if ($obciazenieUznanieCount == 1){
                                            $findeText['messgaeType'] = 'Uznanie Rachunku';
                                            $explodeCorrectPrice = explode(',',$explodeSpacja[2]);
                                            if (!empty($explodeCorrectPrice[1])){
                                                $price = $explodeSpacja[2];
                                            } else {
                                                $price = $explodeSpacja[2].''.$explodeSpacja[3];
                                            }

                                            $findeText['uznanie'] = str_replace(array(',', ' '),array('.', ''),$price);
                                        } 


                               

                                    }
                                    $obciazenieUznanieCount++;
                                }

                                

                            } else {
                                $findeText['uznanie'] = 'null';
                                $findeText['obciazenie'] = 'null';
                                $findeText['saldo'] = str_replace(array(',', ' '),array('.', ''),$explodeFind[0]);	
                            }
                            break;
                        }
                        $j++;
                    }
                
                    $apiData[] = $findeText;
        
                    
                    if ($i == 100) {
                        break;
                    }
                    $i++;
                }


                $apiData = array_reverse($apiData);
                $apiDataCounter = 0;
                foreach($apiData as $data){
                    if($data['status'] != 'null'){
                        if($data['saldo'] != 'null'){
                            $saldo[$data['card']] = $data['saldo'];	
                        }
                        if($data['date'] != 'null'){
                            $lastTransakciion[$data['card']] = $data['date'];	
                        }

                        $categoryInfoDBInsert = DB::table('upeer-app-transaction')->insert(
                            [
                            'date' => $data['date'],
                            'MCC' => $data['MCC'],
                            'saldo' => $data['saldo'],
                            'obciazenie' => $data['obciazenie'],
                            'uznanie' => $data['uznanie'],
                            'konto' => $data['konto'],
                            'card' => $data['card'],
                            'messgaeType' => $data['messgaeType'],
                            ]
                        );

                    }
                }
                

                echo '<pre>';
                print_r($apiData);
                echo '</pre>';
                echo '<pre>';

                if (!empty($apiData)){
                    foreach($cards as $card) {
                        $updateData = DB::table('upeer-app-data')->where([['konto','=',$card]])->update(['saldo' => $saldo[$card]]);
                        $updateData = DB::table('upeer-app-data')->where([['konto','=',$card]])->update(['last_date_transaction' => $lastTransakciion[$card]]);
                    }                    
                }

                


                print_r($saldo);
                echo '</pre>';
                echo '<pre>';
                print_r($lastTransakciion);
                echo '</pre>';                
                
                
            } 

            /* close the connection */
            imap_close($inbox);

            $this->generateCorrectDate();

        }


    
}
