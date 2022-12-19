<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/functions.functions.php';

    header("Access-Control-Allow-Origin: *");
    header("Content-type:application/json");
    $response = array();

    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp');
    if(isset($_POST['submit'])) {

        $phone_number = trim(strip_tags($_POST["phone_number"]));

        if (!empty($_FILES['image'])) {
            $path = "../images/profile_pictures/";
            $img = $_FILES['image']['name'];
            $tmp = getimagesize($_FILES['image']['tmp_name']);
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

            $name = rand(0, 999999) . "" . rand(0, 999999) . "" . rand(0, 999999) . "" . date('d') . "" . date('m') . "" . date('Y') . "" . date('H') . "" . date('i');
            $final_name_image = "yambi".$phone_number."".$name.".".$ext;

            if (in_array($ext, $valid_extensions)) {
                $path = $path.strtolower($final_name_image);

                if(move_uploaded_file($_FILES['image']['tmp_name'], $path)) {

                    $query_update_profile = "UPDATE yb_table_users SET user_profile=? WHERE phone_number=?";
                    $request_update_profile = $database_connect->prepare($query_update_profile);
                    $request_update_profile->execute(array($final_name_image, $phone_number));

                    $user_data = user_data($phone_number);
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
?>

