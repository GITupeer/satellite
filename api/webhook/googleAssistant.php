<?php
 
function sendMessage($parameters) {
    echo json_encode($parameters);
}
 
$update_response = file_get_contents("php://input");
$update = json_decode($update_response, true);

     sendMessage(array(
            "source" => $update["responseId"],
            "fulfillmentText"=>"Hello from webhook... ".$update["queryResult"]["queryText"],
            "payload" => array(
                "items"=>[
                    array(
                        "simpleResponse"=>
                    array(
                        "textToSpeech"=>"Bad request"
                         )
                    )
                ],
                ),
           
        ));

?>