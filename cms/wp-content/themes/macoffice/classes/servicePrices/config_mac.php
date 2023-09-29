<?php
error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', '1');  // a hibauzeneteket kiirja-e vagy sem
	header('Content-Type: text/html; charset=UTF8');


	$ip = 'localhost:3306';  //host
	$dbuser = 'media-admin';
	$dbpass = 'Tr1-I7ad#1n';
	$db = 'macbook_vk';

	$connect = mysqli_connect($ip, $dbuser, $dbpass, $db);

	mysqli_query($connect, 'set names utf8');

	$self_url = 'http://127.0.0.1/macoffice';
	$domain = 'macoffice';
	$slogan = 'macoffice';

	$actual_link = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; //aktualis link behuzasa

?>