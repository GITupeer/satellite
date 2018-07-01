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


    public function getAdditionalInfo() {
        // RCS
        // category
        // category_id

    }


    public function ZwrocLiczbeDniDoWydarzenia($data_wydarzenia) {
        $data_aktualna = Date("Y-m-d");
     
        $liczba_sekund_dla_wydarzenia = StrToTime($data_wydarzenia);
        $liczba_sekund_dla_aktualnej_daty = StrToTime($data_aktualna);
     
        $liczba_sekund_miedzy_datami = $liczba_sekund_dla_wydarzenia - $liczba_sekund_dla_aktualnej_daty;
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
            $satelliteInfo = DB::table('satellite_informations')->select('*')->where([['satellite_id','=',$row['NORAD_CAT_ID']]])->get();
            $satelliteInfo = json_decode($satelliteInfo, true);

            if (!empty($satelliteInfo[0])){
                $lauch_date_day = $this->ZwrocLiczbeDniDoWydarzenia($row['LAUNCH']);
                $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$row['NORAD_CAT_ID']]])->update(['launch_date_day' => $lauch_date_day]);
                $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$row['NORAD_CAT_ID']]])->update(['Perigee' => $row['PERIGEE']]);
                $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$row['NORAD_CAT_ID']]])->update(['Apogee' => $row['APOGEE']]);
                $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$row['NORAD_CAT_ID']]])->update(['Peroid' => $row['PERIOD']]);
                $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$row['NORAD_CAT_ID']]])->update(['Inclination' => $row['INCLINATION']]);
                $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$row['NORAD_CAT_ID']]])->update(['Intl_Code' => $row['OBJECT_ID']]);
                $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$row['NORAD_CAT_ID']]])->update(['Launch_Site' => $row['COUNTRY']]);
                $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$row['NORAD_CAT_ID']]])->update(['comment' => $row['COMMENT']]);
                $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$row['NORAD_CAT_ID']]])->update(['onOrbit' => 0]);

                if (empty($satelliteInfo[0]['tle'])){
                    $tle = file_get_contents('http://www.n2yo.com/sat/gettle.php?s='.$row['NORAD_CAT_ID']);
                    $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$row['NORAD_CAT_ID']]])->update(['tle' => $tle]);
                    $tle = json_decode($tle, true);
                    if (!empty($tle[1])){
                        echo $row['NORAD_CAT_ID'];
                        $explodeTLE2 = explode(' ', $tle[1]);
                        $perDay = round($explodeTLE2[7], 4);
                        $perDay = round($explodeTLE2[7], 2);
                        $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$row['NORAD_CAT_ID']]])->update(['perDay' => $perDay]);
                    }
                } else {
                    $tle = json_decode($satelliteInfo[0]['tle'], true);
                        echo $row['NORAD_CAT_ID'];
                        $explodeTLE2 = explode(' ', $tle[1]);
                        $perDay = round($explodeTLE2[7], 4);
                        $perDay = round($explodeTLE2[7], 2);
                        $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$row['NORAD_CAT_ID']]])->update(['perDay' => $perDay]);
                                     
                }
                
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
                
                $tle = file_get_contents('http://www.n2yo.com/sat/gettle.php?s='.$row['NORAD_CAT_ID']);
                $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$row['NORAD_CAT_ID']]])->update(['tle' => $tle]);
                $tle = json_decode($tle, true);
                if (!empty($tle)){
                    $explodeTLE2 = explode(' ', $tle[1]);
                    $perDay = round($explodeTLE2[7], 4);
                    $perDay = round($explodeTLE2[7], 2);
                    $newstatus = DB::table('satellite_informations')->where([['satellite_id','=',$row['NORAD_CAT_ID']]])->update(['perDay' => $perDay]);
                }

            }


        }


    }

}
