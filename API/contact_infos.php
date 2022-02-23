<?php

     require_once("../config/dbconnect.functions.php");
     include '../config/functions.functions.php';

     header("Access-Control-Allow-Origin: *");
     $rest_json = file_get_contents("php://input");
     $_POST = json_decode($rest_json, true);
     $response = array();
     $contacts_data = array();
     $followers_data = array();

     $user_id = trim(strip_tags($_POST["user"]));
     $user_connected = trim(strip_tags($_POST["user_connected"]));

     $user_followers_count = user_followers_count($user_id);
     $user_following_count = user_following_count($user_id);
     // $user_blogs_count = user_blogs_count($user_id);
     // $user_posts_count = user_posts_count($user_id);
     $user_data = user_data_id($user_id);
     $user_contacts = render_contacts($user_id);
     $user_followers = render_followers_all($user_id);

     foreach($user_contacts as $contact) {
          array_push($contacts_data, user_data_id($contact->user_2));
     }

     foreach($user_followers as $follower) {
          array_push($followers_data, user_data_id($follower->user_1));
     }

     $response['user_following_count'] = $user_following_count;
     $response['user_followers_count'] = $user_followers_count;
     // $response['user_blogs_count'] = $user_blogs_count;
     // $response['user_posts_count'] = $user_posts_count;
     $response['user_contacts'] = $contacts_data;
     $response['user_followers'] = $followers_data;
     $response['user_data'] = $user_data;
     $response['is_followed'] = contact_exists($user_connected, $user_id);
     echo json_encode($response);

?>