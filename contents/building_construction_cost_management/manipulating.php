<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php"; 
	include_once "../../libraries/DML.php";    
	include_once "../../helpers/mix_helper.php";
	include_once "../../libraries/global_obj.php";

	$DML1 = new DML('perhitunganbkb_master',$db);
	$DML2 = new DML('perhitunganbkb_komponen',$db);
	$DML3 = new DML('perhitunganbkb_hasil',$db);
	$global = new global_obj($db);
	$uc = new user_controller($db);

	$uc->check_access();

	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$menu_id = $_POST['menu_id'];	
	$kunci_pencarian = $_POST['kunci_pencarian'];
	
	$ip = get_ip();

	if($act=='delete')
	{
		$id=$_POST['id'];

		$db->BeginTrans();

		$cond = "id_perhitunganbkb_master='".$id."'";
		$result = $DML1->delete($cond);
		
		if(!$result)
		{
			$db->RollBackTrans();
			die('failed');
		}

		$cond = "fk_perhitunganbkb_master='".$id."'";
		$result = $DML2->delete($cond);

		if(!$result)
		{
			$db->RollBackTrans();
			die('failed');
		}

		$cond = "fk_perhitunganbkb_master='".$id."'";
		$result = $DML3->delete($cond);

		if(!$result)
		{
			$db->RollBackTrans();
			die('failed');
		}				

		$activity = "menghapus data dari table perhitunganbkb_master,perhitunganbkb_komponen,perhitunganbkb_hasil (id_perhitunganbkb_master=".$id.")";
		$global->insert_logs($activity,$ip);

		$db->CommitTrans();

	}
	
	$readAccess = $uc->check_priviledge('read',$menu_id);
    $addAccess = $uc->check_priviledge('add',$menu_id);
    $editAccess = $uc->check_priviledge('update',$menu_id);
    $deleteAccess = $uc->check_priviledge('delete',$menu_id);
    
	include_once "list_sql.php";

  	$list_sql .= " WHERE (a.no_bct LIKE '%".$kunci_pencarian."%' OR a.alamat  LIKE '%".$kunci_pencarian."%')";

    $list_of_data = $db->Execute($list_sql);

    if (!$list_of_data)
        print $db->ErrorMsg();

  	include_once "list_of_data.php";
?>