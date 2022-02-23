<?php

     require_once("../config/dbconnect.functions.php");
     include '../config/functions.functions.php';

     header("Access-Control-Allow-Origin: *");
     $rest_json = file_get_contents("php://input");
     $_POST = json_decode($rest_json, true);
     $response = array();
     $response_explore = array();

     $user_id = trim(strip_tags($_POST["user_id"]));

     $query = "SELECT * FROM yambi_users WHERE user_id!=? AND account_privacy=? ORDER BY RAND() LIMIT 0, 350";
     $request = $database_connect->prepare($query);
     $request->execute(array($user_id, 0));
     while($responseFetch = $request->fetchObject()) {
          if(!contact_exists($user_id, $responseFetch->user_id)) {
               array_push($response_explore, $responseFetch);
          }
     }

     $user_contacts = render_contacts($user_id);
     $user_followers_count = user_followers_count($user_id);
     $user_following_count = user_following_count($user_id);
     $response['contacts'] = $user_contacts;
     $response['explore_yambi_contacts'] = $response_explore;
     $response['user_following_count'] = $user_following_count;
     $response['user_followers_count'] = $user_followers_count;
     echo json_encode($response);

?>