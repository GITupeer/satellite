<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Response;

class TekkenController extends BaseController
{

    public function registerAccount($login, $pass) {
        $user = DB::table('tekken_user')->where([['user','=',$login]])->get();
        $user = json_decode($user, true);

        echo '<pre>';
        print_r($user);
        echo '</pre>';


    }



}