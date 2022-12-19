<?php

    require_once("../config/dbconnect.functions.php");
    // include '../config/functions.functions.php';

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);
    $response = array();
    $data = $_POST['contacts'];
    $contacts = array();

    foreach($data as $cle => $con) {

        $contact = array();

        $verify_query = "SELECT phone_number, COUNT(*) AS count_contact_exists FROM yb_table_users WHERE phone_number=?";
        $verify_request = $database_connect->prepare($verify_query);
        $verify_request->execute(array($con['phoneNumber']));
        $verify_response = $verify_request->fetchObject();

        if ($verify_response->count_contact_exists != 0) {

            $select_contact_query = "SELECT * FROM yb_table_users WHERE phone_number='$verify_response->phone_number'";
            $select_contact_request = $database_connect->prepare($select_contact_query);
            $select_contact_request->execute(array($con['phoneNumber']));
            $select_contact_response = $select_contact_request->fetchObject();

            $contact['user_id'] = $select_contact_response->user_id;
            $contact['displayName'] = $con['displayName'];
            $contact['phoneNumber'] = $select_contact_response->phone_number;
            $contact['gender'] = $select_contact_response->gender;
            $contact['birth_date'] = $select_contact_response->birth_date;
            $contact['country'] = $select_contact_response->country;
            $contact['user_profile'] = $select_contact_response->user_profile;
            $contact['profession'] = $select_contact_response->profession;
            $contact['status_information'] = $select_contact_response->status_information;
            $contact['user_password'] = $select_contact_response->user_password;
            $contact['account_privacy'] = $select_contact_response->account_privacy;
            $contact['account_valid'] = $select_contact_response->account_valid;

            array_push($contacts, $contact);
        }
    }

    $response['contacts'] = $contacts;
    echo json_encode($response);

?>