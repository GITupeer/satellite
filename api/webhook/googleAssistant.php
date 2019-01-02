<?php
    function processMessage($update) {
        //if($update["result"]["action"] == "sayHello"){
            sendMessage(array(
                "source" => $update["result"]["source"],
                "speech" => "Hello from webhook",
                "displayText" => "Hello from webhook",
                "contextOut" => array()
            ));
        //}
    }

    function sendMessage($parameters) {
        $req_dump = print_r($parameters, true);
        $fp = file_put_contents('reques4.log', $req_dump);

        header('Content-Type: application/json');
        echo json_encode($parameters);
    }

    $update_response = file_get_contents("php://input");
    $update = json_decode($update_response, true);
    //if (isset($update["result"]["action"])) {
        processMessage($update);
    //}
?>