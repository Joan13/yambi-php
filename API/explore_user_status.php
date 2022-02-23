<?php

     require_once("../config/dbconnect.functions.php");
     include '../config/functions.functions.php';

     header("Access-Control-Allow-Origin: *");
     $rest_json = file_get_contents("php://input");
     $_POST = json_decode($rest_json, true);
     $response = array();
     $global_data = array();
     $likes_count_array = array();
     $comments_count_array = array();

     $user_id = trim(strip_tags($_POST["user_id"]));
          
     $sub_data = array();

     if (status_user_exists($user_id) != 0) {
          $status_user = status_user($user_id);

          foreach ($status_user as $status) {
               array_push($global_data, $status);

               $likes_count_query = "SELECT status_id, COUNT(*) AS count_likes_status FROM likes_status WHERE status_id=?";
               $likes_count_request = $database_connect->prepare($likes_count_query);
               $likes_count_request->execute(array($status->status_id));
               $likes_count_response = $likes_count_request->fetchObject();

               $array1 = array(
                    "status_id" => $status->status_id,
                    "likes_count" => $likes_count_response->count_likes_status
               );
               array_push($likes_count_array, $array1);


               $comments_count_query = "SELECT status_id, COUNT(*) AS count_comments_status FROM comments_status WHERE status_id=?";
               $comments_count_request = $database_connect->prepare($comments_count_query);
               $comments_count_request->execute(array($status->status_id));
               $comments_count_response = $comments_count_request->fetchObject();

               $array = array(
                    "status_id" => $status->status_id,
                    "comments_count" => $comments_count_response->count_comments_status
               );
               array_push($comments_count_array, $array);
          }
     }

     $response['data'] = $global_data;
     $response['likes_array_count'] = $likes_count_array;
     $response['comments_array_count'] = $comments_count_array;
     echo json_encode($response);

?>