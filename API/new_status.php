<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/functions.functions.php';

    header("Access-Control-Allow-Origin: *");
    header("Content-type:application/json");
    $response = array();

    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp');
    if(isset($_POST['submit'])) {

        $user_id = trim(strip_tags($_POST["user_id"]));
        $main_text_status = trim(strip_tags($_POST["main_text_status"]));
        $final_name_image = "";

        if (!empty($_FILES['image'])) {
            $path = "../images/status_flags/";
            $img = $_FILES['image']['name'];
            $tmp = getimagesize($_FILES['image']['tmp_name']);
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

            $name = rand(0, 999999) . "" . rand(0, 999999) . "" . rand(0, 999999) . "" . date('d') . "" . date('m') . "" . date('Y') . "" . date('H') . "" . date('i');
            
            $final_name_image = "yambi_image_@@@@_status_flag".$name.".".$ext;

            if (in_array($ext, $valid_extensions)) {
                $path = $path.strtolower($final_name_image);

                if(move_uploaded_file($_FILES['image']['tmp_name'], $path)) {

                    $query = "INSERT INTO status_yambi(user_id, main_text_status, media_status, date_creation) VALUES(?, ?, ?, NOW())";
                    $request = $database_connect->prepare($query);
                    $request->execute(array($user_id, $main_text_status, $final_name_image));

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

            if($main_text_status == "") {

                $response['success'] = '0';
                echo json_encode($response);

            } 
            else {

                $query = "INSERT INTO status_yambi(user_id, main_text_status, media_status, date_creation) VALUES(?, ?, ?, NOW())";
                $request = $database_connect->prepare($query);
                $request->execute(array($user_id, $main_text_status, $final_name_image));

                $response['success'] = '1';
                echo json_encode($response);
            }
        }
    }

