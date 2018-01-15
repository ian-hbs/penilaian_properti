<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php"; 
	include_once "../../libraries/DML.php";
	include_once "../../libraries/global_obj.php";	
	include_once "../../helpers/mix_helper.php";

	$DML = new DML('properti',$db);	
	$global = new global_obj($db);
	$uc = new user_controller($db);

	$uc->check_access();
		
	$fn = $_POST['fn'];
	$id_penugasan = $_POST['fk_penugasan'];
	$perancang = $global->real_escape_string($_POST['perancang_peta_lokasi']);
	$skala = $global->real_escape_string($_POST['skala_peta_lokasi']);

	$ip = get_ip();

	$arr_data = array('perancang_peta_lokasi'=>$perancang,'skala_peta_lokasi'=>$skala);
	$cond = "fk_penugasan='".$id_penugasan."'";
	$result = $DML->update($arr_data,$cond);
	
	if(!$result)
		die('failed');

	$activity = "merubah data pada tabel properti (fk_penugasan=".$id_penugasan.")";
	$global->insert_logs($activity,$ip);

	include_once "designer_form_content.php";

?>