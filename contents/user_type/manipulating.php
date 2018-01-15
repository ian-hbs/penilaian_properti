<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php"; 
	include_once "../../libraries/DML.php";
	include_once "../..//helpers/mix_helper.php";
	include_once "../../libraries/global_obj.php";
	include_once "list_sql.php";

	$DML = new DML('user_types',$db);
	$global = new global_obj($db);
	$uc = new user_controller($db);
		
	$uc->check_access();

	$act = $_POST['act'];		
	$fn = $_POST['fn'];
	$menu_id = $_POST['menu_id'];
	$ip = get_ip();

	$arr_data=array();

	if($act=='add' || $act=='edit')
	{
		$arr_field = array('name');
		
		foreach($_POST as $key => $val)
		{
			if(in_array($key,$arr_field))
				$arr_data[$key]=$global->real_escape_string($val);
		}
	}

	if($act=='add')
	{
		$id = $global->get_incrementID('user_types','type_id');
		$arr_data['type_id'] = $id;
		$result = $DML->save($arr_data);
				
		if(!$result)
			die('failed');

		$activity = "menambah data ke table user_types (type_id=".$arr_data['type_id'].")";
		$global->insert_logs($activity,$ip);

	}
	else if($act=='edit')
	{
		$id = $_POST['id'];
		$cond = "type_id='".$id."'";
		$result = $DML->update($arr_data,$cond);		
		
		if(!$result)
			die('failed');

		$activity = "merubah data pada table user_types (type_id=".$id.")";
		$global->insert_logs($activity,$ip);
	}
	else if($act=='delete')
	{
		$id=$_POST['id'];
		$cond = "type_id='".$id."'";
		$result = $DML->delete($cond);
		
		if(!$result)
			die('failed');

		$activity = "menghapus data dari table user_types (type_id=".$id.")";
		$global->insert_logs($activity,$ip);
	}	
    
    $readAccess = $uc->check_priviledge('read',$menu_id);
    $addAccess = $uc->check_priviledge('add',$menu_id);
    $editAccess = $uc->check_priviledge('update',$menu_id);
    $deleteAccess = $uc->check_priviledge('delete',$menu_id);

    //fetching data to generate list of data
    $list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();

	include_once "list_of_data.php";
?>