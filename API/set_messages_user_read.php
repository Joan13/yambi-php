<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/functions.functions.php';

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);
    $response = array();
    // $response_messages = array();

    $receiver_id = trim(strip_tags($_POST["receiver_id"]));
    $sender_id = trim(strip_tags($_POST["sender_id"]));

    $queryCount = "SELECT sender_id, receiver_id, message_read, COUNT(*) AS user_messages_exist FROM messages_yambi WHERE sender_id=? AND receiver_id=? AND message_read=?";
    $requestCount = $database_connect->prepare($queryCount);
    $requestCount->execute(array($receiver_id, $sender_id, 0));
    $responseCount = $requestCount->fetchObject();

    if($responseCount->user_messages_exist != 0) {
        $query = "UPDATE messages_yambi SET message_read=? WHERE sender_id=? AND receiver_id=? AND message_read=?";
        $request = $database_connect->prepare($query);
        $request->execute(array(3, $receiver_id, $sender_id, 0));
    }

    $response['success'] = '1';
    echo json_encode($response);

?>