<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/functions.functions.php';

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);
    $response = array();

    $user_1 = trim(strip_tags($_POST["user_1"]));
    $user_2 = trim(strip_tags($_POST["user_2"]));

    if ($user_1 == $user_2) {

        $response['success'] = '0';
        echo json_encode($response);

    } else {

        if(contact_exists($user_1, $user_2) == 0) {

            create_contact($user_1, $user_2);
   
            $user_contacts = render_contacts($user_1);
            $user_followers_count = user_followers_count($user_2);
   
            $response['success'] = '1';
            $response['contacts'] = $user_contacts;
            $response['user_followers_count'] = $user_followers_count;
            echo json_encode($response);
       } 
       else {
   
             $response['success'] = '0';
             echo json_encode($response);
       }
    }

?>