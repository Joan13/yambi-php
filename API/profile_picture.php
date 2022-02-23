<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/functions.functions.php';

    header("Access-Control-Allow-Origin: *");
    header("Content-type:application/json");
    $response = array();

    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp');
    if(isset($_POST['submit'])) {

        $user_id = trim(strip_tags($_POST["user_id"]));

        if (!empty($_FILES['image'])) {
            $path = "../images/profile_pictures/";
            $img = $_FILES['image']['name'];
            $tmp = getimagesize($_FILES['image']['tmp_name']);
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

            $name = rand(0, 999999) . "" . rand(0, 999999) . "" . rand(0, 999999) . "" . date('d') . "" . date('m') . "" . date('Y') . "" . date('H') . "" . date('i');
            $final_name_image = "yambi".$user_id."".$name.".".$ext;

            if (in_array($ext, $valid_extensions)) {
                $path = $path.strtolower($final_name_image);

                if(move_uploaded_file($_FILES['image']['tmp_name'], $path)) {

                    $query_update_picture = "UPDATE yambi_users SET profile_picture=? WHERE user_id=?";
                    $request_update_picture = $database_connect->prepare($query_update_picture);
                    $request_update_picture->execute(array($final_name_image, $user_id));

                    $user_data = user_data_id($user_id);

                    $response['assemble'] = $user_data;
                    $response['success'] = '1';
                    echo json_encode($response);
                } else {
                    $response['success'] = '2';
                    echo json_encode($response);
                }

            } else {
                $response['success'] = '3';
                echo json_encode($response);
            }
            
        } else {
            $response['success'] = '0';
            echo json_encode($response);
        }
    }

