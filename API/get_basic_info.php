<?php

     require_once("../config/dbconnect.functions.php");
     include '../config/functions.functions.php';

     header("Access-Control-Allow-Origin: *");
     $rest_json = file_get_contents("php://input");
     $_POST = json_decode($rest_json, true);
     $response = array();

     $user_id = trim(strip_tags($_POST["user_id"]));

     $user_followers_count = user_followers_count($user_id);
     $user_following_count = user_following_count($user_id);
     $user_followers = render_followers($user_id);

     $response['user_following_count'] = $user_following_count;
     $response['user_followers_count'] = $user_followers_count;
     $response['user_followers'] = $user_followers;

     echo json_encode($response);

?>