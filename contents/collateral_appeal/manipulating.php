<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php"; 
	include_once "../../libraries/DML.php";    
	include_once "../..//helpers/mix_helper.php";
	include_once "../../libraries/global_obj.php";	

	$DML = new DML('daya_tarik_agunan',$db);
	$global = new global_obj($db);
	$uc = new user_controller($db);

	$uc->check_access();
	
	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$menu_id = $_POST['menu_id'];
	$kunci_pencarian = $_POST['kunci_pencarian'];
	$ip = get_ip();

	$arr_data=array();
	
	$arr_field = array('fk_penugasan','sarana_listrik','sarana_telepon','sarana_untuk_olahraga','sarana_jalan_lingkungan_perumahan','sarana_fasos_fasum','sarana_air',
					   'sarana_taman_lingkungan','sarana_pengelolaan_lingkungan','sarana_jumlah_akses_jalan_ke_perumahan','bentuk_tanah');
	
	$sarana_fasos_fasum = '';
	$s = false;
	foreach($_POST as $key => $val)
	{
		if(substr($key,0,strlen($key)-1)=='sarana_fasos_fasum')
		{			
			$sarana_fasos_fasum .= ($s?'_'.$val:$val);
			$s = true;
		}		

		if(in_array($key,$arr_field))
			$arr_data[$key]=$val;
	}
	
	$arr_data['sarana_fasos_fasum'] = $sarana_fasos_fasum;

	if($act=='add')
	{
		$id_daya_tarik_agunan = $global->get_incrementID('daya_tarik_agunan','id_daya_tarik_agunan');
		$arr_data['id_daya_tarik_agunan'] = $id_daya_tarik_agunan;
		$result = $DML->save($arr_data);
				
		if(!$result)
			die('failed');

		$activity = "menambah data ke tabel daya_tarik_agunan (fk_penugasan=".$arr_data['fk_penugasan'].")";
		$global->insert_logs($activity,$ip);

	}
	else if($act=='edit')
	{
		$fk_penugasan = $_POST['fk_penugasan'];
		$cond = "fk_penugasan='".$fk_penugasan."'";
		$result = $DML->update($arr_data,$cond);
		
		if(!$result)
			die('failed');

		$activity = "merubah data pada daya_tarik_agunan (fk_penugasan=".$fk_penugasan.")";
		$global->insert_logs($activity,$ip);
	}
	
    $readAccess = $uc->check_priviledge('read',$menu_id);
    $addAccess = $uc->check_priviledge('add',$menu_id);
    $editAccess = $uc->check_priviledge('update',$menu_id);
    $deleteAccess = $uc->check_priviledge('delete',$menu_id);
  	
  	include_once "list_sql.php";

  	if($kunci_pencarian!='')
  		$list_sql .= " WHERE (a.no_penilaian LIKE '%".$kunci_pencarian."%' OR a.id_penugasan  LIKE '%".$kunci_pencarian."%' OR b.nama LIKE '%".$kunci_pencarian."%')";
  	else
  		$list_sql .= "WHERE (a.status='0') ORDER BY id_penugasan DESC LIMIT 0,10";
  	
    $list_of_data = $db->Execute($list_sql);

    if (!$list_of_data)
        print $db->ErrorMsg();

  	include_once "list_of_data.php";
?>