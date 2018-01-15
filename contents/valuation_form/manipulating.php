<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../config/app_param.php";
	include_once "../../libraries/user_controller.php"; 
	include_once "../../libraries/DML.php";	
	include_once "../../libraries/global_obj.php";
	include_once "../../helpers/date_helper.php";
	include_once "../../helpers/mix_helper.php";

	$uc = new user_controller($db);
	$global = new global_obj($db);
	$DML = new DML('',$db);

	$uc->check_access();
	
	$arr_op = array('fk_penugasan','fk_jenis_objek','alamat','kelurahan','kecamatan','kota','provinsi','kd_pos','perancang_foto_properti','skala_foto_properti',
					'perancang_peta_lokasi','skala_peta_lokasi');
	$arr_debitur = array('fk_penugasan','nama','alamat','no_tlp_kantor','no_ponsel');
	$arr_penugasan = array('perusahaan_penilai','fk_perusahaan_penunjuk','no_penugasan','tgl_penugasan',
						   'nama_pengorder1','jabatan_pengorder1','nama_pengorder2','jabatan_pengorder2',
						   'no_laporan','tgl_laporan','tgl_survei','reviewer1','reviewer2','penilai1','penilai2','keperluan_penugasan');
	$arr_pemeriksaan = array('fk_penugasan','perusahaan_penilai','depan','belakang','kanan','kiri','klien_pendamping_lokasi',
							 'status_objek','dihuni_oleh','tgl_pemeriksaan','keterangan');
	$arr_tanah = array('fk_penugasan','fk_jenis_sertifikat','no_sertifikat','tgl_terbit_sertifikat','tgl_jatuh_tempo_sertifikat',
					   'no_gs_su','tgl_gs_su','atas_nama','hubungan_dengan_calon_nasabah','luas_tanah','prosentase_bangunan',
					   'tinggi_halaman_thd_jalan','tinggi_halaman_thd_lantai','keadaan_halaman');

	$input_date = date('Y-m-d H:i:s');
	$input_user = $_SESSION['username'];

	$id_properti = $global->get_incrementID('properti','id_properti');
	$id_debitur = $global->get_incrementID('debitur','id_debitur');
	$id_penugasan = $global->get_incrementID('penugasan','id_penugasan');
	$id_pemeriksaan = $global->get_incrementID('pemeriksaan','id_pemeriksaan');
	$id_objek_tanah = $global->get_incrementID('objek_tanah','id_objek_tanah');
	$no_penilaian = $global->get_new_valuation_number();

	$sql_insert1 = "INSERT INTO properti VALUES (".$id_properti;
	$sql_insert2 = "INSERT INTO debitur VALUES (".$id_debitur;
	$sql_insert3 = "INSERT INTO penugasan VALUES (".$id_penugasan.",'".$no_penilaian."'";
	$sql_insert4 = "INSERT INTO pemeriksaan VALUES (".$id_pemeriksaan;
	$sql_insert5 = "INSERT INTO objek_tanah VALUES (".$id_objek_tanah;
	
	//lengkapi sql_insert1
	foreach($arr_op as $key => $val)
	{
		if($val=='fk_penugasan')
			$sql_insert1 .=",'".$id_penugasan."'";
		else if($val=='provinsi')
			$sql_insert1 .=",'".$global->get_province_name($_POST[$val.'_op'])."'";
		else if($val=='kota')
			$sql_insert1 .=",'".$global->get_regency_name($_POST[$val.'_op'])."'";
		else if($val=='kelurahan')
			$sql_insert1 .=",'".$global->get_village_name($_POST[$val.'_op'])."'";
		else if($val=='kecamatan')
			$sql_insert1 .=",'".$global->get_district_name($_POST[$val.'_op'])."'";
		else if($val=='perancang_foto_properti' or $val=='skala_foto_properti' or $val=='perancang_peta_lokasi' or $val=='skala_peta_lokasi')
			$sql_insert1 .=",''";
		else
			$sql_insert1 .= ",'".$global->real_escape_string($_POST[$val.'_op'])."'";
	}	
	$sql_insert1 .=",'".$input_date."','".$input_user."','0000-00-00 00:00:00','')";

	//lengkapi sql_insert2	
	foreach($arr_debitur as $key => $val)
	{
		if($val=='fk_penugasan')
			$sql_insert2 .= ",'".$id_penugasan."'";
		else
			$sql_insert2 .= ",'".$global->real_escape_string($_POST[$val.'_debitur'])."'";
	}	
	$sql_insert2 .=",'".$input_date."','".$input_user."','0000-00-00 00:00:00','')";

	//lengkapi sql_insert3	
	foreach($arr_penugasan as $key => $val)
	{		
		if($val=='no_penugasan' or $val=='keperluan_penugasan')
			$sql_insert3 .= ",'".$global->real_escape_string($_POST[$val])."'";
		else if($val=='tgl_penugasan')
			$sql_insert3 .= ",'".us_date_format($_POST[$val])."'";
		else if($val=='tgl_laporan' or $val=='tgl_survei')
			$sql_insert3 .= ",'".us_date_format($_POST[$val.'_penugasan'])."'";
		else if($val=='perusahaan_penilai')
			$sql_insert3 .= ",'".$global->real_escape_string($_POST['nama_perusahaan_penilai'])."'";
		else
			$sql_insert3 .= ",'".$global->real_escape_string($_POST[$val.'_penugasan'])."'";
	}	
	$sql_insert3 .=",'0','".$input_date."','".$input_user."','0000-00-00 00:00:00','')";

	//lengkapi sql_insert4	
	foreach($arr_pemeriksaan as $key => $val)
	{
		if($val=='fk_penugasan')
			$sql_insert4 .= ",'".$id_penugasan."'";
		else if($val=='perusahaan_penilai')
			$sql_insert4 .= ",'".$global->real_escape_string($_POST['nama_perusahaan_penilai'])."'"; 
		else if($val=='tgl_pemeriksaan')
			$sql_insert4 .= ",'".us_date_format($_POST[$val])."'";
		else
			$sql_insert4 .= ",'".$global->real_escape_string($_POST[$val.'_pemeriksaan'])."'";		
	}	
	$sql_insert4 .=",'".$input_date."','".$input_user."','0000-00-00 00:00:00','')";

	//lengkapi sql_insert5	
	foreach($arr_tanah as $key => $val)
	{
		if($val=='fk_penugasan')
			$sql_insert5 .= ",'".$id_penugasan."'";
		else if($val=='tgl_terbit_sertifikat' or $val=='tgl_gs_su')
			$sql_insert5 .= ",'".us_date_format($_POST[$val.'_tanah'])."'";
		else if($val=='tgl_jatuh_tempo_sertifikat' )
		{
			$tgl = $_POST[$val.'_tanah'];			
			$tgl = (!empty($tgl)?us_date_format($tgl):'0000-00-00');			
			$sql_insert5 .= ",'".$tgl."'";
		}
		else if($val=='atas_nama')
			$sql_insert5 .= ",'".$global->real_escape_string($_POST['nama_debitur'])."'";
		else if($val=='luas_tanah')
			$sql_insert5 .= ",'".$_POST[$val]."'";
		else
			$sql_insert5 .= ",'".$global->real_escape_string($_POST[$val.'_tanah'])."'";
	}
	$sql_insert5 .=",'".$input_date."','".$input_user."','0000-00-00 00:00:00','')";
	
	$db->BeginTrans();
	
	$result1 = $DML->execute($sql_insert1);	
	if(!$result1)
	{

		$db->RollBackTrans();
		die('failed1');
	}	
	$result2 = $DML->execute($sql_insert2);
	if(!$result2)
	{
		$db->RollBackTrans();
		die('failed2');
	}
	$result3 = $DML->execute($sql_insert3);	
	if(!$result3)
	{		
		$db->RollBackTrans();
		die('failed3');
	}
	$result4 = $DML->execute($sql_insert4);
	if(!$result4)
	{
		$db->RollBackTrans();
		die('failed4');
	}
	$result5 = $DML->execute($sql_insert5);	
	if(!$result5)
	{		
		$db->RollBackTrans();
		die('failed5');
	}

	$ip = get_ip();
	$activity = "menambah data penilaian properti (id_penugasan=".$id_penugasan.")";
	$global->insert_logs($activity,$ip);

	$db->CommitTrans();
	
	$fn = $_POST['fn'];
	$menu_id = $_POST['menu_id'];		
	
	$result = array('no_penilaian'=>$no_penilaian,'debitur'=>$_POST['nama_debitur'],'alamat'=>$_POST['alamat_op'],
					'kecamatan'=>$global->get_district_name($_POST['kecamatan_op']),'kelurahan'=>$global->get_village_name($_POST['kelurahan_op']),
					'dt2'=>$global->get_regency_name($_POST['kota_op']),'provinsi'=>$global->get_province_name($_POST['provinsi_op']));

    include_once "input_result.php";
?>