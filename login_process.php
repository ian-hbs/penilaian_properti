<?php
	session_start();
	include_once "config/superglobal_var.php";
	include_once "config/db_connection.php";
	include_once "libraries/user_controller.php";
	include_once "helpers/mix_helper.php";
	include_once "libraries/global_obj.php";

	$global = new global_obj($db);
	$uc = new user_controller($db);

	$username = $_POST['username'];
	$password = md5($_POST['password']);
	$ip = get_ip();
	
	$status_login = $uc->login_process($username,$password,$ip);	

	if($status_login=='success')
	{
		$activity = "login";
		$global->insert_logs($activity,$ip);

	}

	echo $status_login;

?>
