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

                $tle = file_get_contents('https://www.n2yo.com/sat/gettle.php?s='.$oneSatellite['satellite_id']);
                

                $newstatus = DB::table('satellite_informations')->where
                (
                    [
                        ['satellite_id','=',$satellite2[0]['satellite_id']]
                    ]
                )
                ->update(['tle' => $tle]);
echo $tle; exit;


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
        $satellite2 = DB::table('satellite_log')->select('*')->where([['satellite_id','=','25544']])->orderBy('id', 'desc')->limit(2)->get();
        $satellite2 = json_decode( $satellite2, true);
        echo '<pre>';
        print_r($satellite2);
        echo '</pre>';


        function distance($lat1, $lon1, $lat2, $lon2, $unit) {

            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);
          
            if ($unit == "K") {
              return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
              } else {
                  return $miles;
                }
          }
          
          echo distance($satellite2[0]['latitude'], $satellite2[0]['longitude'], $satellite2[1]['latitude'], $satellite2[1]['longitude'], "K") . " Kilometers<br>";
          ECHO distance($satellite2[0]['latitude'], $satellite2[0]['longitude'], $satellite2[1]['latitude'], $satellite2[1]['longitude'], "M"). " Miles<br>";
            $km = distance($satellite2[0]['latitude'], $satellite2[0]['longitude'], $satellite2[1]['latitude'], $satellite2[1]['longitude'], "K");
            $mil = distance($satellite2[0]['latitude'], $satellite2[0]['longitude'], $satellite2[1]['latitude'], $satellite2[1]['longitude'], "M");

          $czas = strtotime($satellite2[0]['timestamp']) - strtotime($satellite2[1]['timestamp']);
          $speed = $km/$czas;
          $speed2 = $mil/$czas;
echo  '<br>Spped km/s:'.$speed;
echo  '<br>Spped mil/s:'.$speed2;

        
    }
    
}
