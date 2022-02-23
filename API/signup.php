<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/functions.functions.php';

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);
    $response = array();

    $first_name = trim(strip_tags($_POST["first_name"]));
    $last_name = trim(strip_tags($_POST["last_name"]));
    // $other_name = trim(strip_tags($_POST["other_name"]));
    $gender = trim(strip_tags($_POST["gender"]));
    $password = trim(strip_tags(sha1($_POST["password"])));
    $phone_number = "";
    $code = "";

    // $user_names = $first_name." ".$last_name." ".$other_name;
    $user_names = $first_name." ".$last_name;
    $username = $first_name.".".$last_name;

    $special_chars = array('À','Á','Â','Ã','Ä','Å','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ò','Ó','Ô','Õ','Ö','Ù','Ú','Û','Ü','Ý','à','á','â','ã','ä','å','ç','è','é','ê','ë','ì','í','î','ï','ð','ò','ó','ô','õ','ö','ù','ú','û','ü','ý','ÿ', "'", "?", "!", ",", ";", "\"", "{", "}", "<", ">", "/", ":", " ", "_", "-", "=", "+", ")", "(", "[", "]", "\\", "*", "&", "^", "%", "$", "#", "@", "!", "|", "`", "~");
    $normal_chars = array('A','A','A','A','A','A','C','E','E','E','E','I','I','I','I','O','O','O','O','O','U','U','U','U','Y','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','o','o','o','o','o','u','u','u','u','y','y', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''); 

    $username = str_replace($special_chars, $normal_chars, $username);
    $username = strtolower("@".$username);

    if (user_exists($username, $password) == 0) {

        insert_user($first_name, $last_name, "", $user_names, $gender, $username, "", $phone_number, $code, $password, 0, 1);

        $user_data = user_data($username, $password);

        $response['success'] = '1';
        $response['assemble'] = $user_data;
        echo json_encode($response);

    }
    else {

        $response['success'] = '0';
        echo json_encode($response);
        
    }

?>