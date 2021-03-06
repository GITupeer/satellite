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
        $speech = 'Here is EXIT properties!';
        $webhookText = 'Here is EXIT properties! <a href="https://myexit.co/?&latitude=40.7257953&longitude=-74.1868973">myexit.co</a>';   
    }   


    echo '{ 
        "payload": 
        { "google": 
            { 
                "expectUserResponse": true, 
                "richResponse": 
                { "items": 
                    [ 
                        { 
                            "simpleResponse": 
                            { 
                                "textToSpeech": "Here is exit properties:" 
                            } 
                        }, 
                        { 
                            "basicCard": 
                            { "title": "Real estate in your area!", 
                                "image": 
                                { 
                                    "url": "http://exitrealty.com/img/logo-small.png", 
                                    "accessibilityText": "EXIT Logo" 
                                }, 
                                "buttons": 
                                [ 
                                    { 
                                        "title": "Check Properties", 
                                        "openUrlAction": 
                                        { 
                                            "url": "https://myexit.co/?&latitude=40.7257953&longitude=-74.1868973" 
                                        } 
                                    } 
                                ], 
                                "imageDisplayOptions": "WHITE" 
                            } 
                        } 
                        ] 
                    } 
                } 
            } 
        }';


    // sendMessage(array(
    //     "source" => $update["responseId"],
    //     "fulfillmentText" => $webhookText,
    //     "payload" => array(
    //        "google" => array(
    //            "expectUserResponse" => true,
    //            "richResponse" => array(
    //                "items" => [
    //                    array(
    //                         "simpleResponse" => array(
    //                             "textToSpeech" => $speech
    //                         )
    //                     )
    //                 ],
    //                 "linkOutSuggestion" =>
    //                     array(
    //                         "destinationName" => "Website",
    //                         "url" => "https://assistant.google.com"
    //                     )
                    
    //            )
    //        )
    //     ),
       
    // ));
}


 
$update_response = file_get_contents("php://input");
$update = json_decode($update_response, true);

scenarios($update["queryResult"]["queryText"], $update);


?>