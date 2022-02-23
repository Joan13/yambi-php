<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/functions.functions.php';

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);
    $response = array();
    $contacts_data = array();

    $username = trim(strip_tags($_POST["username"]));
    $password = trim(strip_tags(sha1($_POST["password"])));

    if (user_exists($username, $password) != 0) {

        $user_data = user_data($username, $password);
        $user_contacts = render_contacts($user_data->user_id);

        foreach($user_contacts as $contact) {
            array_push($contacts_data, user_data_id($contact->user_2));
        }

        $response['success'] = '1';
        $response['contacts'] = $contacts_data;
        $response['assemble'] = $user_data;
        echo json_encode($response);
    }
    else {

        $response['success'] = '0';
        echo json_encode($response);
    }

?>