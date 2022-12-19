<?php

    require_once("dbconnect.functions.php");

    function user_data($phone_number) {
        global $database_connect;

        $queryUserData = "SELECT * FROM yb_table_users WHERE phone_number=?";
        $requestUserData = $database_connect->prepare($queryUserData);
        $requestUserData->execute(array($phone_number));
        $responseUserData = $requestUserData->fetchObject();

        return $responseUserData;
    }

    function user_exists($username, $password) {
        global $database_connect;

        $queryCountUser = "SELECT username, user_password, COUNT(*) AS count_user_exists FROM yambi_users WHERE username=? AND user_password=?";
        $requestCountUser = $database_connect->prepare($queryCountUser);
        $requestCountUser->execute(array($username, $password));
        $responseCountUser = $requestCountUser->fetchObject();

        return $responseCountUser->count_user_exists;
    }

    function user_data1($username, $password) {
        global $database_connect;

        $queryUserData = "SELECT * FROM yambi_users WHERE username=? AND user_password=?";
        $requestUserData = $database_connect->prepare($queryUserData);
        $requestUserData->execute(array($username, $password));
        $responseUserData = $requestUserData->fetchObject();

        return $responseUserData;
    }

    function user_data_id($user_id) {
        global $database_connect;

        $queryUserData = "SELECT * FROM yambi_users WHERE user_id=?";
        $requestUserData = $database_connect->prepare($queryUserData);
        $requestUserData->execute(array($user_id));
        $responseUserData = $requestUserData->fetchObject();

        return $responseUserData;
    }

    function user_data_username($username) {
        global $database_connect;

        $queryUserData = "SELECT * FROM yambi_users WHERE username=?";
        $requestUserData = $database_connect->prepare($queryUserData);
        $requestUserData->execute(array($username));
        $responseUserData = $requestUserData->fetchObject();

        return $responseUserData;
    }

    function insert_user($first_name, $last_name, $other_name, $user_names, $gender, $username, $profile_picture, $phone, $code, $password, $account_privacy, $account_valid) {
        global $database_connect;

        $queryInsertUser = "INSERT INTO yambi_users(first_name, last_name, other_name, user_names, gender, username, profile_picture, phone_number, number_code, user_password, date_creation, account_privacy, account_valid)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)";
        $requestInsertUser = $database_connect->prepare($queryInsertUser);
        $requestInsertUser->execute(array($first_name, $last_name, $other_name, $user_names, $gender, $username, $profile_picture, $phone, $code, $password, $account_privacy, $account_valid));
    }

    function update_user($first_name, $last_name, $other_name, $user_names, $gender, $username, $phone_number, $code, $account_privacy, $account_valid, $password, $user_name) {
        global $database_connect;

        $queryUpdateUser = "UPDATE yambi_users SET first_name=?, last_name=?, other_name=?, user_names=?, gender=?, username=?, phone_number=?, number_code=?, account_privacy=?, account_valid=?, user_password=? WHERE username=?";
        $requestUpdateUser = $database_connect->prepare($queryUpdateUser);
        $requestUpdateUser->execute(array(
            $first_name, 
            $last_name, 
            $other_name, 
            $user_names, 
            $gender, 
            $username,
            $phone_number,
            $code,
            $account_privacy, 
            $account_valid,
            $password,
            $user_name
        ));
    }

    function chat_exists($sender_id, $receiver_id) {

        global $database_connect;
        $query = "SELECT sender_id, receiver_id, COUNT(*) AS count_chats FROM chats WHERE sender_id=? AND receiver_id=?";
        $request = $database_connect->prepare($query);
        $request->execute(array($sender_id, $receiver_id));
        $response = $request->fetchObject();

        $query1 = "SELECT sender_id, receiver_id, COUNT(*) AS count_chats1 FROM chats WHERE sender_id=? AND receiver_id=?";
        $request1 = $database_connect->prepare($query1);
        $request1->execute(array($receiver_id, $sender_id));
        $response1 = $request1->fetchObject();

        return $response->count_chats + $response1->count_chats1;
    }

    function chat_id($sender_id, $receiver_id) {

        global $database_connect;
        // $query = "SELECT sender_id, receiver_id, COUNT(*) AS count_chats FROM chats WHERE sender_id=? AND receiver_id=?";
        // $request = $database_connect->prepare($query);
        // $request->execute(array($sender_id, $receiver_id));
        // $response = $request->fetchObject();

        // if($response->count_chats != 0) {
            $query = "SELECT * FROM chats WHERE sender_id=? AND receiver_id=? OR sender_id=? AND receiver_id=?";
            $request = $database_connect->prepare($query);
            $request->execute(array($sender_id, $receiver_id, $receiver_id, $sender_id));
            $response = $request->fetchObject();

            return $response->chat_id;
            // }
        // }
    }

    function create_chat($sender_id, $receiver_id, $chat_name_sender, $chat_name_receiver) {

        global $database_connect;
        $query = "INSERT INTO chats(sender_id, receiver_id, chat_name_sender, chat_name_receiver, chat_content, date_creation) VALUES(?, ?, ?, ?, ?, NOW())";
        $request = $database_connect->prepare($query);
        $request->execute(array($sender_id, $receiver_id, $chat_name_sender, $chat_name_receiver, 1));
    }

    function contact_exists($user_1, $user_2) {

        global $database_connect;
        $query = "SELECT user_1, user_2, COUNT(*) AS count_contacts FROM yambi_contacts WHERE user_1=? AND user_2=?";
        $request = $database_connect->prepare($query);
        $request->execute(array($user_1, $user_2));
        $response = $request->fetchObject();

        return $response->count_contacts;
    }

    function create_contact($user_1, $user_2) {

        global $database_connect;
        $query = "INSERT INTO yambi_contacts(user_1, user_2, privacy_chat, privacy_status, privacy_posts, date_creation) VALUES(?, ?, ?, ?, ?, NOW())";
        $request = $database_connect->prepare($query);
        $request->execute(array($user_1, $user_2, 0, 0, 0));
    }

    function render_contacts($user_id) {

        global $database_connect;
        $returnResponse = array();

        $queryCount = "SELECT user_1, COUNT(*) AS count_contacts FROM yambi_contacts WHERE user_1=?";
        $requestCount = $database_connect->prepare($queryCount);
        $requestCount->execute(array($user_id));
        $responseCount = $requestCount->fetchObject();

        if($responseCount->count_contacts != 0) {
            $query = "SELECT * FROM yambi_contacts WHERE user_1=?";
            $request = $database_connect->prepare($query);
            $request->execute(array($user_id));
            while($response = $request->fetchObject()) {
                array_push($returnResponse, $response);
            }
        }

        return $returnResponse;
    }

    function render_followers_all($user_id) {

        global $database_connect;
        $returnResponse = array();

        $queryCount = "SELECT user_2, COUNT(*) AS count_contacts FROM yambi_contacts WHERE user_2=?";
        $requestCount = $database_connect->prepare($queryCount);
        $requestCount->execute(array($user_id));
        $responseCount = $requestCount->fetchObject();

        if($responseCount->count_contacts != 0) {
            $query = "SELECT * FROM yambi_contacts WHERE user_2=?";
            $request = $database_connect->prepare($query);
            $request->execute(array($user_id));
            while($response = $request->fetchObject()) {
                array_push($returnResponse, $response);
            }
        }

        return $returnResponse;
    }

    function render_followers($user_id) {

        global $database_connect;
        $returnResponse = array();

        $queryCount = "SELECT user_2, COUNT(*) AS count_contacts FROM yambi_contacts WHERE user_2=?";
        $requestCount = $database_connect->prepare($queryCount);
        $requestCount->execute(array($user_id));
        $responseCount = $requestCount->fetchObject();

        if($responseCount->count_contacts != 0) {
            $query = "SELECT * FROM yambi_contacts WHERE user_2=?";
            $request = $database_connect->prepare($query);
            $request->execute(array($user_id));
            while($response = $request->fetchObject()) {

                if (contact_exists($user_id, $response->user_1) != 1) {
                    $user_data = user_data_id($response->user_1);
                    $response_data = array();

                    $response_data['user_id'] = $user_data->user_id;
                    $response_data['first_name'] = $user_data->first_name;
                    $response_data['last_name'] = $user_data->last_name;
                    $response_data['other_name'] = $user_data->other_name;
                    $response_data['gender'] = $user_data->gender;
                    $response_data['user_names'] = $user_data->user_names;
                    $response_data['username'] = $user_data->username;
                    $response_data['profile_picture'] = $user_data->profile_picture;
                    $response_data['phone_number'] = $user_data->phone_number;
                    $response_data['account_valid'] = $user_data->account_valid;
                    $response_data['username'] = $user_data->username;
                    $response_data['account_privacy'] = $user_data->account_privacy;
                    $response_data['number_code'] = $user_data->number_code;
                    $response_data['date_creation'] = $user_data->date_creation;
                    $response_data['date_creation_contact'] = $response->date_creation;

                    array_push($returnResponse, $response_data);
                }
                
            }
        }

        return $returnResponse;
    }

    function user_followers_count($user_id) {

        global $database_connect;

        $query = "SELECT user_2, COUNT(*) AS count_user_followers FROM yambi_contacts WHERE user_2=?";
        $request = $database_connect->prepare($query);
        $request->execute(array($user_id));
        $response = $request->fetchObject();

        return $response->count_user_followers;
    }

    function user_following_count($user_id) {

        global $database_connect;

        $query = "SELECT user_1, COUNT(*) AS count_user_following FROM yambi_contacts WHERE user_1=?";
        $request = $database_connect->prepare($query);
        $request->execute(array($user_id));
        $response = $request->fetchObject();

        return $response->count_user_following;
    }

    function user_blogs_count($user_id) {

        global $database_connect;

        $query = "SELECT user_id, COUNT(*) AS count_user_blogs FROM blogs_yambi WHERE user_id=?";
        $request = $database_connect->prepare($query);
        $request->execute(array($user_id));
        $response = $request->fetchObject();

        return $response->count_user_blogs;
    }

    function user_posts_count($user_id) {

        global $database_connect;

        $query = "SELECT user_id, COUNT(*) AS count_user_posts FROM posts_yambi WHERE user_id=?";
        $request = $database_connect->prepare($query);
        $request->execute(array($user_id));
        $response = $request->fetchObject();

        return $response->count_user_posts;
    }

    function status_user($user_id) {

        global $database_connect;
        $return_response = array();

        $query = "SELECT user_id, COUNT(*) AS status_exists FROM status_yambi WHERE user_id=?";
        $request = $database_connect->prepare($query);
        $request->execute(array($user_id));
        $response = $request->fetchObject();

        if($response->status_exists != 0) {
            $querys = "SELECT * FROM status_yambi WHERE user_id=? ORDER BY status_id DESC";
            $requests = $database_connect->prepare($querys);
            $requests->execute(array($user_id));
            while($responses = $requests->fetchObject()) {
                array_push($return_response, $responses);
            }

            return $return_response;
        }
    }

    function status_user_exists($user_id) {

        global $database_connect;

        $query = "SELECT user_id, COUNT(*) AS status_exists FROM status_yambi WHERE user_id=?";
        $request = $database_connect->prepare($query);
        $request->execute(array($user_id));
        $response = $request->fetchObject();

        return $response->status_exists;
    }

?>