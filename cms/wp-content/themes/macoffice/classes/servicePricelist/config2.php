<?php
error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', '0');  // a hibauzeneteket kiirja-e vagy sem
	header('Content-Type: text/html; charset=UTF8');


	$ip = 'localhost:3306';  //host
	$dbuser = 'media-admin';
	$dbpass = 'Tr1-I7ad#1n';
	$db = 'macoffice-service-db';

	$connect = mysqli_connect($ip, $dbuser, $dbpass);




?>