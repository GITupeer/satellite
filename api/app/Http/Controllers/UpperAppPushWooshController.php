<?
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Response;
use App\Libraries\Push;

class PushWooshController
{

    const APPLICATION = '0240C-F21F3';
    const BEARER = 'e55GkubTetiSWcEiONN5TmhPGDxKmKDV1TloxKfirH9K8Yu6DfamBZmArFlGEepC37ec4ABbuOfAGGDi7JPK';



    public function sendPush($token, $body, $titile) {

        $results = Push::sendNotificationToMany($titile, $body, $token);

    }


    public function registerDevice($token) {
        


    }



}