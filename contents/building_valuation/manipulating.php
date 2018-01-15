<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php"; 
	include_once "../../libraries/DML.php";    
	include_once "../..//helpers/mix_helper.php";
	include_once "../../libraries/global_obj.php";	

	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$menu_id = $_POST['menu_id'];	
	$kunci_pencarian = $_POST['kunci_pencarian'];
	$data_type = $_POST['data_type'];
	$fk_penugasan = $_POST['fk_penugasan'];
	$jenis_perusahaan_penunjuk = $_POST['jenis_perusahaan_penunjuk'];

	$ip = get_ip();

	$arr_data1=array();
	$arr_data2=array();

	$table1 = ($data_type=='1'?'perhitungan_bangunan':'perhitungan_bangunan_pembanding');
	$table2 = ($data_type=='1'?'adjustment_bangunan':'adjustment_bangunan_pembanding');

	$DML1 = new DML($table1,$db);
	$DML2 = new DML($table2,$db);
	$global = new global_obj($db);
	$uc = new user_controller($db);

	$uc->check_access();


	if($act=='add' || $act=='edit')
	{
		$arr_field1 = array('fk_penugasan','description','qty','built_year','renov_year','construction','eco_use_life','cond_on_inspec',
                       'maintenance','phys_deter','func_obsc','eco_obsc','location_index','floor_area',
                       'total_floor_area','price_sqm_usd','price_sqm_usd_abs','cost_sqm1','cost_sqm2','crn','remain','remain_year',
                       'market_value','liquidation_weight','liquidation_value');

		if($data_type=='1')		
			$arr_field1 = array_merge($arr_field1,array('type','main_building'));
		else
			$arr_field1[] = 'fk_objek_pembanding';		
	

		$arr_field2 = array('phys_act_age1','phys_deter_year','phys_deter1','phys_deter2','phys_deter3','func_obsc1','eco_obsc1','func_obsc2',
                       'eco_obsc2','phys_act_age2','phys_deter4','remain_act','remain_rebuild',
                       'first_remain','mv_per_sqr','maintenance','total');		

		foreach($_POST as $key => $val)
		{
			if(in_array($key,$arr_field1))
			{
				if($key!='fk_penugasan' && $key!='type' && $key!='description' && $key!='construction' && $key!='cond_on_inspec' && $key!='main_building')
					$arr_data1[$key]=str_replace(',','',$val);
				else if($key=='construction')
				{
					$x = explode('_',$val);
					$val = $x[0];
					$arr_data1[$key]=$val;
				}
				else if($key=='description')
					$arr_data1[$key]=$global->real_escape_string($val);
				else
					$arr_data1[$key]=$val;
			}

			if(in_array($key,$arr_field2))
			{				
				$arr_data2[$key]=str_replace(',','',$val);
			}
		}

		if($data_type=='1')
		{
			$arr_data1['main_building'] = ($arr_data1['type']=='building'?$arr_data1['main_building']:'N');
		}		
	}
	
	$db->BeginTrans();
	if($act=='add')
	{
		$table1 = ($data_type=='1'?'perhitungan_bangunan':'perhitungan_bangunan_pembanding');
		$pk1 = ($data_type=='1'?'id_perhitungan_bangunan':'id_perhitungan_bangunan_pembanding');

		$id_perhitungan_bangunan = $global->get_incrementID($table1,$pk1);
		$arr_data1[$pk1] = $id_perhitungan_bangunan;
		$result = $DML1->save($arr_data1);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		$table2 = ($data_type=='1'?'adjustment_bangunan':'adjustment_bangunan_pembanding');
		$pk2 = ($data_type=='1'?'id_adjustment_bangunan':'id_adjustment_bangunan_pembanding');
		$fk = ($data_type=='1'?'fk_perhitungan_bangunan':'fk_perhitungan_bangunan_pembanding');

		$id_adjustment_bangunan = $global->get_incrementID($table2,$pk2);
		$arr_data2[$pk2] = $id_adjustment_bangunan;
		$arr_data2[$fk] = $id_perhitungan_bangunan;
		$result = $DML2->save($arr_data2);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		// reconcile land_valuation //
		$fk_objek_pembanding = ($data_type=='1'?'':$_POST['fk_objek_pembanding']);
		$type = ($data_type=='1'?$_POST['type']:'');

		if($data_type=='2')
		{
			$reconcile_comparison_land_valuation = $global->reconcile_comparison_land_valuation($fk_penugasan,$fk_objek_pembanding);
			if($reconcile_comparison_land_valuation===false)
			{
				$db->RollbackTrans();
				die('failed');
			}
		}

		$reconcile_main_land_valuation = $global->reconcile_main_land_valuation($fk_penugasan);
		if($reconcile_main_land_valuation===false)
		{
			$db->RollbackTrans();
			die('failed');
		}
		// ====== //

		//reconcile safetymargin //
		if($data_type=='1')
		{
			$reconcile_safetymargin_value = $global->reconcile_safetymargin_value(($type=='building'?'2':'3'),$fk_penugasan);
			if($reconcile_safetymargin_value===false)
			{
				$db->RollbackTrans();
				die('failed');
			}
		}
		else
		{
			$reconcile_safetymargin_value = $global->reconcile_safetymargin_value('1',$fk_penugasan);
			if($reconcile_safetymargin_value===false)
			{
				$db->RollbackTrans();
				die('failed');
			}
		}		
		// ====== //		

		$activity = "menambah data ke tabel ".$table1." (".$pk1."=".$arr_data1[$pk1].")";
		$global->insert_logs($activity,$ip);
		$activity = "menambah data ke tabel ".$table2." (".$pk2."=".$arr_data2[$pk2].")";
		$global->insert_logs($activity,$ip);

	}
	else if($act=='edit')
	{
		$table1 = ($data_type=='1'?'perhitungan_bangunan':'perhitungan_bangunan_pembanding');
		$table2 = ($data_type=='1'?'adjustment_bangunan':'adjustment_bangunan_pembanding');
		$pk = ($data_type=='1'?'id_perhitungan_bangunan':'id_perhitungan_bangunan_pembanding');
		$fk = ($data_type=='1'?'fk_perhitungan_bangunan':'fk_perhitungan_bangunan_pembanding');

		$id = $_POST['id'];
		$cond = $pk."='".$id."'";
		$result = $DML1->update($arr_data1,$cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		$cond = $fk."='".$id."'";
		$result = $DML2->update($arr_data2,$cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}
	
		if($_POST['_market_value']!=$_POST['market_value'])
		{
			// reconcile land_valuation //
			$fk_objek_pembanding = ($data_type=='1'?'':$_POST['_fk_objek_pembanding']);
			$type = ($data_type=='1'?$_POST['type']:'');

			if($data_type=='2')
			{
				$reconcile_comparison_land_valuation = $global->reconcile_comparison_land_valuation($fk_penugasan,$fk_objek_pembanding);
				if($reconcile_comparison_land_valuation===false)
				{
					$db->RollbackTrans();
					die('failed');
				}
			}

			$reconcile_main_land_valuation = $global->reconcile_main_land_valuation($fk_penugasan);
			if($reconcile_main_land_valuation===false)
			{
				$db->RollbackTrans();
				die('failed');
			}
			// ====== //

			//reconcile safetymargin //
			if($data_type=='1')
			{
				$reconcile_safetymargin_value = $global->reconcile_safetymargin_value(($type=='building'?'2':'3'),$fk_penugasan);
				if($reconcile_safetymargin_value===false)
				{
					$db->RollbackTrans();
					die('failed');
				}
			}
			else
			{
				$reconcile_safetymargin_value = $global->reconcile_safetymargin_value('1',$fk_penugasan);
				if($reconcile_safetymargin_value===false)
				{
					$db->RollbackTrans();
					die('failed');
				}
			}
			// ====== //
		}		

		$activity = "merubah data pada tabel ".$table1." (".$pk."=".$id.")";
		$global->insert_logs($activity,$ip);
		$activity = "merubah data pada tabel ".$table2." (".$fk."=".$id.")";
		$global->insert_logs($activity,$ip);
	}
	else if($act=='delete')
	{
		$table1 = ($data_type=='1'?'perhitungan_bangunan':'perhitungan_bangunan_pembanding');
		$table2 = ($data_type=='1'?'adjustment_bangunan':'adjustment_bangunan_pembanding');
		$pk = ($data_type=='1'?'id_perhitungan_bangunan':'id_perhitungan_bangunan_pembanding');
		$fk = ($data_type=='1'?'fk_perhitungan_bangunan':'fk_perhitungan_bangunan_pembanding');

		$id=$_POST['id'];		

		$cond = $pk."='".$id."'";
		$result = $DML1->delete($cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		$cond = $fk."='".$id."'";
		$result = $DML2->delete($cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		// reconcile land_valuation //		
		$fk_objek_pembanding = ($data_type=='1'?'':$_POST['fk_objek_pembanding']);
		$type = ($data_type=='1'?$_POST['type']:'');

		if($data_type=='2')
		{
			$reconcile_comparison_land_valuation = $global->reconcile_comparison_land_valuation($fk_penugasan,$fk_objek_pembanding);
			if($reconcile_comparison_land_valuation===false)
			{
				$db->RollbackTrans();
				die('failed');
			}
		}

		$reconcile_main_land_valuation = $global->reconcile_main_land_valuation($fk_penugasan);
		if($reconcile_main_land_valuation===false)
		{
			$db->RollbackTrans();
			die('failed');
		}
		// ====== //

		//reconcile safetymargin //
		if($data_type=='1')
		{
			$reconcile_safetymargin_value = $global->reconcile_safetymargin_value(($type=='building'?'2':'3'),$fk_penugasan);
			if($reconcile_safetymargin_value===false)
			{
				$db->RollbackTrans();
				die('failed');
			}
		}
		else
		{
			$reconcile_safetymargin_value = $global->reconcile_safetymargin_value('1',$fk_penugasan);
			if($reconcile_safetymargin_value===false)
			{
				$db->RollbackTrans();
				die('failed');
			}
		}		
		// ====== //

		$activity = "menghapus data dari tabel ".$table1." (".$pk."=".$id.")";
		$global->insert_logs($activity,$ip);
		$activity = "menghapus data dari tabel ".$table2." (".$fk."=".$id.")";
		$global->insert_logs($activity,$ip);
	}

  	$db->CommitTrans();

  	$readAccess = $uc->check_priviledge('read',$menu_id);
    $addAccess = $uc->check_priviledge('add',$menu_id);
    $editAccess = $uc->check_priviledge('update',$menu_id);
    $deleteAccess = $uc->check_priviledge('delete',$menu_id);
    
	$id_penugasan = $_POST['fk_penugasan'];	

	if($data_type=='1')
    {
        $list_sql2 = "SELECT * FROM perhitungan_bangunan WHERE fk_penugasan='".$id_penugasan."' AND type='building'";
        $list_sql2_2 = "SELECT * FROM perhitungan_bangunan WHERE fk_penugasan='".$id_penugasan."' AND type='site improvement'";

        $list_of_data2 = $db->Execute($list_sql2);

        if (!$list_of_data2)
            print $db->ErrorMsg();

        $list_of_data2_2 = $db->Execute($list_sql2_2);

        if (!$list_of_data2_2)
            print $db->ErrorMsg();            
    }
    else
    {
        $list_sql2 = "SELECT * FROM perhitungan_bangunan_pembanding WHERE fk_penugasan='".$id_penugasan."'";
        $list_of_data2 = $db->Execute($list_sql2);

        if (!$list_of_data2)
            print $db->ErrorMsg();            
    }


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