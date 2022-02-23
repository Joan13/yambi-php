<?php

    require_once("../config/dbconnect.functions.php");
    include '../config/functions.functions.php';

    header("Access-Control-Allow-Origin: *");
    header("Content-type:application/json");

    if(isset($_POST['submit'])) {

        $response = array();
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp');

        $user_names = trim(strip_tags($_POST["user_names"]));
        $final_name_image = "";

        $first_name = trim(strip_tags($_POST["first_name"]));
        $last_name = trim(strip_tags($_POST["extension_domain"]));
        $gender = "3";
        $password = trim(strip_tags(sha1("Group_default_password")));
        $phone_number = "";
        $code = "";
        $username = $last_name;
        $group_members = $_POST['group_members'];
        $group_members = str_split($group_members);

        $special_chars = array('À','Á','Â','Ã','Ä','Å','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ò','Ó','Ô','Õ','Ö','Ù','Ú','Û','Ü','Ý','à','á','â','ã','ä','å','ç','è','é','ê','ë','ì','í','î','ï','ð','ò','ó','ô','õ','ö','ù','ú','û','ü','ý','ÿ', "'", "?", "!", ",", ";", "\"", "{", "}", "<", ">", "/", ":", " ", "_", "-", "=", "+", ")", "(", "[", "]", "\\", "*", "&", "^", "%", "$", "#", "@", "!", "|", "`", "~");
        $normal_chars = array('A','A','A','A','A','A','C','E','E','E','E','I','I','I','I','O','O','O','O','O','U','U','U','U','Y','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','o','o','o','o','o','u','u','u','u','y','y', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''); 

        $username = str_replace($special_chars, $normal_chars, $username);
        $username = strtolower($username . rand(0, 999));

        if (!empty($_FILES['image'])) {
            $path = "../images/group_flags/";
            $img = $_FILES['image']['name'];
            $tmp = getimagesize($_FILES['image']['tmp_name']);
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

            $name = rand(0, 999999) . "" . rand(0, 999999) . "" . rand(0, 999999) . "" . date('d') . "" . date('m') . "" . date('Y') . "" . date('H') . "" . date('i');
            
            $final_name_image = "yambi_image_@@@@_group_flag".$name.".".$ext;

            if (in_array($ext, $valid_extensions)) {
                $path = $path.strtolower($final_name_image);

                if(move_uploaded_file($_FILES['image']['tmp_name'], $path)) {
                    if (user_exists($username, $password) == 0) {

                        insert_user($first_name, $last_name, "", $user_names, $gender, $username, $final_name_image, $phone_number, $code, $password, 1, 1);
            
                        $user_data = user_data($username, $password);
                        foreach($group_members as $element) {
                            if($element == "[" || $element == "]" || $element == "," || $element == "" || $element == " ") {}
                            else {
                                if(contact_exists($user_data->user_id, $element) == 0) {
                                    create_contact($user_data->user_id, $element);
                                }
                            }
                        }

                        if(contact_exists($user_data->user_id, $first_name) == 0) {
                            create_contact($user_data->user_id, $first_name);
                        }

                        if(contact_exists($first_name, $user_data->user_id) == 0) {
                            create_contact($first_name, $user_data->user_id);
                        }
            
                        $response['success'] = '1';
                        $response['assemble'] = $user_data;
                        echo json_encode($response);
            
                    } else {
                        $response['success'] = '0';
                        echo json_encode($response);
                    }
                    
                } else {
                    $response['success'] = '2';
                    echo json_encode($response);
                }

            } else {
                $response['success'] = '3';
                echo json_encode($response);
            }
            
        } 
        else {
            if (user_exists($username, $password) == 0) {

                insert_user($first_name, $last_name, "", $user_names, $gender, $username, $final_name_image, $phone_number, $code, $password, 1, 1);
    
                $user_data = user_data($username, $password);
                foreach($group_members as $element) {
                    if($element == "[" || $element == "]" || $element == "," || $element == "" || $element == " ") {}
                    else {
                        if(contact_exists($user_data->user_id, $element) == 0) {
                            create_contact($user_data->user_id, $element);
                        }
                    }
                }

                if(contact_exists($user_data->user_id, $first_name) == 0) {
                    create_contact($user_data->user_id, $first_name);
                }

                if(contact_exists($first_name, $user_data->user_id) == 0) {
                    create_contact($first_name, $user_data->user_id);
                }
    
                $response['success'] = '1';
                $response['assemble'] = $user_data;
                echo json_encode($response);
    
            } else {
                $response['success'] = '0';
                echo json_encode($response);
            }
        }
    }

