<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class APIController extends BaseController
{
    public function cron() {
        echo 'test';
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
