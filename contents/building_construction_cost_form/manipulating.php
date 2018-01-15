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
	
	$DML1 = new DML('perhitunganbkb_master',$db);
	$DML2 = new DML('perhitunganbkb_komponen',$db);
	$DML3 = new DML('perhitunganbkb_hasil',$db);

	$uc->check_access();	

	$act = $_POST['act'];	

	$arr_field = array('fk_jenis_objek','fk_klasifikasi_bangunan','penilai1','penilai2','penilai3','tgl_penilaian','nm_perumahan','provinsi','kota','kecamatan','kelurahan',
					'alamat','nm_bangunan','luas_bangunan','jumlah_lantai','thn_bangun','thn_renov');
	$arr_data1 = array();
	
	foreach($arr_field as $field)
	{
		if(array_key_exists($field, $_POST))
		{
			if($field=='tgl_penilaian')
				$arr_data1[$field] = us_date_format($_POST[$field]);
			else if($field=='provinsi' or $field=='kota' or $field=='kecamatan' or $field=='kelurahan')
			{
				if($field=='provinsi')
					$x_provinsi = explode('_',$_POST[$field]);

				$val='';
				switch($field)
				{
					case 'provinsi':$val=$global->get_province_name($x_provinsi[0]);break;
					case 'kota':$val=$global->get_regency_name($_POST[$field]);break;
					case 'kecamatan':$val=$global->get_district_name($_POST[$field]);break;
					case 'kelurahan':$val=$global->get_village_name($_POST[$field]);break;
				}
				$arr_data1[$field] = $val;
			}
			else
				$arr_data1[$field] = $_POST[$field];
		}
	}	

	$db->BeginTrans();

	if($act=='add')
	{
		$id_perhitunganbkb_master = $global->get_incrementID('perhitunganbkb_master','id_perhitunganbkb_master');
		$no_bct = $global->get_new_bct_number();
		$arr_data1['id_perhitunganbkb_master'] = $id_perhitunganbkb_master;
		$arr_data1['no_bct'] = $no_bct;

		$result = $DML1->save($arr_data1);
	}
	else
	{
		$id_perhitunganbkb_master = $_POST['id_perhitunganbkb_master'];
		$cond = "id_perhitunganbkb_master='".$id_perhitunganbkb_master."'";
		$result = $DML1->update($arr_data1,$cond);
	}

	if(!$result)
	{
		$db->RollBackTrans();
		die('failed1');
	}

	$n_index = $_POST['n_index'];

	$arr_data2= array();
	$total_biaya_komponen = 0;
	
	for($i=1;$i<=$n_index;$i++)
	{
		$index = $_POST['index'.$i];
		$arr_field = array('fk_kelompok_komponen_bangunan','fk_jenis_komponen_bangunan','material_ke','volume','harga_satuan');
		
		foreach($arr_field as $field)
		{
			if(array_key_exists($field.$index, $_POST))
			{
				if($field=='volume' or $field=='harga_satuan')
					$arr_data2[$field] = str_replace(',','',$_POST[$field.$index]);
				else
					$arr_data2[$field] = $_POST[$field.$index];
			}
		}

		if($act=='add')
		{
			$id_perhitunganbkb_komponen = $global->get_incrementID('perhitunganbkb_komponen','id_perhitunganbkb_komponen');
			$arr_data2['id_perhitunganbkb_komponen'] = $id_perhitunganbkb_komponen;
			$arr_data2['fk_perhitunganbkb_master'] = $id_perhitunganbkb_master;
			$result = $DML2->save($arr_data2);
		}
		else
		{
			$id_perhitunganbkb_komponen = $_POST['id_perhitunganbkb_komponen'.$index];
			$cond = "id_perhitunganbkb_komponen='".$id_perhitunganbkb_komponen."'";
			$result = $DML2->update($arr_data2,$cond);
		}

		if(!$result)
		{
			$db->RollBackTrans();
			die('failed2');
		}

		$total_biaya_komponen += $arr_data2['harga_satuan'];
	}

	$arr_field = array('overhead_persen','overhead_nilai','fee_kontraktor_persen','fee_kontraktor_nilai',
					   'fee_konsultan_persen','fee_konsultan_nilai','biaya_imb','total_biaya_langsung','ppn_persen','ppn_nilai',
					   'biaya_lain_persen','biaya_lain_nilai','idc_bunga_konstruksi_persen','idc_bunga_konstruksi_nilai','total_biaya_tidak_langsung',
					   'total_biaya_bangunan','rounded');
	$arr_data3 = array();
	foreach($arr_field as $field)
	{
		if(array_key_exists($field, $_POST))
			$arr_data3[$field] = str_replace(',', '', $_POST[$field]);
	}

	if($act=='add')
	{
		$id_perhitunganbkb_hasil = $global->get_incrementID('perhitunganbkb_hasil','id_perhitunganbkb_hasil');
		$arr_data3['id_perhitunganbkb_hasil'] = $id_perhitunganbkb_hasil;
		$arr_data3['fk_perhitunganbkb_master'] = $id_perhitunganbkb_master;
		$arr_data3['total_biaya_komponen'] = $total_biaya_komponen;
		$result = $DML3->save($arr_data3);
	}
	else
	{		
		$id_perhitunganbkb_master = $_POST['id_perhitunganbkb_master'];
		$arr_data3['total_biaya_komponen'] = $total_biaya_komponen;
		$cond = "fk_perhitunganbkb_master='".$id_perhitunganbkb_master."'";
		$result = $DML3->update($arr_data3,$cond);
	}

	if(!$result)
	{
		$db->RollBackTrans();
		die('failed3');
	}
	
	$ip = get_ip();
	$activity = "menambah data perhitungan biaya konstruksi bangunan (id_perhitunganbkb_master=".$id_perhitunganbkb_master.")";
	$global->insert_logs($activity,$ip);

	$db->CommitTrans();
	
	if($act=='add')	
	{
		$fn = $_POST['fn'];
		$menu_id = $_POST['menu_id'];
		
		$properti = $db->getOne("SELECT jenis_objek FROM ref_jenis_objek WHERE id_jenis_objek='".$_POST['fk_jenis_objek']."'");
		$klasifikasi = $db->getOne("SELECT klasifikasi_bangunan FROM ref_klasifikasi_bangunan WHERE id_klasifikasi_bangunan='".$_POST['fk_klasifikasi_bangunan']."'");

		$result = array('no_bct'=>$no_bct,'properti'=>$properti,'klasifikasi'=>$klasifikasi,
						'nm_perumahan'=>$arr_data1['nm_perumahan'],'alamat'=>$arr_data1['alamat'],'kelurahan'=>$arr_data1['kelurahan'],'kecamatan'=>$arr_data1['kecamatan'],
						'kota'=>$arr_data1['kota'],'provinsi'=>$arr_data1['provinsi']);

	    include_once "input_result.php";
	}
?>