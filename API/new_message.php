<?php

    require_once("../config/dbconnect.functions.php");

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $data = json_decode($rest_json, true);

    $array = $data['data'];
    $response = array();

    foreach ($array as $key => $_POST) {

        $sender_id = trim(strip_tags($_POST["sender_id"]));
        $receiver_id = trim(strip_tags($_POST["receiver_id"]));
        $main_text_message = trim(strip_tags($_POST["main_text_message"]));
        $response_to = trim(strip_tags($_POST["response_to"]));
        $message_read = trim(strip_tags($_POST["message_read"]));
        $date_creation = trim(strip_tags($_POST["date_creation"]));

        $queryCount = "SELECT sender_id, receiver_id, main_text_message, response_to, date_creation, COUNT(*) AS message_exists FROM messages_yambi WHERE sender_id=? AND receiver_id=? AND main_text_message=? AND response_to=? AND date_creation=?";
        $requestCount = $database_connect->prepare($queryCount);
        $requestCount->execute(array($sender_id, $receiver_id, $main_text_message, $response_to, $date_creation));
        $responseCount = $requestCount->fetchObject();

        if($responseCount->message_exists == 0) {
            $query = "INSERT INTO messages_yambi(sender_id, receiver_id, main_text_message, response_to, message_read, date_creation) VALUES(?, ?, ?, ?, ?, ?)";
            $request = $database_connect->prepare($query);
            $request->execute(array($sender_id, $receiver_id, $main_text_message, $response_to, 0, $date_creation));
        }
    }

    $response['success'] = '1';
    echo json_encode($response);

?>