<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/functions.functions.php';

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);
    $response = array();
    $response_messages = array();

    $user_id = trim(strip_tags($_POST["user_id"]));

    $queryCount = "SELECT receiver_id, message_read, COUNT(*) AS user_messages_exist FROM messages_yambi WHERE receiver_id=? AND message_read=?";
    $requestCount = $database_connect->prepare($queryCount);
    $requestCount->execute(array($user_id, 0));
    $responseCount = $requestCount->fetchObject();

    if($responseCount->user_messages_exist != 0) {
        $query = "SELECT * FROM messages_yambi WHERE receiver_id=? AND message_read=?";
        $request = $database_connect->prepare($query);
        $request->execute(array($user_id, 0));
        while($responseFetch = $request->fetchObject()) {
            array_push($response_messages, $responseFetch);
        }
    }

    $response['messages'] = $response_messages;
    $response['success'] = '1';
    echo json_encode($response);

?>