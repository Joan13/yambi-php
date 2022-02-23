<?php

     require_once("../config/dbconnect.functions.php");
     include '../config/functions.functions.php';

     header("Access-Control-Allow-Origin: *");
     $rest_json = file_get_contents("php://input");
     $_POST = json_decode($rest_json, true);
     $response = array();
     $likes_count_array = array();
     $comments_count_array = array();
     $comments = array();
     $likes = array();
     $users = array();

     $status_id = trim(strip_tags($_POST["status_id"]));

     $likes_count_query = "SELECT status_id, COUNT(*) AS count_likes_status FROM likes_status WHERE status_id=?";
     $likes_count_request = $database_connect->prepare($likes_count_query);
     $likes_count_request->execute(array($status_id));
     $likes_count_response = $likes_count_request->fetchObject();

     $comments_count_query = "SELECT status_id, COUNT(*) AS count_comments_status FROM comments_status WHERE status_id=?";
     $comments_count_request = $database_connect->prepare($comments_count_query);
     $comments_count_request->execute(array($status_id));
     $comments_count_response = $comments_count_request->fetchObject();

     if($likes_count_response->count_likes_status != 0) {
          $query_likes = "SELECT * FROM likes_status WHERE status_id=?";
          $request_likes = $database_connect->prepare($query_likes);
          $request_likes->execute(array($status_id));
          while($response_likes = $request_likes->fetchObject()) {
               array_push($likes, $response_likes);
               array_push($users, user_data_id($response_likes->user_id));
          }
     }

     if($comments_count_response->count_comments_status != 0) {
          $query_comments = "SELECT * FROM comments_status WHERE status_id=?";
          $request_comments = $database_connect->prepare($query_comments);
          $request_comments->execute(array($status_id));
          while($response_comments = $request_comments->fetchObject()) {
               array_push($comments, $response_comments);
               array_push($users, user_data_id($response_comments->user_id));
          }
     }

     $response['comments'] = $comments;
     $response['likes'] = $likes;
     $response['users'] = $users;
     $response['likes_array_count'] = $likes_count_response->count_likes_status;
     $response['comments_array_count'] = $comments_count_response->count_comments_status;
     echo json_encode($response);

?>