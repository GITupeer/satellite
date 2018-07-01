<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Response;

class Cron extends BaseController
{

    public function ZwrocLiczbeDniDoWydarzenia($data_wydarzenia) {
        $data_aktualna = Date("Y-m-d");
     
        $liczba_sekund_dla_wydarzenia = StrToTime($data_wydarzenia);
        $liczba_sekund_dla_aktualnej_daty = StrToTime($data_aktualna);
     
        $liczba_sekund_miedzy_datami = $liczba_sekund_dla_wydarzenia 
                         - $liczba_sekund_dla_aktualnej_daty;
        if ($liczba_sekund_miedzy_datami<0)
           return -1;
     
        $liczba_sekund_w_dniu = 60 * 60 * 24;
        $liczba_dni_miedzy_datami = 
              Floor ($liczba_sekund_miedzy_datami/$liczba_sekund_w_dniu);
     
        return $liczba_dni_miedzy_datami;
     }


    public function get_satellite_data() {
        $satellite = DB::table('TABLE 7')->select('*')->get();
        $satellite = json_decode( $satellite, true);

        foreach($satellite as $row) {
            $satelliteInfo = DB::table('satellite_informations')->select('*')->where([['satellite_id','=',$row['COL 3']]])->get();
            $satelliteInfo = json_decode($satelliteInfo, true);

            if (!empty($satelliteInfo[0])){
                
            } else {
                $lauch_date_day = $this->ZwrocLiczbeDniDoWydarzenia($row['LAUNCH']);

                $satelliteDB = DB::table('satellite_informations')->insert(
                    [
                    'launch_date' => $row['LAUNCH'],
                    'Perigee' => $row['PERIGEE'],
                    'Apogee' => $row['APOGEE'],
                    'name' => $row['OBJECT_NAME'],
                    'satellite_id' => $row['NORAD_CAT_ID'],
                    'Peroid' => $row['PERIOD'],
                    'Inclination' => $row['INCLINATION'],
                    'launch_date_day' => $lauch_date_day,
                    'Intl_Code' => $row['OBJECT_ID'],
                    'Launch_Site' => $row['COUNTRY'],
                    'onOrbit' => 0,
                    'comment' => $row['COMMENT'],
                    ]
                );               
            }


        }


    }

}
