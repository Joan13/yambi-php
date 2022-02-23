<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/functions.functions.php';

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);
    $response = array();

    // $first_name = "Joan";//trim(strip_tags($_POST["first_name"]));
    // $last_name = "Migani";//trim(strip_tags($_POST["last_name"]));
    // $other_name = "";//trim(strip_tags($_POST["other_name"]));
    // $gender = "0";//trim(strip_tags($_POST["gender"]));
    // $phone_number = "";//trim(strip_tags($_POST["phone_number"]));
    // $old_password = "";//trim(strip_tags(sha1($_POST["old_password"])));
    // $password = "";//trim(strip_tags(sha1($_POST["password"])));
    // $user_name = "@joan.migani";//trim(strip_tags($_POST["username"]));
    // $account_privacy = "1";//trim(strip_tags($_POST["account_privacy"]));

    // $old_password_unhashed = "";//trim(strip_tags($_POST["old_password"]));
    // $password_unhashed = "";//trim(strip_tags($_POST["password"]));

    $first_name = trim(strip_tags($_POST["first_name"]));
    $last_name = trim(strip_tags($_POST["last_name"]));
    $other_name = trim(strip_tags($_POST["other_name"]));
    $gender = trim(strip_tags($_POST["gender"]));
    $phone_number = trim(strip_tags($_POST["phone_number"]));
    $old_password = trim(strip_tags(sha1($_POST["old_password"])));
    $password = trim(strip_tags(sha1($_POST["password"])));
    $user_name = trim(strip_tags($_POST["username"]));
    $usernamee = trim(strip_tags($_POST["usernamee"]));
    $account_privacy = trim(strip_tags($_POST["account_privacy"]));

    $old_password_unhashed = trim(strip_tags($_POST["old_password"]));
    $password_unhashed = trim(strip_tags($_POST["password"]));

    $user_names = $first_name." ".$last_name;
    $username = $first_name.".".$last_name;

    $special_chars = array('À','Á','Â','Ã','Ä','Å','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ò','Ó','Ô','Õ','Ö','Ù','Ú','Û','Ü','Ý','à','á','â','ã','ä','å','ç','è','é','ê','ë','ì','í','î','ï','ð','ò','ó','ô','õ','ö','ù','ú','û','ü','ý','ÿ', "'", "?", "!", ",", ";", "\"", "{", "}", "<", ">", "/", ":", " ", "-", "=", "+", ")", "(", "[", "]", "\\", "*", "&", "^", "%", "$", "#", "@", "!", "|", "`", "~");
    $normal_chars = array('A','A','A','A','A','A','C','E','E','E','E','I','I','I','I','O','O','O','O','O','U','U','U','U','Y','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','o','o','o','o','o','u','u','u','u','y','y', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''); 

    // $username = str_replace($special_chars, $normal_chars, $username);
    // $username = strtolower("@".$username);

    $usernamee = str_replace($special_chars, $normal_chars, $usernamee);
    $usernamee = strtolower("@".$usernamee);

    $code = "";
    
    if ($phone_number != "") {
        $code = rand(100000, 999999);
    }

    $user_data = user_data_username($user_name);
    $new_password = $user_data->user_password;

    if($old_password_unhashed == "" && $password_unhashed == "") {
        $response['message'] = '0';
    }
    else {
        if ($old_password == $new_password) {
            $new_password = $password;

            $response['message'] = '1';
        }
        else {
            $response['message'] = strlen($old_password);
        }
    }

    update_user($first_name, $last_name, $other_name, $user_names, $gender, $usernamee, $phone_number, $code, $account_privacy, 1, $new_password, $user_name);

    $user_data = user_data_username($usernamee);

    $response['success'] = '1';
    $response['assemble'] = $user_data;
    echo json_encode($response);

?>