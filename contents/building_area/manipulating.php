<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php"; 
	include_once "../../libraries/DML.php";    
	include_once "../..//helpers/mix_helper.php";
	include_once "../../libraries/global_obj.php";	

	$DML1 = new DML('luas_bangunan',$db);
	$DML2 = new DML('perhitungan_bangunan',$db);
	$DML3 = new DML('nilai_safetymargin',$db);

	$global = new global_obj($db);
	$uc = new user_controller($db);

	$uc->check_access();

	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$menu_id = $_POST['menu_id'];	
	$kunci_pencarian = $_POST['kunci_pencarian'];
	$fk_penugasan = $_POST['fk_penugasan'];	

	$ip = get_ip();

	$arr_data=array();

	if($act=='add' || $act=='edit')
	{
		$arr_field = array('fk_penugasan','tingkat_lantai','tahun_bangun','teras','ruang_tamu','ruang_keluarga','ruang_tidur1','ruang_tidur2',
						   'ruang_tidur3','ruang_dapur','kamar_mandi','lain_lain','total');
		
		foreach($_POST as $key => $val)
		{
			if(in_array($key,$arr_field))
			{
				if($key!='fK_penugasan' && $key!='tingkat_lantai')
					$arr_data[$key]=str_replace(',','',$val);
				else
					$arr_data[$key]=$val;
			}
		}
	}

	$db->BeginTrans();

	$x = false;
	if($act=='edit')
		$x = ($_POST['total']!=$_POST['_total']?true:false);
	else if($act=='delete')
		$x = true;

	if($x)
	{
		$cond = "fk_penugasan='".$fk_penugasan."' AND (type='building')";
		$result = $DML2->delete($cond);
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		$cond = "fK_penugasan='".$fK_penugasan."' AND (jenis_objek='bangunan')";
		$result = $DML3->delete($cond);
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');	
		}

		$reconcile_land_valuation = $global->reconcile_land_valuation('1','building',$fk_penugasan,'',0,0);
		if($reconcile_land_valuation===false)
		{
			$db->RollbackTrans();
			die('failed');
		}		
	}

	if($act=='add')
	{
		$id_luas_bangunan = $global->get_incrementID('luas_bangunan','id_luas_bangunan');
		$arr_data['id_luas_bangunan'] = $id_luas_bangunan;
		$result = $DML1->save($arr_data);
				
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		$activity = "menambah data ke tabel luas_bangunan (id_luas_bangunan=".$arr_data['id_luas_bangunan'].")";
		$global->insert_logs($activity,$ip);

	}
	else if($act=='edit')
	{
		$id = $_POST['id'];
		$cond = "id_luas_bangunan='".$id."'";
		$result = $DML1->update($arr_data,$cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}
		

		$activity = "merubah data pada tabel luas_bangunan (id_luas_bangunan=".$id.")";
		$global->insert_logs($activity,$ip);
	}
	else if($act=='delete')
	{
		$id=$_POST['id'];
		$cond = "id_luas_bangunan='".$id."'";
		$result = $DML1->delete($cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}		

		$activity = "menghapus data dari tabel luas_bangunan (id_luas_bangunan=".$id.")";
		$global->insert_logs($activity,$ip);
	}
  	
  	$db->CommitTrans();

  	$readAccess = $uc->check_priviledge('read',$menu_id);
    $addAccess = $uc->check_priviledge('add',$menu_id);
    $editAccess = $uc->check_priviledge('update',$menu_id);
    $deleteAccess = $uc->check_priviledge('delete',$menu_id);
    	
  	$list_sql = "SELECT id_luas_bangunan,tahun_bangun,fk_penugasan,tingkat_lantai,teras,ruang_tamu,ruang_keluarga,ruang_tidur1,ruang_tidur2,
                 ruang_tidur3,ruang_dapur,kamar_mandi,lain_lain,total FROM luas_bangunan 
                 WHERE fk_penugasan='".$fk_penugasan."'";    

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
  		$list_sql .= " ORDER BY id_penugasan DESC LIMIT 0,10";
  	
    $list_of_data = $db->Execute($list_sql);

    if (!$list_of_data)
        print $db->ErrorMsg();

	include_once "list_of_data1.php";
?>