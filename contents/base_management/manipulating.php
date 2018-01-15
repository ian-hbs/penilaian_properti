<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php"; 
	include_once "../../libraries/DML.php";    
	include_once "../../helpers/mix_helper.php";
	include_once "../../helpers/date_helper.php";
	include_once "../../libraries/global_obj.php";		

	$global = new global_obj($db);
	$uc = new user_controller($db);

	$uc->check_access();

	$fk_penugasan = $_POST['fk_penugasan'];	
	$menu_id = $_POST['menu_id'];
	$fn = $_POST['fn'];
	$kunci_pencarian = $_POST['kunci_pencarian'];
	$form_type = $_POST['form_type'];
	$ip = get_ip();

	$arr_data=array();
	$table = '';
	$pk = '';
	$suffix = '';

	if($form_type=='form1')
	{	
		$table = 'properti';
		$suffix = '_op';
		$pk = 'fk_penugasan';
		$DML = new DML($table,$db);
		$arr_field = array('fk_jenis_objek','alamat','kelurahan','kecamatan','kota','provinsi','kd_pos');
	}
	else if($form_type=='form2')
	{		
		$table = 'debitur';
		$suffix = '_debitur';
		$pk = 'fk_penugasan';
		$DML = new DML($table,$db);
		$arr_field = array('nama','alamat','no_tlp_kantor','no_ponsel');
	}
	else if($form_type=='form3')
	{
		$table = 'penugasan';
		$suffix = '_penugasan';
		$pk = 'id_penugasan';
		$DML = new DML($table,$db);
		$arr_field = array('fk_perusahaan_penunjuk','no_penugasan','tgl_penugasan',
						   'nama_pengorder1','jabatan_pengorder1','nama_pengorder2','jabatan_pengorder2','keperluan_penugasan');
	}
	else if($form_type=='form4')
	{
		$table = 'pemeriksaan';
		$suffix = '_pemeriksaan';
		$pk = 'fk_penugasan';
		$DML = new DML($table,$db);
		$arr_field = array('depan','belakang','kanan','kiri','klien_pendamping_lokasi',
						   'status_objek','dihuni_oleh','tgl_pemeriksaan','keterangan');
	}
	else if($form_type=='form5')
	{
		$table = 'objek_tanah';
		$suffix = '_tanah';
		$pk = 'fk_penugasan';
		$DML = new DML($table,$db);
		$arr_field = array('fk_jenis_sertifikat','no_sertifikat','tgl_terbit_sertifikat','tgl_jatuh_tempo_sertifikat',
					   	   'no_gs_su','tgl_gs_su','atas_nama','hubungan_dengan_calon_nasabah','luas_tanah','prosentase_bangunan',
					       'tinggi_halaman_thd_jalan','tinggi_halaman_thd_lantai','keadaan_halaman');

	}
	else
	{
		$table = 'penugasan';
		$suffix = '_penugasan';
		$pk = 'id_penugasan';
		$DML = new DML($table,$db);
		$arr_field = array('no_laporan','tgl_laporan','tgl_survei','reviewer1','reviewer2','penilai1','penilai2');
	}
		
	foreach($arr_field as $key => $val)
	{
		if(array_key_exists($val.$suffix,$_POST))
		{
			if($val=='kelurahan')
				$arr_data[$val] = $global->get_village_name($_POST[$val.$suffix]);
			else if($val=='kecamatan')
				$arr_data[$val] = $global->get_district_name($_POST[$val.$suffix]);
			else if($val=='tgl_terbit_sertifikat' or $val=='tgl_jatuh_tempo_sertifikat' or $val=='tgl_gs_su' or $val=='tgl_laporan' or $val=='tgl_survei')
			{
				$_val = ($_POST[$val.$suffix]==''?'00-00-0000':$_POST[$val.$suffix]);
				$arr_data[$val] = us_date_format($_val);
			}
			else
				$arr_data[$val] = $global->real_escape_string($_POST[$val.$suffix]);
		}
		if(array_key_exists($val, $_POST))
		{						
			if($val=='tgl_penugasan' or $val=='tgl_pemeriksaan')
			{
				$_val = ($_POST[$val]==''?'00-00-0000':$_POST[$val]);
				$arr_data[$val] = us_date_format($_val);
			}
			else if($val=='atas_nama')
			{
				$sql = "SELECT nama FROM debitur WHERE fk_penugasan='".$fk_penugasan."'";
				$nm_debitur = $db->getOne($sql);
				$arr_data[$val] = $nm_debitur;
			}
			else
				$arr_data[$val] = $global->real_escape_string($_POST[$val]);
		}
	}
	
	$arr_data['tgl_modifikasi'] = date('Y-m-d H:i:s');
	$arr_data['user_modifikasi'] = $_SESSION['username'];	
	
	$cond = $pk."='".$fk_penugasan."'";

	$db->BeginTrans();
	$result = $DML->update($arr_data,$cond);
		
	if(!$result)
	{
		$db->RollbackTrans();
		die('failed');
	}

	if($form_type=='form5')
	{

		if($_POST['_luas_tanah']!=$_POST['luas_tanah'])
		{
			$DML2 = new DML('perhitungan_tanah',$db);

			$arr_data = array();
			$arr_data['land_area'] = $_POST['luas_tanah'];
			$cond = "fk_penugasan='".$fk_penugasan."'";
			$result = $DML2->update($arr_data,$cond);

			if(!$result)
			{
				$db->RollbackTrans();
				die('failed1');
			}

			// reconcile land_valuation //
			$reconcile_main_land_valuation = $global->reconcile_main_land_valuation($fk_penugasan);
			if($reconcile_main_land_valuation===false)
			{
				$db->RollbackTrans();
				die('failed2');
			}
			// ====== //

			//reconcile land safetymargin value //
			$reconcile_safetymargin_value = $global->reconcile_safetymargin_value('1',$fk_penugasan);
			if($reconcile_safetymargin_value===false)
			{
				$db->RollbackTrans();
				die('failed3');
			}			
			// ====== //		

		}
			
	}

	$db->CommitTrans();

	$activity = "merubah data pada tabel ".$table." (fk_penugasan=".$fk_penugasan.")";
	$global->insert_logs($activity,$ip);
	
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