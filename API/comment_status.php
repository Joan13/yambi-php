<?php

require_once("../config/dbconnect.functions.php");
include '../config/functions.functions.php';

header("Access-Control-Allow-Origin: *");
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);
$response = array();

$user_id = trim(strip_tags($_POST["user_id"]));
$status_id = trim(strip_tags($_POST["status_id"]));
$main_comment_text = trim(strip_tags($_POST["main_comment_status"]));

$query = "INSERT INTO comments_status(user_id, status_id, main_comment_text, date_creation) VALUES(?, ?, ?, NOW())";
$request = $database_connect->prepare($query);
$request->execute(array($user_id, $status_id, $main_comment_text));

$response['success'] = '1';
echo json_encode($response);

?>