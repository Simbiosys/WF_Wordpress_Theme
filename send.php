<?php
	header('Content-Type: application/json');
	
	require_once('../../../wp-config.php'); 
	include_once('../../../wp-includes/pluggable.php');
	
	$name = "";
	$email = "";
	$telephone = "";
	$subject = "";
	$message = "";
	
	if (isset($_POST["name"]))
		$name = $_POST["name"];
		
	if (isset($_POST["email"]))
		$email = $_POST["email"];
		
	if (isset($_POST["telephone"]))
		$telephone = $_POST["telephone"];
		
	if (isset($_POST["subject"]))
		$subject = $_POST["subject"];
		
	if (isset($_POST["message"]))
		$message = $_POST["message"];
		
	if (empty($name) || empty($email) || empty($subject) || empty($message)) {
		$success = FALSE;
	}
	else {
		$headers = "From: $name <$email>\r\n";
		$success = wp_mail('juan.castro@simbiosys.es', $subject, $message, $headers);
	}
	
	echo json_encode(array (
		"success" => $success
	));