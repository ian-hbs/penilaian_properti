<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php"; 
	include_once "../../libraries/DML.php";    
	include_once "../..//helpers/mix_helper.php";
	include_once "../../libraries/global_obj.php";	

	//instantiate objects
	$DML1 = new DML('kesimpulan_rekomendasi',$db);
	$DML2 = new DML('penugasan',$db);
	$DML3 = new DML('log_kesimpulan_rekomendasi',$db);
	$global = new global_obj($db);
	$uc = new user_controller($db);

	$uc->check_access();

	$id_kesimpulan_rekomendasi = $_POST['id_kesimpulan_rekomendasi'];	
	$fn = $_POST['fn'];
	$menu_id = $_POST['menu_id'];
	$kunci_pencarian = $_POST['kunci_pencarian'];
	$ip = get_ip();


	$arr_data=array();
	
	$arr_field = array('fk_penugasan','luas_tanah','luas_bangunan','nilai_pasar_tanah','nilai_pasar_bangunan',
					   'nilai_pasar_sarana_pelengkap','nilai_satuan_tanah','nilai_satuan_bangunan',
					   'nilai_safetymargin_tanah','nilai_safetymargin_bangunan',
					   'nilai_likuidasi_tanah','nilai_likuidasi_bangunan','nilai_likuidasi_sarana_pelengkap',
					   'nilai_satuan_likuidasi_tanah','nilai_satuan_likuidasi_bangunan',
					   'nilai_pasar_objek','nilai_safetymargin_objek','nilai_likuidasi_objek',
					   'pembulatan_pasar_objek','pembulatan_safetymargin_objek','pembulatan_likuidasi_objek',
					   'faktor_penambah_nilai_tanah','faktor_penambah_nilai_bangunan','faktor_pengurang_nilai_tanah',
                       'faktor_pengurang_nilai_bangunan','faktor_pemenuh_nilai','kesimpulan','nama_reviewer1','ijin_reviewer1',
                       'mappi_reviewer1','nama_reviewer2','mappi_reviewer2','nama_penilai1','mappi_penilai1',
                       'nama_penilai2','mappi_penilai2');
		
	foreach($_POST as $key => $val)
	{
		if(in_array($key,$arr_field))
		{
			if($key=='faktor_penambah_nilai_tanah' or $key=='faktor_penambah_nilai_bangunan' or $key=='faktor_pengurang_nilai_tanah' or $key=='faktor_pengurang_nilai_bangunan'
				or $key=='faktor_pemenuh_nilai' or $key=='kesimpulan')
				$arr_data[$key] = $global->real_escape_string($val);
			else
				$arr_data[$key]=$val;
		}
	}	
	
	$db->BeginTrans();
	
	if($id_kesimpulan_rekomendasi!='')
	{
		$no_urut = $_POST['no_urut'];

		$arr_data['nilai_safetymargin_sarana_pelengkap'] = 0;
		$cond = "id_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."'";

		$result = $DML1->update($arr_data,$cond);
		if(!$result)
		{
			$db->RollBackTrans();
			die('failed');			
		}

		unset($arr_data['id_kesimpulan_rekomendasi']);
		unset($arr_data['fk_penugasan']);

		$arr_data['tgl_input'] = date('Y-m-d H:i:s');
		$arr_data['user_input'] = $_SESSION['username'];
		$arr_data['status'] = 'Y';

		$cond = "fk_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."' AND no_urut='".$no_urut."'";
		$result = $DML3->update($arr_data,$cond);
		if(!$result)
		{
			$db->RollBackTrans();
			die('failed');
		}
	}
	else		
	{
		$id_kesimpulan_rekomendasi = $global->get_incrementID('kesimpulan_rekomendasi','id_kesimpulan_rekomendasi');
		$arr_data['nilai_safetymargin_sarana_pelengkap'] = 0;
		$arr_data['id_kesimpulan_rekomendasi'] = $id_kesimpulan_rekomendasi;
		
		$result = $DML1->save($arr_data);

		if(!$result)
		{
			$db->RollBackTrans();
			die('failed');
		}

		unset($arr_data['id_kesimpulan_rekomendasi']);
		unset($arr_data['fk_penugasan']);
		
		$id_log_kesimpulan_rekomendasi = $global->get_incrementID('log_kesimpulan_rekomendasi','id_log_kesimpulan_rekomendasi');
		$order_num = $global->get_order_num_conclusion($id_kesimpulan_rekomendasi);

		$arr_data['id_log_kesimpulan_rekomendasi'] = $id_log_kesimpulan_rekomendasi;
		$arr_data['fk_kesimpulan_rekomendasi'] = $id_kesimpulan_rekomendasi;
		$arr_data['no_urut'] = $order_num;
		$arr_data['tgl_input'] = date('Y-m-d H:i:s');
		$arr_data['user_input'] = $_SESSION['username'];
		$arr_data['status'] = 'Y';

		$result = $DML3->save($arr_data);
		if(!$result)
		{
			$db->RollBackTrans();
			die('failed');
		}		
	}

	unset($arr_data);
	$arr_data['status'] = '1';
	$fk_penugasan = $_POST['fk_penugasan'];
	$cond = "id_penugasan='".$fk_penugasan."'";
	$result = $DML2->update($arr_data,$cond);

	if(!$result)
	{
		$db->RollBackTrans();
		die('failed');
	}

	$activity = "menambah data ke tabel kesimpulan_rekomendasi (fk_penugasan=".$_POST['fk_penugasan'].")";
	$global->insert_logs($activity,$ip);
	
	$db->CommitTrans();

    $readAccess = $uc->check_priviledge('read',$menu_id);
    $addAccess = $uc->check_priviledge('add',$menu_id);
    $editAccess = $uc->check_priviledge('update',$menu_id);
    $deleteAccess = $uc->check_priviledge('delete',$menu_id);
  	
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