<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php"; 
	include_once "../../libraries/DML.php";    
	include_once "../..//helpers/mix_helper.php";
	include_once "../../libraries/global_obj.php";	

	$DML = new DML('catatan_penilai',$db);
	$global = new global_obj($db);
	$uc = new user_controller($db);

	$uc->check_access();

	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$menu_id = $_POST['menu_id'];
	$kunci_pencarian = $_POST['kunci_pencarian'];
	$ip = get_ip();

	$arr_data=array();

	if($act=='add' || $act=='edit')
	{
		$arr_field = array('fk_penugasan','catatan');
		
		foreach($_POST as $key => $val)
		{
			if(in_array($key,$arr_field))				
				$arr_data[$key]=$global->real_escape_string($val);
		}
	}

	if($act=='add')
	{
		$id_catatan_penilai = $global->get_incrementID('catatan_penilai','id_catatan_penilai');
		$arr_data['id_catatan_penilai'] = $id_catatan_penilai;
		$result = $DML->save($arr_data);
				
		if(!$result)
			die('failed');

		$activity = "menambah data ke tabel catatan_penilai (id_catatan_penilai=".$arr_data['id_catatan_penilai'].")";
		$global->insert_logs($activity,$ip);

	}
	else if($act=='edit')
	{
		$id = $_POST['id'];
		$cond = "id_catatan_penilai='".$id."'";
		$result = $DML->update($arr_data,$cond);
		
		if(!$result)
			die('failed');

		$activity = "merubah data pada tabel catatan_penilai (id_catatan_penilai=".$id.")";
		$global->insert_logs($activity,$ip);
	}
	else if($act=='delete')
	{
		$id=$_POST['id'];
		$cond = "id_catatan_penilai='".$id."'";
		$result = $DML->delete($cond);
		
		if(!$result)
			die('failed');

		$activity = "menghapus data dari tabel catatan_penilai (id_catatan_penilai=".$id.")";
		$global->insert_logs($activity,$ip);
	}
  	
  	$readAccess = $uc->check_priviledge('read',$menu_id);
    $addAccess = $uc->check_priviledge('add',$menu_id);
    $editAccess = $uc->check_priviledge('update',$menu_id);
    $deleteAccess = $uc->check_priviledge('delete',$menu_id);
    
	$id_penugasan = $_POST['fk_penugasan'];	
  	$list_sql = "SELECT * FROM catatan_penilai WHERE fk_penugasan='".$id_penugasan."'";

    //fetching data to generate list of data
    $list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();

	include_once "list_of_data2.php";
	
	echo "|$*{()}*$|";	
	
	include_once "list_sql.php";

	if($kunci_pencarian!='')
  		$list_sql .= " WHERE (a.no_penilaian LIKE '%".$kunci_pencarian."%' OR a.id_penugasan  LIKE '%".$kunci_pencarian."%' OR b.nama LIKE '%".$kunci_pencarian."%')";
  	else
  		$list_sql .= "WHERE (a.status='0') ORDER BY id_penugasan DESC LIMIT 0,10";

    $list_of_data = $db->Execute($list_sql);

    if (!$list_of_data)
        print $db->ErrorMsg();
	
	   
	include_once "list_of_data1.php";
?>