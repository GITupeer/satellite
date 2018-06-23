<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class APIController extends BaseController
{
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
            $deleteOldPosition = DB::table('satellite')->where('sattelite_id', '=', $sattelite['satid'])->delete();  
            $multiOfficeAccountCreate = DB::table('satellite')->insert(
                [
                'launchDate' => $sattelite['launchDate'],
                'intDesignator' => $sattelite['intDesignator'],
                'latitude' => $sattelite['satlat'],
                'longitude' => $sattelite['satlng'],
                'satalt' => $sattelite['satalt'],
                'satellite_name' => $sattelite['satname'],
                'sattelite_category' => $array['category'],
                'sattelite_id' => $sattelite['satid'],
                'satellite_category_id' => 0,
                ]
            );


            mysql_query("INSERT INTO `upeer_sat`.`satellite` 
            (`id`, `launchDate`, `intDesignator`, `latitude`, `longitude`, `satalt`, `satellite_name`, `sattelite_category`, `sattelite_id`, `satellite_category_id`) 
            VALUES 
            (NULL, '".$sattelite['launchDate']."', '".$sattelite['intDesignator']."', '".$sattelite['satlat']."', '".$sattelite['satlng']."', '".$sattelite['satalt']."', 
            '".$sattelite['satname']."', '".$array['category']."', '".$sattelite['satid']."', '".$arrayCategory[$array['category']]['category_id']."');");
        }
            

    }

    public function test() {

        $domains = DB::table('category')
            ->select('*')
            ->get();

            echo '<pre>';
            print_r($domains);
            echo '</pre>';

        echo 'test';


        
    }
    
}
