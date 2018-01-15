<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php"; 
	include_once "../../libraries/DML.php";    
	include_once "../..//helpers/mix_helper.php";
	include_once "../../libraries/global_obj.php";	

	$DML1 = new DML('objek_pembanding',$db);
	$DML2 = new DML('perhitungan_tanah_pembanding',$db);
	$DML3 = new DML('adjustment_tanah_pembanding',$db);
	$DML4 = new DML('perhitungan_bangunan_pembanding',$db);
	$DML5 = new DML('adjustment_bangunan_pembanding',$db);

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
		$arr_field = array('fk_penugasan','no_urut','alamat','pemberi_data','status','no_tlp',
						   'fk_jenis_objek','jarak_dari_properti');
		
		foreach($_POST as $key => $val)
		{
			if(in_array($key,$arr_field))
				$arr_data[$key]=$global->real_escape_string($val);
		}
	}

	$db->BeginTrans();

	if($act=='add')
	{
		$id_objek_pembanding = $global->get_incrementID('objek_pembanding','id_objek_pembanding');
		$arr_data['id_objek_pembanding'] = $id_objek_pembanding;
		$result = $DML1->save($arr_data);
				
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		$activity = "menambah data ke tabel objek_pembanding (id_objek_pembanding=".$arr_data['id_objek_pembanding'].")";
		$global->insert_logs($activity,$ip);

	}
	else if($act=='edit')
	{
		$id = $_POST['id'];
		$cond = "id_objek_pembanding='".$id."'";
		$result = $DML1->update($arr_data,$cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		$activity = "merubah data pada tabel objek_pembanding (id_objek_pembanding=".$id.")";
		$global->insert_logs($activity,$ip);
	}
	else if($act=='delete')
	{
		$id=$_POST['id'];
		$fk_penugasan = $_POST['fk_penugasan'];

		$cond = "id_objek_pembanding='".$id."'";
		$result = $DML1->delete($cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		//delete data perhitungan_tanah_pembanding dan adjustment_tanah_pembanding
		$sql = "SELECT id_perhitungan_tanah_pembanding FROM perhitungan_tanah_pembanding WHERE(fk_penugasan='".$fk_penugasan."') AND (fk_objek_pembanding='".$id."')";
		$data = $DML2->fetchData($sql);
		foreach($data as $row)
		{
			$cond = "fk_perhitungan_tanah_pembanding='".$row['id_perhitungan_tanah_pembanding']."'";
			$result = $DML3->delete($cond);
			if(!$result)
			{
				$db->RollbackTrans();
				die('failed1');
			}
		}
		$cond = "fk_penugasan='".$fk_penugasan."' AND fk_objek_pembanding='".$id."'";
		$result = $DML2->delete($cond);
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed2');
		}
		// ====== //

		//delete data perhitungan_bangunan_pembanding dan adjustment_bangunan_pembanding
		$sql = "SELECT id_perhitungan_bangunan_pembanding FROM perhitungan_bangunan_pembanding WHERE(fk_penugasan='".$fk_penugasan."') AND (fk_objek_pembanding='".$id."')";
		$data = $DML4->fetchData($sql);
		foreach($data as $row)
		{
			$cond = "fk_perhitungan_bangunan_pembanding='".$row['id_perhitungan_bangunan_pembanding']."'";
			$result = $DML5->delete($cond);
			if(!$result)
			{
				$db->RollbackTrans();
				die('failed3');
			}
		}
		$cond = "fk_penugasan='".$fk_penugasan."' AND fk_objek_pembanding='".$id."'";
		$result = $DML4->delete($cond);
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed4');
		}
		// ======= //

		// reconcile land_valuation //
		$reconcile_main_land_valuation = $global->reconcile_main_land_valuation($fk_penugasan);
		if($reconcile_main_land_valuation===false)
		{
			$db->RollbackTrans();
			die('failed5');
		}
		// ====== //

		//reconcile safetymargin //		
		$reconcile_safetymargin_value = $global->reconcile_safetymargin_value('1',$fk_penugasan);
		if($reconcile_safetymargin_value===false)
		{
			$db->RollbackTrans();
			die('failed6');
		}
		// ====== //



		$activity = "menghapus data dari tabel objek_pembanding (id_objek_pembanding=".$id.")";
		$global->insert_logs($activity,$ip);
	}
  	
  	$db->CommitTrans();
  	
  	$readAccess = $uc->check_priviledge('read',$menu_id);
    $addAccess = $uc->check_priviledge('add',$menu_id);
    $editAccess = $uc->check_priviledge('update',$menu_id);
    $deleteAccess = $uc->check_priviledge('delete',$menu_id);
    
	$id_penugasan = $_POST['fk_penugasan'];	
  	$list_sql = "SELECT a.id_objek_pembanding,a.fk_penugasan,a.no_urut,a.alamat,a.pemberi_data,a.jarak_dari_properti,b.jenis_objek FROM objek_pembanding as a 
                 LEFT JOIN ref_jenis_objek as b ON (a.fk_jenis_objek=b.id_jenis_objek) WHERE a.fk_penugasan='".$id_penugasan."'";    

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