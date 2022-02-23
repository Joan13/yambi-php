<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/functions.functions.php';

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);
    $response = array();
    $response_messages = array();

    $user_id = trim(strip_tags($_POST["user_id"]));

    $queryCount = "SELECT sender_id, message_read, COUNT(*) AS user_messages_read FROM messages_yambi WHERE sender_id=? AND message_read=?";
    $requestCount = $database_connect->prepare($queryCount);
    $requestCount->execute(array($user_id, 3));
    $responseCount = $requestCount->fetchObject();

    if($responseCount->user_messages_read != 0) {

        $queryRender = "SELECT * FROM messages_yambi WHERE sender_id=? AND message_read=?";
        $requestRender = $database_connect->prepare($queryRender);
        $requestRender->execute(array($user_id, 3));
        while($responseFetch = $requestRender->fetchObject()) {
            array_push($response_messages, $responseFetch);
        }

        $query = "UPDATE messages_yambi SET message_read=? WHERE sender_id=? AND message_read=?";
        $request = $database_connect->prepare($query);
        $request->execute(array(1, $user_id, 3));
    }

    $response['messages'] = $response_messages;
    $response['success'] = '1';
    echo json_encode($response);

?>