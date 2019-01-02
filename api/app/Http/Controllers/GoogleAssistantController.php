<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Response;

class GoogleAssistantController
{

    public $defaultText = "This is my text";
    

    public function processMessage($update) {
        if ($update['resaults']['action'] == ""){

            $fp = file_put_contents( 'request.log', $update['results']['parameters']['msg']);

            $this->sendMessage(array(
                'source' => $update['result']['source'],
                'speach' => $update['result']['parameters']['msg'],
                'displayText' => $this->defaultText,
                'contentOut' => array() 
            ));

        }
    }


    public function sendMessage($parameters){
        $req_dump = print_r($parameters, true);
        $fp = file_put_contents('reques4.log', $req_dump);

        header('Content-Type: application/json');
        echo json_encode($parameters);
    }


    public function googleActions() {
        header('Content-Type: application/json');

        $update_response = file_get_contents("php://input");
        $update = json_decode($update_response, true);

        if (isset($update['result']['actions'])){
            $this->processMessage($update);
        }  
    }
}