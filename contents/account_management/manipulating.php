<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php"; 
	include_once "../../libraries/DML.php";
	include_once "../..//helpers/mix_helper.php";
	include_once "../../libraries/global_obj.php";

	$DML = new DML('users',$db);
	$global = new global_obj($db);
	$uc = new user_controller($db);

	$uc->check_access();

	$fn = $_POST['fn'];
	$menu_id = $_POST['menu_id'];
	$ip = get_ip();

	$arr_data=array();
	
	$arr_field = array('username','password');
		
	foreach($_POST as $key => $val)
	{
		if(in_array($key,$arr_field))
		{			
			$arr_data[$key]=($key=='password'?md5($val):$val);
		}
	}

	$id = $_POST['id'];
	$cond = "user_id='".$id."'";	
	$arr_data['modified_by'] = $_SESSION['username'];
	$arr_data['modified_time'] = date('Y-m-d H:i:s');	

	$result = $DML->update($arr_data,$cond);
	
	if(!$result)
		die('failed');

	$activity = "merubah data pada table users (user_id=".$id.")";
	$global->insert_logs($activity,$ip);

	if(isset($_POST['username']))
	{
		$_SESSION['username']=$_POST['username'];
	}
?>