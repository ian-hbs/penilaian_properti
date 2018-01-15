<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php";
	include_once "../../libraries/DML.php";	
	include_once "../../libraries/global_obj.php";
	include_once "../..//helpers/mix_helper.php";
	include_once "../../helpers/date_helper.php";
		
	//instantiate objects	
	$global = new global_obj($db);
	$uc = new user_controller($db);

	$uc->check_access();
	
	$DML = new DML('log_kesimpulan_rekomendasi',$db);

	$id_penugasan = $_POST['id_penugasan'];	
	$id_kesimpulan_rekomendasi = $_POST['id_kesimpulan_rekomendasi'];
	$no_urut = $_POST['no_urut'];
	$jenis_perusahaan_penunjuk = $_POST['jenis_perusahaan_penunjuk'];
	$menu_id = $_POST['menu_id'];
  	$fn = $_POST['fn'];
  	$kunci_pencarian = $_POST['kunci_pencarian'];

  	$ip = get_ip();

  	$db->BeginTrans();

  	//update field status='N' in log_kesimpulan_rekomendasi according to id_kesimpulan_rekomendasi
	$arr_data = array('status'=>'N');
	$cond = "fk_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."'";
	$result = $DML->update($arr_data,$cond);
	if(!$result)
	{
		$db->RollBackTrans();
		die('failed');
	}	
	// ====== //

  	//update field status='Y' in log_kesimpulan_rekomendasi according to id_kesimpulan_rekomendasi and no_urut
	$arr_data = array('status'=>'Y');
	$cond = "fk_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."' AND no_urut='".$no_urut."'";
	$result = $DML->update($arr_data,$cond);
	if(!$result)
	{
		$db->RollBackTrans();
		die('failed');
	}	
	// ====== //

	$activity = "mengganti data aktif (fk_kesimpulan_rekomendasi=".$id_kesimpulan_rekomendasi.";no_urut=".$no_urut.")";
	$global->insert_logs($activity,$ip);

	$db->CommitTrans();

	$addAccess = $uc->check_priviledge('add',$menu_id);
    $editAccess = $uc->check_priviledge('update',$menu_id);
    $deleteAccess = $uc->check_priviledge('delete',$menu_id);

	include_once "form_content.php";

	echo "|$*{()}*$|";

	include_once "list_sql.php";

	if($kunci_pencarian!='')
  		$list_sql .= " WHERE (a.no_penilaian LIKE '%".$kunci_pencarian."%' OR a.id_penugasan  LIKE '%".$kunci_pencarian."%' OR b.nama LIKE '%".$kunci_pencarian."%')";
  	else
  		$list_sql .= " ORDER BY id_penugasan DESC LIMIT 0,10";
  	
    $list_of_data = $db->Execute($list_sql);

    if (!$list_of_data)
        print $db->ErrorMsg();

	 include_once "list_of_data.php";
?>