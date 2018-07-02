<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Response;

class SatelliteController extends BaseController
{




    public function JD($rok, $miesiac,$dzien,$sekunda,$min,$sec, $timezone) {
        if($miesiac > 2){
            $miesiac = $miesiac;
            $rok = $rok;
        }
        if($miesiac <= 2){
            $miesiac = $miesiac + 12;
            $rok = $rok - 1;           
        }

        $A = (int) $rok / 100;
        $tmp1 = (int) ($A/4);
        $b = 2 - $A + $tmp1;
        $JD = (365.25 * ($rok + 4716) + (30.6001 * ($miesiac + 1)) + $dzien + $b + (($sekunda + $min / 60 + $sec / 3600) / 24) - 1524.5)-($timezone/24);
        return $JD;
    }




    public function rang($x){
        $b = $x/360;
        $intB = (int) $b;
        $A = 360*($b - $intB);
        if ($A < 0 ){
            $A = $A + 360;
        } 

        return $A;

    }


    public function SternzeitGreenwich($JD){
        $d = ($JD - 2451545) / 36525;
        $gst = 280.46061837 + 360.98564736629 * ($JD - 2451545) + 0.000387933 * $d * $d - $d * $d * $d / 38710000;
        $gst = $this->rang($gst);
        $SternzeitGreenwich = ($gst / 15)*15;
        $SternzeitGreenwich = $this->rang($SternzeitGreenwich);
        return $SternzeitGreenwich;
    }


    public function day2000($year, $month, $day, $hour, $min, $sec) {
        $a = 10000 * $year + 100 * $month + $day;
        if ($month <= 2) {
            $month = $month + 12;
            $year = $year - 1;
        }
         
        $b = $year / 400 - $year / 100 + $year / 4; 
        $A = 365 * $year - 730548.5;
        $day2000 = $A + $b + 30.6001 * ($month + 1) + $day + ($hour + $min / 60 + $sec / 3600) / 24;
        return $day2000;        

    }

    public function dateSerial($rok,$miesiac,$dzien) {
        $day2000 = $this->day2000($rok,$miesiac,$dzien,12,0,0);
        $DateSerial = 36526+ $day2000;
        return $DateSerial;
    }

    public function epochDatum($epoch) {
        $ept =  (int) $epoch;
        $eptj = (int) $ept / 1000;
        $Jahr = intval(2000+$eptj);
        $tag = $ept - $eptj * 1000;
        $datum =  $this->dateSerial($Jahr, date('m'), date('d')) + $tag - 1;
        return round($datum);

    }



    public function dap($mm, $i, $e){
        $erdradius=6378.14;
        $s = ($mm / 13750.98708) * ($mm / 13750.98708);
        $A = pow((398601.2 / $s),(1 / 3));
        $dap = (5 / (pow(($A / $erdradius),3.5) * (1 - $e) * (1 - $e))) * (5 * Cos($i * 3.14159265358979 / 180) * Cos($i * 3.14159265358979 / 180) - 1);
        return $dap;
    }



    public function draan($mm, $i, $e) {
        $i = 51.6430;
        $erdradius=6378.14;
        $s = ($mm / 13750.98708) * ($mm / 13750.98708);
        $A = pow((398601.2 / $s), (0.33333333333333));
        $draan = (-9.98 / ( pow(($A / $erdradius), 3.5) * (1 - $e) * (1 - $e))) * cos($i * 3.14159265358979 / 180);
        return $draan;
    }

    public function gha($mm){
        $s = ($mm / 13750.98708) * ($mm / 13750.98708);
        $gha = pow((398601.2 / $s), (1 / 3));
        return $gha;
    }



    public function ExzentrischeAnomalie($m, $e) {

        $A = $m + $e * (180 / 3.14159265358979) * sin($m * 3.14159265358979 / 180) * (1 + $e * Cos($m * 3.14159265358979 / 180));
        $A = $A - ($A - $e * (180 / 3.14159265358979) * sin($A * 3.14159265358979 / 180) - $m) / (1 - $e * Cos($A * 3.14159265358979 / 180));
        $A = $A + $e * (180 / 3.14159265358979) * sin(0 * 3.14159265358979 / 180) * (1 + $e * Cos($A * 3.14159265358979 / 180));
        $A = $A - ($A - $e * (180 / 3.14159265358979) * sin($A * 3.14159265358979 / 180) - $m) / (1 - $e * Cos($A * 3.14159265358979 / 180));
   
 
        $A = $A / 360;
        $b = (int) $A;
        $c = $A - $b;
        $d = 360 * $c;
        if ($d < 0){
            $d = 360+$d;
        }
        $ExzentrischeAnomalie = $d;
        return $ExzentrischeAnomalie;

    }

    public function atan2($a,$b){
        if (($a=0) && ($b<0)) $pom =-pi()/2;
        if (($a=0) && ($b>0)) $pom =pi()/2;
        if (($a<0) && ($b<0)) $pom = atan($b/$a)-pi();
        if (($a=0) && ($b=0)) $pom = 0;
        if (($a<0) && ($b>=0)) $pom = atan($b/$a)+pi();
        if (($a>0) && ($b=0)) $pom = 0;
        if (($a>0) && ($b<0) || ($b>0)) $pom = atan($b/$a);
        $atan2 = $pom;
        return $atan2;

    }



    public function WahreAnomalie($m, $e) {
        $A = $m + $e * (180 / 3.14159265358979) * sin($m * 3.14159265358979 / 180) * (1 + $e * Cos($m * 3.14159265358979 / 180));
        $A = $A - ($A - $e * (180 / 3.14159265358979) * sin($A * 3.14159265358979 / 180) - $m) / (1 - $e * Cos($A * 3.14159265358979 / 180));
        $A = $A + $e * (180 / 3.14159265358979) * sin(0 * 3.14159265358979 / 180) * (1 + $e * Cos($A * 3.14159265358979 / 180));
        $A = $A - ($A - $e * (180 / 3.14159265358979) * sin($A * 3.14159265358979 / 180) - $m) / (1 - $e * Cos($A * 3.14159265358979 / 180));

        $A = $A / 360;
        $b = (int) $A;
        $c = $A - $b;
        $d = 360 * $c;
        if ($d < 0){
            $d = 360+$d;
        }

        $k = sqrt((1 + $e) / (1 - $e));
        $L = tan(($d / 2) * 3.14159265358979 / 180);
        $N = ($L*$k);
        $o = atan2(5, $N) * 180 / 3.14159265358979;
        $o = 2 * $o;


        if ($o < 0){
            $o = 360 + $o;
        }
        $WahreAnomalie = $d;
        return $WahreAnomalie;
    
    }

 

    public function  dateTLE($dateTLE){
        $date['date_TLE'] = $dateTLE;
       $year = substr($dateTLE, 0,2);
       if ($year > 50 && $year < 99){
            $date['year'] = '19'.$year;
       } else {
            $date['year'] = '20'.$year;
       }

       $dayOfYearE = explode('.',$date['date_TLE']);
       $dayOfYear = substr($dayOfYearE[0], 2, 10);
       $date['month'] = date( 'm', strtotime( '2018-01-01' .' +'.$dayOfYear.' day' ));
       $date['day'] = date( 'd', strtotime( '2018-01-01' .' +'.$dayOfYear.' day' ))-2;

       $minutes = $dayOfYearE[1]*337/23432123;
       $minutes = round($minutes / 60, 2);
       $minutes = explode('.', $minutes);
       $date['hour'] = $minutes[0]-3;
       $sec = explode('.', 60-$minutes[0]*60/100);
       $date['min'] = round($sec[0], 2)+5;
       $date['sec'] = $sec[1]*60/10;
       return $date;

    }



    public function GeogrBreite($dek){
        $GeogrBreite = $dek + 0.1924 * sin(2 * $dek * 3.14159265358979 / 180);
        return $GeogrBreite;
    }

            
    

 

    public function getPosition() {
        $arr = array();


                $tle = '["1 25544U 98067A 18182.82365002 +.00001702 +00000-0 +33102-4 0 9999\r","2 25544 051.6426 305.7534 0003492 251.5586 257.2723 15.53997847120798"]';
                $tle = json_decode($tle);
                $data['tle'] = $tle;
                $tle[0] = str_replace(array('  ', '   ','    '), array(' ',' ',' '), $tle[0]);
     
                $explode_TLE_1 = explode(' ', $tle[0]);
    

                    $tle[1] = str_replace(array(' ','  ', '   ','    '), array(' ',' ',' ',' '), $tle[1]);
                    $explode_TLE_2 = explode(' ', $tle[1]);
                    
                    date_default_timezone_set('Europe/Warsaw');

                    $script_tz = date_default_timezone_get();


                    $data['date'] = date('Y-m-d H:i:s');

                    $data['b3'] = date('Y');
                    $data['b4'] = date('m');
                    $data['b5'] = date('d')-1;
                    $data['b6'] = date('H')-3;//date('H');
                    $data['b7'] = date('i')+7;//date('i');
                    $data['b8'] = date('s')+30;//date('s');
                    $data['b9'] = 2;
                    $data['Mean_Motion'] = str_replace(array('+','-'), array('0','0'), $explode_TLE_1[4]);
                    $data['Epoka_TLE'] = str_replace(array('+','-'), array('0','0'), $explode_TLE_1[3]);
                    $data['Inklinacja'] = $explode_TLE_2[2];
                    $data['RAAN'] = $explode_TLE_2[3];
                    $data['excentrity'] = '0.'.$explode_TLE_2[4];
                    $data['Arg_Peri'] = $explode_TLE_2[5];
                    $data['Mean_Anomaly'] = $explode_TLE_2[6];
                    $Mean_Motion_MM = explode('.', $explode_TLE_2[7]);
                    $data['Mean_Motion_MM'] = $Mean_Motion_MM[0].'.'.substr($Mean_Motion_MM[1], 0, 8);
                    

                    $data['epoch_datum'] = $this->epochDatum($data['Epoka_TLE']);
                    $epochZeit = explode('.', $data['Epoka_TLE']);
                    $data['epoch_zeit'] = '0.'.$epochZeit[1];

                    // DATA EYGENEROWANIA TLE DD/MM/YY HH:II:SS
                    $dataTLE = $this->dateTLE($data['Epoka_TLE']);
                    $data['epoch_JD'] = $this->JD($dataTLE['year'], $dataTLE['month'],$dataTLE['day'],$dataTLE['hour'],$dataTLE['min'],$dataTLE['sec'], 0);                                                                                 // DO POPRAWY
                    $data['now_JD'] = $this->JD($data['b3'], $data['b4'],$data['b5'],$data['b6'],$data['b7'],$data['b8'], $data['b9']);
                    $data['GMST'] = $this->SternzeitGreenwich($data['now_JD']);
                    $data['deltaT'] = $data['now_JD'] - $data['epoch_JD'];
                    $data['draan'] = $this->draan($data['Mean_Motion_MM'], $data['Inklinacja'], $data['excentrity']);
                    $data['tmp_1'] = $data['RAAN']+$data['draan']*$data['deltaT'];
                    $data['dap'] = $this->dap($data['Mean_Motion_MM'], $data['Inklinacja'], $data['excentrity']);
                    $data['tmp_2'] = $data['Arg_Peri']+ $data['dap']*$data['deltaT'];
                    $data['tmp_3'] = 1440/$data['Mean_Motion_MM'];
                    
                    $data['tmp_4a'] = 2*$data['Mean_Motion']*360;
                    $data['tmp_4b'] = $data['Mean_Motion_MM']*360;
                    $data['tmp_4c'] = $data['deltaT']/2;
                    $data['tmp_4'] = -$data['tmp_4a']/$data['tmp_4b']/3*$data['tmp_4c'];

                    $data['tmp_5'] = 1 - 3 * $data['tmp_4'];
                    $data['tmp_6'] = 1 + 4 * $data['tmp_4'];
                    $data['tmp_7'] = 1 -7 * $data['tmp_4'];
                    $data['tmp_8'] = $this->rang($data['Mean_Anomaly'] + ($data['Mean_Motion_MM']*360*$data['deltaT']*$data['tmp_5']));                 // DO POPRAWY
                    $data['tmp_9'] = $data['tmp_7']*( $data['RAAN']+$data['deltaT']*$data['draan']);
                    $data['tmp_10'] = $data['tmp_6']*( $data['Arg_Peri']+$data['dap']*$data['deltaT']);
                    $gha = $this->gha($data['Mean_Motion_MM']);
                    $data['tmp_11'] = $data['tmp_6']*$gha;
                    $data['tmp_12'] = $data['tmp_6']*$data['tmp_11']*(1-$data['excentrity']*$data['excentrity']);
                    $ExzentrischeAnomalie = $this->ExzentrischeAnomalie($data['tmp_8'], $data['excentrity']);
                    $data['tmp_13'] =  $ExzentrischeAnomalie;
                    $data['tmp_14'] = $this->WahreAnomalie($data['tmp_8'], $data['excentrity']);
                    $data['tmp_15'] = $data['tmp_11']*(1-$data['excentrity']*cos($data['tmp_13']*pi()/180));
                    $data['altitude'] = ($data['tmp_15']-6378.13649);
                    $data['speed'] = 631.35/sqrt($data['tmp_15']);
                    $data['tmp16'] = $this->rang($data['tmp_10']+$data['tmp_14']);
                    $data['tmp_17'] = ASIN( SIN($data['Inklinacja']*PI()/180) *SIN($data['tmp16']*PI()/180) )*180/PI();
                    $data['latitude'] = $this->GeogrBreite($data['tmp_17']);
                    $data['tmp18'] = ATAN(COS($data['Inklinacja']*PI()/180)*TAN($data['tmp16']*PI()/180))*180/PI();
                    $data['tmp_19'] =(360-($data['tmp_9']+$data['tmp_17'])+$data['GMST']);

                    if ((COS($data['tmp16']*PI()/180)) < 0){
                        $data['tmp_20'] = $this->rang($data['tmp_19']-180);
                    } else {
                        $data['tmp_20'] = $this->rang($data['tmp_19']);
                    }

             

                    if ($data['tmp_20']<180) {
                        $data['longitude'] = -$data['tmp_20'];
                    } else {
                        $data['longitude'] = 360-$data['tmp_20'];  
                    }
        
                    echo '<pre>';
        print_r($data);
        echo '</pre>';

    }


}