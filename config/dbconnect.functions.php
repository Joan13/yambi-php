<?php

	$dbhost = 'localhost';
	$dbname = 'yb_center';
	$dbuser = 'root';
	$dbpassword = 'root';

	try {
		$database_connect = new PDO('mysql:host='.$dbhost.';dbname='.$dbname, $dbuser, $dbpassword, 
			array(
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4', 
				PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
			));
	}
	catch(Exception $database_error) {
		die('Error: Yambi was unable to access internet. Retry Later : ' . $database_error->getMessage());
	}
    
?>