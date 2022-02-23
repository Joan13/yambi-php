<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/functions.functions.php';

    header("Access-Control-Allow-Origin: *");
    header("Content-type:application/json");
    $response = array();

    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp');
    if(isset($_POST['submit'])) {

        $sender_id = trim(strip_tags($_POST["sender_id"]));
        $receiver_id = trim(strip_tags($_POST["receiver_id"]));

        if (!empty($_FILES['image'])) {
            $path = "../images/images/";
            $img = $_FILES['image']['name'];
            $tmp = getimagesize($_FILES['image']['tmp_name']);
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

            $name = rand(0, 999999) . "" . rand(0, 999999) . "" . rand(0, 999999) . "" . date('d') . "" . date('m') . "" . date('Y') . "" . date('H') . "" . date('i');
            
            $final_name_image = "yambi_image_@@@@_sentimage".$name.".".$ext;

            if (in_array($ext, $valid_extensions)) {
                $path = $path.strtolower($final_name_image);

                if(move_uploaded_file($_FILES['image']['tmp_name'], $path)) {

                    $response['success'] = '1';
                    $response['message_text'] = $final_name_image;
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

