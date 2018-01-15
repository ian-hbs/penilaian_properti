<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php"; 
	include_once "../../libraries/DML.php";
	include_once "../..//helpers/mix_helper.php";
	include_once "../../libraries/global_obj.php";
	include_once "list_sql.php";

	$DML = new DML('users',$db);
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
		$arr_field = array('type_fk','username','password','fullname','email','phone_number');
		
		foreach($_POST as $key => $val)
		{
			if(in_array($key,$arr_field))
			{
				if($key!='email' and $key!='type_fk')
					$arr_data[$key] = $global->real_escape_string($val);
				else
					$arr_data[$key] = $val;
				
				$arr_data[$key]=($key=='password'?md5($val):$val);
			}
		}
	}

	if($act=='add')
	{
		$user_id = $global->get_incrementID('users','user_id');
		$arr_data['user_id'] = $user_id;
		$arr_data['register_id'] = $global->get_registerID($_POST['type_fk']);
		$arr_data['created_by'] = $_SESSION['username'];
		$arr_data['created_time'] = date('Y-m-d H:i:s');
		$arr_data['blocked'] = (isset($_POST['blocked'])?'1':'0');

		$result = $DML->save($arr_data);
				
		if(!$result)
			die('failed');

		$activity = "menambah data ke table users (user_id=".$arr_data['user_id'].")";
		$global->insert_logs($activity,$ip);

	}
	else if($act=='edit')
	{
		$id = $_POST['id'];
		$cond = "user_id='".$id."'";
		$arr_data['modified_by'] = $_SESSION['username'];
		$arr_data['modified_time'] = date('Y-m-d H:i:s');
		$arr_data['blocked'] = (isset($_POST['blocked'])?'1':'0');

		$result = $DML->update($arr_data,$cond);
		
		if(!$result)
			die('failed');

		$activity = "merubah data pada table users (user_id=".$id.")";
		$global->insert_logs($activity,$ip);
	}
	else if($act=='delete')
	{
		$id=$_POST['id'];
		$cond = "user_id='".$id."'";
		$result = $DML->delete($cond);
		
		if(!$result)
			die('failed');

		$activity = "menghapus data dari table users (user_id=".$id.")";
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