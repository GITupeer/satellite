<?php
 
function sendMessage($parameters) {
    echo json_encode($parameters);
}



function scenarios($queryText, $update) {
    $webhookText = "I am sorry I do not know what you mean.";

    // if ($queryText == 'Saldo konto firmowe') {
    //     $webhookText = 'The company account balance is PLN 23';   
    // }

    if ($queryText == 'Show me EXIT properties') {
        $webhookText = 'Here is EXIT properties! <a href="https://myexit.co/?&latitude=40.7257953&longitude=-74.1868973">myexit.co</a>';   
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
        "linkOutSuggestion" => array(
            "url" => 'http://google.com',
            "destinationName" => 'Suggestet Link'
        )
       
    ));
}


 
$update_response = file_get_contents("php://input");
$update = json_decode($update_response, true);

scenarios($update["queryResult"]["queryText"], $update);


?>