<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class APIController extends BaseController
{


    public function cron_satellite_info() {
        $satellite = DB::table('satellite')->select('*')->get();
        $satellite = json_decode( $satellite, true);

        foreach($satellite as $oneSatellite){
            $satellite2 = DB::table('satellite_informations')->select('*')->where([['satellite_id','=',$oneSatellite['satellite_id']]])->get();
            $satellite2 = json_decode( $satellite2, true);

            if (!empty($satellite2[0])){


                if ($satellite2[0]['tle'] == ''){
                    $tle = file_get_contents('http://www.n2yo.com/sat/gettle.php?s='.$satellite2[0]['satellite_id']);
                    $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$satellite2[0]['satellite_id']]])
                     ->update(['tle' => $tle]);
                }
                if ($satellite2[0]['Apogee'] == ''){
                    $ch = curl_init('http://www.n2yo.com/satellite/?s='.$satellite2[0]['satellite_id']); //inicjacja curla
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $jakasZmienna2 = curl_exec($ch);
                    curl_close($ch); 
                    $jakasZmienna = iconv("iso-8859-2", "utf-8", $jakasZmienna2);
                    $one = array("<", ">");
                    $two   = array("&lt;", "&gt;");
                    $newphrase = str_replace($one, $two, $jakasZmienna);
            
            
                    $Apogee = explode('Apogee',$newphrase); 
                    $Apogee2 = explode(' ',$Apogee[1]); 
                    $arr['Apogee'] = $Apogee2[1].' km';
            
                    $Perigee = explode('Perigee',$newphrase); 
                    $Perigee2 = explode(' ',$Perigee[1]); 
                    $arr['Perigee'] = $Perigee2[1].' km';
            
                    $RCS = explode('RCS',$newphrase); 
                    $RCS2 = explode(' ',$RCS[1]); 
                    $arr['RCS'] = $RCS2[1];  
                    
                    
                    $Inclination = explode('Inclination',$newphrase); 
                    $Inclination2 = explode(' ',$Inclination[1]); 
                    $arr['Inclination'] = $Inclination2[1].' °';  
                    
                    $Semi_major_axis = explode('Semi major axis',$newphrase); 
                    $Semi_major_axis2 = explode(' ',$Semi_major_axis[1]); 
                    $arr['Semi_major_axis'] = $Semi_major_axis2[1].' km';  
                    $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$satellite2[0]['satellite_id']]])
                     ->update([
                         ['Apogee' => $arr['Apogee']], 
                         ['Perigee' => $arr['Perigee']]
                         ['RCS' => $arr['RCS']]
                         ['Inclination' => $arr['Inclination']]
                         ['Semi_major_axis' => $arr['Semi_major_axis']]
                    ]); exit;
                }



            } else {
                //$Peroid =     1440/{czas obiegu ziemi};
                // tle =>       API
                // speed =>
                // Perigee -> https://www.n2yo.com/sat/widget-tracking.php?s=25544
                // Apogee -> https://www.n2yo.com/sat/widget-tracking.php?s=25544
                // elevation => API
                // category    => API
                // category_id  => ODCZYT Z BAZY GDY API ZWOCI ID nazwa
                // azimuth =>   API
                // rcs
                // ORBIT - https://www.n2yo.com/sat/widget-tracking.php?s=25544

                $multiOfficeAccountCreate = DB::table('satellite_informations')->insert(
                    [
                    'latitude' => $oneSatellite['latitude'],
                    'longitude' => $oneSatellite['longitude'],
                    'altitude' => $oneSatellite['satalt'],
                    'launch_date' => $oneSatellite['launchDate'],
                    'tle' => '',
                    'azimuth' => '',
                    'speed' => '',
                    'Perigee' => '',
                    'Apogee' => '',
                    'elevation' => '',
                    'category' => '',
                    'name' => $oneSatellite['satellite_name'],
                    'category_id' => '',
                    'satellite_id' => $oneSatellite['satellite_id'],
                    ]
                );
            }

        }




    }

    public function cron() {
        $satellite_api = 'https://www.n2yo.com/rest/v1/satellite/above/41.702/-76.014/0/360/0/&apiKey=G5SS8B-WJK25E-PU9GFC-3U40';
        $json =  file_get_contents($satellite_api);
        $array = json_decode($json, true);



        /*$category = DB::table('category')->select('*')->get();
        $category =json_encode($category, true);
        foreach($category as $row){
            $arrayCategory[$row['category_name']] = $row;
        }
        */

        foreach($array['above'] as $sattelite) {
            $deleteOldPosition = DB::table('satellite')->where('satellite_id', '=', $sattelite['satid'])->delete();  
            $satelliteDB = DB::table('satellite')->insert(
                [
                'launchDate' => $sattelite['launchDate'],
                'intDesignator' => $sattelite['intDesignator'],
                'latitude' => $sattelite['satlat'],
                'longitude' => $sattelite['satlng'],
                'satalt' => $sattelite['satalt'],
                'satellite_name' => $sattelite['satname'],
                'sattelite_category' => 'ANY',
                'satellite_id' => $sattelite['satid'],
                'satellite_category_id' => 0,
                ]
            );


            $satelliteLogDB = DB::table('satellite_log')->insert(
                [
                'latitude' => $sattelite['satlat'],
                'longitude' => $sattelite['satlng'],
                'satellite_id' => $sattelite['satid']
                ]
            );

        }
            

    }

    public function test() {
       $ch = curl_init('http://www.n2yo.com/satellite/?s=41764'); //inicjacja curla
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       $jakasZmienna2 = curl_exec($ch);
       curl_close($ch); 
       $jakasZmienna = iconv("iso-8859-2", "utf-8", $jakasZmienna2);
       $one = array("<", ">");
       $two   = array("&lt;", "&gt;");
       $newphrase = str_replace($one, $two, $jakasZmienna);


        $Apogee = explode('Apogee',$newphrase); 
        $Apogee2 = explode(' ',$Apogee[1]); 
        $arr['Apogee'] = $Apogee2[1].' km';

        $Perigee = explode('Perigee',$newphrase); 
        $Perigee2 = explode(' ',$Perigee[1]); 
        $arr['Perigee'] = $Perigee2[1].' km';

        $RCS = explode('RCS',$newphrase); 
        $RCS2 = explode(' ',$RCS[1]); 
        $arr['RCS'] = $RCS2[1];  
        
        
        $Inclination = explode('Inclination',$newphrase); 
        $Inclination2 = explode(' ',$Inclination[1]); 
        $arr['Inclination'] = $Inclination2[1].' °';  
        
        $Semi_major_axis = explode('Semi major axis',$newphrase); 
        $Semi_major_axis2 = explode(' ',$Semi_major_axis[1]); 
        $arr['Semi_major_axis'] = $Semi_major_axis2[1].' km';         



        echo '<pre>';
        print_r($arr);
        echo '</pre>';

    }
    
}
