<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Response;

class APIController extends BaseController
{


    public function cron_category_info() {
        $category = DB::table('category')->select('*')->get();
        $category = json_decode( $category, true);      


       foreach($category as $cat){
            if ($cat['category_id'] != 122){
                $satellite_api = 'https://www.n2yo.com/rest/v1/satellite/above/41.702/-76.014/0/360/'.$cat['category_id'].'/&apiKey=G5SS8B-WJK25E-PU9GFC-3U40';
                $json =  file_get_contents($satellite_api);
                $array = json_decode($json, true);


                foreach($array['above'] as $row){
                    $categoryInfoDBInsert = DB::table('category_informations')->insert(
                        [
                        'satellite_id' => $row['satid'],
                        'category' => $cat['category_name'],
                        'category_id' => $cat['id']
                        ]
                    );
                }
            }
        }


    }



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


                    if (!empty($Apogee[1])){ 
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
                            'Apogee' => $arr['Apogee']
                        ]);
                        $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$satellite2[0]['satellite_id']]])
                        ->update([
                            'Perigee' => $arr['Perigee']
                        ]);
                        $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$satellite2[0]['satellite_id']]])
                        ->update([
                            'RCS' => $arr['RCS']
                        ]);
                        $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$satellite2[0]['satellite_id']]])
                        ->update([
                            'Inclination' => $arr['Inclination']
                        ]);
                        $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$satellite2[0]['satellite_id']]])
                        ->update([
                            'Semi_major_axis' => $arr['Semi_major_axis']
                        ]);
                    }
                }



            } else {
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


    public function satellite($title) {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Header: *");
        $satellite = DB::table('satellite_informations')->select('*')->where([['satellite_id','=',$title]])->get();
        $satellite = json_decode( $satellite, true);



      
        
        
        if (!empty($satellite[0])){
            if (empty($satellite[0]['Peroid']) AND !empty($satellite[0]['tle'])){
                $tle = $satellite[0]['tle'];
                $tle = json_decode($tle, true);
                
                $explodeTLE2 = explode(' ', $tle[1]);
                $perDay = round($explodeTLE2[7], 4);
                $satellite[0]['perDay'] = round($explodeTLE2[7], 2);
                $satellite[0]['Peroid'] = round(1440/$perDay, 2);

                $newstatus = DB::table('satellite_informations')->where([['id','=',$satellite[0]['id']]])->update(['Peroid' => $satellite[0]['Peroid']]);
                $newstatus = DB::table('satellite_informations')->where([['id','=',$satellite[0]['id']]])->update(['perDay' => $satellite[0]['perDay']]);
            }


            if (empty($satellite[0]['Intl_Code']) AND !empty($satellite[0]['tle'])){
                $tle = $satellite[0]['tle'];
                $tle = json_decode($tle, true);
                
                $explodeTLE1 = explode(' ', $tle[0]);
                $INTLCODE_TLE = $explodeTLE1[2];
                $year = substr($INTLCODE_TLE, 0,2);
                if ($year > '30' AND $year <= 99){
                    $yearFull = '19'.$year;
                } else {
                    $yearFull = '20'.$year;
                }
                $nextString = substr($INTLCODE_TLE, 2,10);
                $Intl_Code = $yearFull.'-'.$nextString;
                $newstatus = DB::table('satellite_informations')->where([['id','=',$satellite[0]['id']]])->update(['Intl_Code' => $satellite[0]['Intl_Code']]);
                $satellite[0]['Intl_Code'] = $Intl_Code;
            }



            $category = DB::table('category_informations')->select('*')->where([['satellite_id','=',$title]])->get();
            $category = json_decode( $category, true);


            if (!empty($category[0])) {
                $category_string = '';
                foreach($category as $cat) {
                    if ($cat['category'] != 'ANY'){
                        if (empty($category_string)){
                            $category_string .= $cat['category'];
                        } else {
                            $category_string .= ', '.$cat['category'];
                        }
                    } else {
                        if (empty($category_string)){
                            $category_string .= 'Other';
                        } else {
                            $category_string .= ', Other';
                        }
                    }
                    
                }
            }
            if ($category_string == 'Other'){
                $category_string = 'Unclassified';     
            } else {
                $category_string = str_replace(', Other','', $category_string);
            }
            $satellite[0]['category'] = $category_string;



            if (!empty($satellite[0]['images'])) {
                $explodeImgae = explode('//', $satellite[0]['images']);
                $explodeImgae2 = explode('/', $explodeImgae[1]);
                $satellite[0]['ImageSource'] = $explodeImgae2[0];
            }



            $arr['status'] = 'OK';
            $arr['data'] = $satellite[0];
        } else {
            $arr['status'] = 'EMPTY';
            $arr['data'] = '';
        }
        $arr['information'] = "Use of this API requires the author's consent.";


        return $arr;

    }

  




    public function get_position($bounds){

        $boundsJSON =json_decode($bounds);

        header("Access-Control-Allow-Origin: *");
        $satellite = DB::table('satellite')->select('latitude', 'longitude', 'satellite_name', 'satellite_id')
        ->whereRaw("longitude < ".$boundsJSON->east." AND longitude > ".$boundsJSON->west." AND latitude < ".$boundsJSON->north." AND latitude > ".$boundsJSON->south."")
        ->limit(2)->get();
        $satellite = json_decode( $satellite, true);
        $query = '';
        $count=0; 
        foreach ($satellite as $row){
            if (empty($query)) {
                $query = '["'.$row['satellite_name'].' |*| '.$row['satellite_id'].'", '.$row['latitude'].', '.$row['longitude'].']';
            } else {
                $query .= ', ["'.$row['satellite_name'].' |*| '.$row['satellite_id'].'", '.$row['latitude'].', '.$row['longitude'].']';
            }
            $count++;
        }

     
        
            $arr['data'] = $query;
            $arr['status'] = 'OK';
            $arr['counter'] = $count;
            $arr['information'] = "Use of this API requires the author's consent.";
            
           return $arr;

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
        $ch = curl_init('https://nssdc.gsfc.nasa.gov/nmc/masterCatalog.do?sc=1998-067A'); //inicjacja curla
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $jakasZmienna2 = curl_exec($ch);
        curl_close($ch); 
        $jakasZmienna = iconv("iso-8859-2", "utf-8", $jakasZmienna2);
        $one = array("<", ">");
        $two   = array("&lt;", "&gt;");
        $newphrase = str_replace($one, $two, $jakasZmienna);


    }


    public function get_client_ip_env() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
     
        return $ipaddress;
    }

    public function userposition() {
        header("Access-Control-Allow-Origin: *");
    
        $ip = $this->get_client_ip_env();

        $url = 'http://ip-api.com/json/'.$ip;

        $json =  file_get_contents($url);
        $array = json_decode($json, true);

        $arrayToReturn['latitude'] = $array['lat'];
        $arrayToReturn['longitude'] = $array['lon'];
        $arrayToReturn['city'] = $array['city'];
        $arrayToReturn['ip'] = $array['query'];
        $arrayToReturn['timezone'] = $array['timezone'];


        $satellite = DB::table('satellite')->count();
        $arrayToReturn['website']['count'] = $satellite;

        sleep(1);

        return($arrayToReturn);

    }




    public function offsetRate() {

        $satellite = DB::table('satellite_informations')->select('*')->get();
        $satellite = json_decode($satellite, true);

        foreach($satellite as $sat){
            $satellite_id = $sat['satellite_id'];
            $rateOffset = DB::table('satellite_log')->select('*')->where([['satellite_id','=',$satellite_id]])->orderBy('timestamp', 'desc')->limit(2)->get();
            $rateOffset = json_decode( $rateOffset, true);
    
            $i=0;
            foreach($rateOffset as $offdet){
                if ($i != 0){
                    $lat = $lat - $offdet['latitude'];
                    $lng = $lng - $offdet['longitude'];
                    $data = strtotime($timestamp);
                    $data2 = strtotime($offdet['timestamp']);
    
                } else {
                    $lat = $offdet['latitude'];
                    $lng = $offdet['longitude'];
                    $timestamp = $offdet['timestamp'];
                }
    
    
                $i++;
            }
    
            if (!empty($data)){
                $sek = $data - $data2;
                $arr['offsetLat'] = $lat/$sek;
                $arr['offsetLng'] = $lng/$sek;
        
                $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$satellite_id]])
                ->update(['offsetLat' => $arr['offsetLat']]);
                $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$satellite_id]])
                ->update(['offsetLng' => $arr['offsetLng']]);               
            }

        }    




    }



    
}
