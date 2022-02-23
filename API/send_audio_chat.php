<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/functions.functions.php';

    header("Access-Control-Allow-Origin: *");
    header("Content-type:application/json");
    $response = array();

    $valid_extensions = array('aac');
    if(isset($_POST['submit'])) {

        // $sender_id = trim(strip_tags($_POST["sender_id"]));
        // $receiver_id = trim(strip_tags($_POST["receiver_id"]));

        if (!empty($_FILES['file'])) {
            $path = "../audios/messages/";
            $audio = $_FILES['file']['name'];
            $tmp = $_FILES['file']['tmp_name'];
            $size = $_FILES['file']['size'];
            $ext = strtolower(pathinfo($audio, PATHINFO_EXTENSION));

            $name = rand(0, 999999) . "" . rand(0, 999999) . "" . rand(0, 999999) . "" . date('d') . "" . date('m') . "" . date('Y') . "" . date('H') . "" . date('i');
            
            $final_name_audio = "yambi_audio_@@@@_sentaudio".$name.".".$ext;

            if (in_array($ext, $valid_extensions)) {
                $path = $path.strtolower($final_name_audio);

                if(move_uploaded_file($_FILES['file']['tmp_name'], $path)) {

                    $response['success'] = '1';
                    $response['audio_text'] = $final_name_audio;
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

