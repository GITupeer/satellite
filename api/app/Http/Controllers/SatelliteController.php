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

    
        $A = round($rok / 100,0);
        $b = 2 - $A + round($A/4,0);

        $tmp1 = round(365.25 * ($rok + 4716),0);
        $tmp2 = round(30.6001 * ($miesiac + 1),0);

        $JD = ($tmp1 + $tmp2 + $dzien + $b + (($sekunda + $min / 60 + $sec / 3600) / 24) - 1524.5)-($timezone/24);
        return $JD;
    }




    public function rang($x){
        $b = $x/360;
        $intB =  round($b,0);
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
       $date['hour'] = $minutes[0];
       $sec = explode('.', 60-$minutes[0]*60/100);
       $date['min'] = round($sec[0], 2);
       if (!empty($sec[1])){
           $date['sec'] = $sec[1]*60/10+37;
       } else {
        $date['sec'] = 0;
       }
       
       return $date;

    }



    public function GeogrBreite($dek){
        $GeogrBreite = $dek + 0.1924 * sin(2 * $dek * 3.14159265358979 / 180);
        return $GeogrBreite;
    }

            
    public function wyciagDateTLE($data, $czas) {
        // 86400 - 1
        //   x   - int
        $sec =  $czas*86400/1;
        $sec = explode('.', $sec);
        $data = $data - 39448;
        $dateNow = date( 'Y-m-d H:i:s', strtotime( '2008-01-01' .' +'. $data.' days' ));
        $data =  $dateNow = date( 'Y-m-d H:i:s', strtotime( $dateNow  .' +'.$sec[0].' seconds' ));
        $arr['year'] = date( 'Y', strtotime( $data  .' +0 seconds' ));
        $arr['month'] = date( 'm', strtotime( $data  .' +0 seconds' ));
        $arr['day'] = date( 'd', strtotime( $data  .' +0 seconds' ));
        $arr['hour'] = date( 'H', strtotime( $data  .' +0 seconds' ));
        $arr['min'] = date( 'i', strtotime( $data  .' +0 seconds' ));
        $arr['sec'] = date( 's', strtotime( $data  .' +0 seconds' ));

       return $this->JD($arr['year'], $arr['month'],$arr['day'],$arr['hour'],$arr['min'],$arr['sec'], 71.99972211); 

    }

 


    public function getOrbit($satellie_id) {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Header: *");
        $orbit = array();
        for($i=0; $i<15; $i++){
            $sec = ($i*500);
            $arr = $this->getPosition($sec, $satellie_id);
            $orbit[$i]['lat'] = $arr['latitude'];
            $orbit[$i]['lng'] = $arr['longitude'];

        }
      
        


        return $orbit;
        
    }


    public function getPosition($sec, $satellite_id) {
        $arr = array();
  
        $satellite2 = DB::table('satellite_informations')->select('*')->where([['satellite_id','=',$satellite_id]])->get();
        $satellite2 = json_decode( $satellite2, true);


        if ($satellite2[0]['tle']){
            $tle = $satellite2[0]['tle'];
        }
    


               // $arr = array();
               // $tle = '["1 25544U 98067A 18184.80969102 +.00001614 +00000-0 +31745-4 0 9993\r","2 25544 051.6414 295.8524 0003435 262.6267 204.2868 15.54005638121106"]';
                $tle = json_decode($tle);
                $data['tle'] = $tle;


      


                    $explode_TLE_1 = explode(' ', $tle[0]);
                    if (empty($tle[1])){
                        $arr['longitude'] = '';
                        $arr['latitude'] = '';
                        $arr['speed'] = '';
                        return $arr; exit;
                    }
                    $tle[1] = str_replace(array(' ','  ', '   ','    '), array(' ',' ',' ',' '), $tle[1]);

                    $explode_TLE_2 = explode(' ', $tle[1]);
                    

                    date_default_timezone_set('Europe/Warsaw');
                    $script_tz = date_default_timezone_get();

                    

                    $data['date'] = date('Y-m-d H:i:s', strtotime( ' +'.$sec.' seconds' ));
                    $explodeDate = explode(' ', $data['date']);
                    $hour = $explodeDate[0];
                    $time = $explodeDate[1];
                    $explodeHour = explode('-', $hour);
                    $explodeMin = explode(':', $time);

                    $data['b3'] = $explodeHour[0];
                    $data['b4'] = $explodeHour[1];
                    if (date('H') == 00){
                        $data['b5'] = $explodeHour[2];
                    } else {
                        $data['b5'] = $explodeHour[2]-1;
                    }
                    
                    $data['b6'] = $explodeMin[0];
                    $data['b7'] = $explodeMin[1];
                    $data['b8'] = $explodeMin[2];
                    $data['b9'] = 2;

                    $counterTLE1 = 3;
                    $flagTLE1 = false;
                    while($flagTLE1 == false){
                        if (!empty($explode_TLE_1[$counterTLE1])){
                            $flagTLE1 = true;
                            $data['Epoka_TLE'] = str_replace(array('+','-'), array('0','0'), $explode_TLE_1[$counterTLE1]);
                            $counterTLE1++;
                        } else {
                            $counterTLE1++;
                        }
                    }

                    $flagTLE1 = false;
                    while($flagTLE1 == false){
                        if (!empty($explode_TLE_1[$counterTLE1])){
                            $flagTLE1 = true;
                            $data['Mean_Motion'] = str_replace(array('+','-'), array('0','0'), $explode_TLE_1[$counterTLE1]);
                        } else {
                            $counterTLE1++;
                        }
                    }                   
                    
                    
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


                    $data['epoch_JD'] = $this->wyciagDateTLE($data['epoch_datum'], $data['epoch_zeit']);                                                                               // DO POPRAWY
                   
                    $data['now_JD'] = $this->JD($data['b3'], $data['b4'],$data['b5'],$data['b6'],$data['b7'],$data['b8'], $data['b9'])-1;
                  
                    
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
                    $data['tmp_8a'] = $data['Mean_Anomaly'] + ($data['Mean_Motion_MM']*360*$data['deltaT']*$data['tmp_5']);                 // DO POPRAWY
                   
                   
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
                    $data['tmp_19'] =(360-($data['tmp_9']+$data['tmp18'])+$data['GMST']);
                    $data['tmp_19a'] =($data['tmp_9']+$data['tmp_17']);

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



                    $returnData['longitude'] = $data['longitude'];
                    $returnData['latitude'] = $data['latitude'];
                    $returnData['altitude'] = $data['altitude'];
                    $returnData['speed'] = $data['speed'];
                    $returnData['date'] = $data['date'];
        
                    return $returnData;

    }



    public function get_position_of_satellites_json($bounds, $userLat, $userLng){
        $boundsJSON =json_decode($bounds);
        header("Access-Control-Allow-Origin: *");
        $satellite = DB::table('satellite')->select('latitude', 'longitude', 'satellite_name', 'satellite_id')
        ->whereRaw("longitude < ".$boundsJSON->east." AND longitude > ".$boundsJSON->west." AND latitude < ".$boundsJSON->north." AND latitude > ".$boundsJSON->south."")
        ->limit(10)->get();
        $satellite = json_decode( $satellite, true);
        $count=0; 
        $arr = array();
        foreach ($satellite as $row){
            $count++;
            $arr[$count]['latitude'] = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$row['latitude']));
            $arr[$count]['longitude'] = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$row['longitude']));

        }

        return $arr;

    }


    public function get_position_of_satellites_xml($bounds, $userLat, $userLng){
        $boundsJSON =json_decode($bounds);
        header("Access-Control-Allow-Origin: *");
        $satellite = DB::table('satellite')->select('latitude', 'longitude', 'satellite_name', 'satellite_id')
        ->whereRaw("longitude < ".$boundsJSON->east." AND longitude > ".$boundsJSON->west." AND latitude < ".$boundsJSON->north." AND latitude > ".$boundsJSON->south."")
        ->limit(10)->get();
        $satellite = json_decode( $satellite, true);
        $count=0; 
       
        $xml['data'] = '<markers>';
        $xml['data'] .= '<marker infoBox="no" motion="no" id="'.$count.'" image="http://icons.iconarchive.com/icons/paomedia/small-n-flat/24/map-marker-icon.png" offsetRateLat="" offsetRateLng="" name="Your Position." satellieID="000" address="n/o" lat="'.$userLat.'" lng="'.$userLng.'" type="user"/>';
            
            foreach ($satellite as $row){
                $count++;
                    $name = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$row['satellite_name']));
                    $satellite_id = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$row['satellite_id']));
                    $latitude = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$row['latitude']));
                    $longitude = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$row['longitude']));


                            
                    $offsetRateLat = '';
                    $offsetRateLat = '';    

                    if ($satellite_id == '25544') { $image = 'http://icons.iconarchive.com/icons/goodstuff-no-nonsense/free-space/24/international-space-station-icon.png'; }
                   // if ($satellite_id == '20580') { $image = 'http://icons.iconarchive.com/icons/goodstuff-no-nonsense/free-space/24/international-space-station-icon.png'; }
                    else { $image = 'http://icons.iconarchive.com/icons/google/noto-emoji-travel-places/24/42597-satellite-icon.png'; }

                    
            
                    $xml['data'] .= '<marker infoBox="yes" motion="yes" id="'.$count.'" image="'.$image.'" offsetRateLat="'.$offsetRateLat.'" offsetRateLng="'.$offsetRateLat.'" name="'.$name.'" satellieID="'.$satellite_id.'" address="n/o" lat="'.$latitude.'" lng="'.$longitude.'" type="satellite"/>';
                    
                
            }
        $xml['data'] .= '</markers>';
        


        $content = view("API_xml", $xml);
        return  Response::make($content, '200')->header('Content-Type', 'text/xml');
    }










    public function updatePosition(){
        $satellite2 = DB::table('satellite_informations')->select('*')->get();
        $satellite2 = json_decode( $satellite2, true);


        $sec = 0;
        foreach($satellite2 as $row){
            $arr = $this->getPosition($sec, $row['satellite_id']);                  
            $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$row['satellite_id']]])
            ->update(['latitude' => $arr['latitude']]);
            $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$row['satellite_id']]])
            ->update(['longitude' => $arr['longitude']]);
        }



    }







    

}