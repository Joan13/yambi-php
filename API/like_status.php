<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/functions.functions.php';

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);
    $response = array();

    $user_id = trim(strip_tags($_POST["user_id"]));
    $status_id = trim(strip_tags($_POST["status_id"]));

    $count = "SELECT user_id, status_id, COUNT(*) AS count_likes_user FROM likes_status WHERE user_id=? AND status_id=?";
    $req = $database_connect->prepare($count);
    $req->execute(array($user_id, $status_id));
    $res = $req->fetchObject();

    if ($res->count_likes_user == 0) {
        $query = "INSERT INTO likes_status(user_id, status_id, date_creation) VALUES(?, ?, NOW())";
        $request = $database_connect->prepare($query);
        $request->execute(array($user_id, $status_id));

        $response['success'] = '1';
        echo json_encode($response);

    } else {

        $response['success'] = '0';
        echo json_encode($response);
    }

?>