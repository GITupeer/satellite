<?php
 
function sendMessage($parameters) {
    echo json_encode($parameters);
}



function scenarios ($queryText) {
    $webhookText = "I'm sorry I do not know what you mean.";

    if ($queryText == 'Saldo konto firmowe') {
        $webhookText = 'The company account balance is PLN 23';   
    }

    sendMessage(array(
        "source" => $update["responseId"],
        "fulfillmentText" => $webhookText,
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
}


 
$update_response = file_get_contents("php://input");
$update = json_decode($update_response, true);



?>