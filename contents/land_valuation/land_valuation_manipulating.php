<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php"; 
	include_once "../../libraries/DML.php";    	
	include_once "../../libraries/global_obj.php";
	include_once "../../helpers/mix_helper.php";
	include_once "../../helpers/date_helper.php";

	$DML1 = new DML('perhitungan_tanah',$db);
	$DML2 = new DML('perhitungan_tanah_pembanding',$db);
	$DML3 = new DML('adjustment_tanah_pembanding',$db);

	$global = new global_obj($db);
	$uc = new user_controller($db);

	$uc->check_access();
	
	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$menu_id = $_POST['menu_id'];	
	$kunci_pencarian = $_POST['kunci_pencarian'];	
	$id_penugasan = $_POST['fk_penugasan'];	
	$jenis_perusahaan_penunjuk = $_POST['jenis_perusahaan_penunjuk'];

	$ip = get_ip();		

	$sql = "SELECT id_objek_pembanding,no_urut FROM objek_pembanding WHERE fk_penugasan='".$id_penugasan."'";
	$result = $db->Execute($sql);
	if(!$result)
		die('fialed');

	$arr_no_urut = array();
	while($row = $result->FetchRow())
	{
		$arr_no_urut[]=array(0=>$row['id_objek_pembanding'],1=>$row['no_urut']);
	}

	if($act=='add' || $act=='edit')
	{
		$arr_field1 = array('land_area','land_title','building_area','built_year','condition','crn_of_building_per_sqm','construction',
                       'economic_life_of_building','frontage','wide_road_access','elevation','land_shape','location','position',
                       'time','indicated_property_value',
                       'indicated_building_market_value_sqm','indicated_building_market_value','indicated_land_value',
                       'indicated_property_value_land','indicated_land_value_sqm','weighted_percent_final',
                       'indicated_land_value_final','rounded1_final','total_land_value_final','rounded2_final',
                       'liquidation_weight','liquidation_value');
		
		$arr_field2 = array('land_area','land_title','building_area','built_year','condition','crn_of_building_per_sqm','construction',
                       'economic_life_of_building','frontage','wide_road_access','elevation','land_shape','location','position',
                       'offering_price','transaction_price','time','discount','total_price','indicated_property_value',
                       'indicated_building_market_value_sqm','indicated_building_market_value','indicated_land_value',
                       'indicated_property_value_land','indicated_land_value_sqm','weighted_percent',
                       'weighted_amount');

		$arr_field3 = array('land_title','land_area','land_use','land_shape',
							'position','frontage','location','wide_road','elevasi',
							'total_adjusted_percent','land_title_amount','land_area_amount','land_use_amount',
							'land_shape_amount','position_amount','frontage_amount','location_amount','wide_road_amount',
							'elevasi_amount','total_adjusted_percent','total_adjusted_amount','indicated_land_value');

		if($jenis_perusahaan_penunjuk=='1')
		{
			$arr_field3 = array_merge($arr_field3,array('time','time_amount'));
		}
		else
		{
			$arr_field3 = array_merge($arr_field3,array('development_environment','economic_factor','security_facility',
														'development_environment_amount','economic_factor_amount','security_facility_amount'));
		}

		$arr_data1 = array();
				
		foreach($arr_field1 as $key => $val)
		{
			if(array_key_exists($val.'0', $_POST))
			{
				if($val=='crn_of_building_per_sqm' || $val=='indicated_property_value' || $val=='indicated_building_market_value_sqm' || 
				   $val=='indicated_building_market_value' || $val=='indicated_land_value' || $val=='indicated_property_value_land' ||
				   $val=='indicated_land_value_sqm')
				{					
					$arr_data1[$val] = str_replace(',','',$_POST[$val.'0']);
				}
				else if($val=='time')
				{
					$arr_data1[$val] = us_date_format($_POST[$val.'0']);
				}
				else
				{					
					$arr_data1[$val] = $_POST[$val.'0'];
				}
				
			}

			if(array_key_exists($val, $_POST))
			{
				$_val = '';
				switch($val)
				{
					case 'weighted_percent_final':$_val='indicated_land_value_weighted';break;
					case 'indicated_land_value_final':$_val='indicated_land_value_amount';break;
					case 'rounded1_final':$_val='first_rounded';break;
					case 'total_land_value_final':$_val='total_land_value';break;
					case 'rounded2_final':$_val='final_rounded';break;
					default : $_val = $val;
				}
				$arr_data1[$_val] = str_replace(',','',$_POST[$val]);
			}
		}
		
	}
	
	$db->BeginTrans();

	if($act=='add')
	{
		$id_perhitungan_tanah = $global->get_incrementID('perhitungan_tanah','id_perhitungan_tanah');
		$arr_data1['id_perhitungan_tanah'] = $id_perhitungan_tanah;
		$arr_data1['fk_penugasan'] = $id_penugasan;
		$arr_data1['topography'] = ($jenis_perusahaan_penunjuk=='1'?'':$_POST['topography0']);
		$arr_data1['security_facility'] = ($jenis_perusahaan_penunjuk=='1'?'':$_POST['security_facility0']);

		$result = $DML1->save($arr_data1);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		foreach($arr_no_urut as $key1=>$val1)
		{
			$arr_data2 = array();
			$arr_data3 = array();

			foreach($arr_field2 as $key2=>$val2)
			{				
				if(array_key_exists($val2.$val1[1],$_POST))
				{
					if($val2=='crn_of_building_per_sqm' || $val2=='offering_price' || $val2=='transaction_price' || $val2=='total_price' || 
					   $val2=='indicated_property_value' || $val2=='indicated_building_market_value_sqm' || $val2=='indicated_building_market_value' || 
					   $val2=='indicated_land_value' || $val2=='indicated_property_value_land' ||$val2=='indicated_land_value_sqm' || 
					   $val2=='weighted_amount')
					{
						$arr_data2[$val2] = str_replace(',','',$_POST[$val2.$val1[1]]);
					}
					else if($val2=='time')
					{
						$arr_data2[$val2] = us_date_format($_POST[$val2.$val1[1]]);
					}
					else
						$arr_data2[$val2] = $_POST[$val2.$val1[1]];
				}
			}

			foreach($arr_field3 as $key2=>$val2)
			{
				if(array_key_exists($val2.'_percent'.$val1[1],$_POST))
				{
					$_val = ($_POST[$val2.'_percent'.$val1[1]]==''?0:str_replace(',','',$_POST[$val2.'_percent'.$val1[1]]));
					$arr_data3[$val2] = $_val;
				}
				else
				{
					$post_index = ($val2=='indicated_land_value'?$val2.'_amount'.$val1[1]:$val2.$val1[1]);
					$arr_data3[$val2] = str_replace(',','',$_POST[$post_index]);
				}				
			}
			
			$id_perhitungan_tanah_pembanding = $global->get_incrementID('perhitungan_tanah_pembanding','id_perhitungan_tanah_pembanding');
			$arr_data2['id_perhitungan_tanah_pembanding'] = $id_perhitungan_tanah_pembanding;
			$arr_data2['fk_penugasan'] = $id_penugasan;
			$arr_data2['fk_objek_pembanding'] = $val1[0];			
			
			$arr_data2['topography'] = ($jenis_perusahaan_penunjuk=='1'?'':$_POST['topography'.$val1[1]]);
			$arr_data2['security_facility'] = ($jenis_perusahaan_penunjuk=='1'?'':$_POST['security_facility'.$val1[1]]);

			$result = $DML2->save($arr_data2);
			if(!$result)
			{
				$db->RollbackTrans();
				die('failed');
			}

			$id_adjustment_tanah_pembanding = $global->get_incrementID('adjustment_tanah_pembanding','id_adjustment_tanah_pembanding');
			$arr_data3['id_adjustment_tanah_pembanding'] = $id_adjustment_tanah_pembanding;
			$arr_data3['fk_perhitungan_tanah_pembanding'] = $id_perhitungan_tanah_pembanding;			

			$result = $DML3->save($arr_data3);
			if(!$result)
			{
				$db->RollbackTrans();
				die('failed');
			}
		}

		$activity = "menambah data ke tabel perhitungan_tanah,perhitungan_tanah_pembanding,adjustment_tanah_pembanding (id_perhitungan_tanah=".$id_perhitungan_tanah.")";
		$global->insert_logs($activity,$ip);
	}
	else if($act=='edit')
	{
		$cond = "fk_penugasan='".$id_penugasan."'";
		$arr_data1['topography'] = ($jenis_perusahaan_penunjuk=='1'?'':$_POST['topography0']);
		$arr_data1['security_facility'] = ($jenis_perusahaan_penunjuk=='1'?'':$_POST['security_facility0']);

		$result = $DML1->update($arr_data1,$cond);

		if(!$result)
		{
			$db->RollbackTrans();
			die('failed1');
		}

		// update nilai safety margin tanah
		$sql = "SELECT id_nilai_safetymargin,prosentase FROM nilai_safetymargin WHERE(fk_penugasan='".$id_penugasan."') AND (jenis_objek='tanah')";
		$result = $db->Execute($sql);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed2');
		}

		$row = $result->FetchRow();
		$idnsm = $row['id_nilai_safetymargin'];
		$psm = $row['prosentase'];

		$nsm = ((100-$psm) * $arr_data1['total_land_value'])/100;

		$sql = "UPDATE nilai_safetymargin SET nilai='".$nsm."' WHERE(id_nilai_safetymargin='".$idnsm."')";
		$result = $db->Execute($sql);

		if(!$result)
		{
			$db->RollbackTrans();
			die('failed3');
		}
		// == //
		
		foreach($arr_no_urut as $key1=>$val1)
		{
			$arr_data2 = array();
			$arr_data3 = array();

			foreach($arr_field2 as $key2=>$val2)
			{				
				if(array_key_exists($val2.$val1[1],$_POST))
				{
					if($val2=='crn_of_building_per_sqm' || $val2=='offering_price' || $val2=='transaction_price' || $val2=='total_price' || 
					   $val2=='indicated_property_value' || $val2=='indicated_building_market_value_sqm' || $val2=='indicated_building_market_value' || 
					   $val2=='indicated_land_value' || $val2=='indicated_property_value_land' ||$val2=='indicated_land_value_sqm' || 
					   $val2=='weighted_amount')
					{
						$arr_data2[$val2] = str_replace(',','',$_POST[$val2.$val1[1]]);
					}
					else if($val2=='time')
					{
						$arr_data2[$val2] = us_date_format($_POST[$val2.$val1[1]]);
					}
					else
						$arr_data2[$val2] = $_POST[$val2.$val1[1]];
				}
			}

			foreach($arr_field3 as $key2=>$val2)
			{
				if(array_key_exists($val2.'_percent'.$val1[1],$_POST))
				{
					$_val = ($_POST[$val2.'_percent'.$val1[1]]==''?0:str_replace(',','',$_POST[$val2.'_percent'.$val1[1]]));
					$arr_data3[$val2] = $_val;
				}
				else
				{
					$post_index = ($val2=='indicated_land_value'?$val2.'_amount'.$val1[1]:$val2.$val1[1]);
					$arr_data3[$val2] = str_replace(',','',$_POST[$post_index]);
				}				
			}
						
			
			$arr_data2['topography'] = ($jenis_perusahaan_penunjuk=='1'?'':$_POST['topography'.$val1[1]]);
			$arr_data2['security_facility'] = ($jenis_perusahaan_penunjuk=='1'?'':$_POST['security_facility'.$val1[1]]);


			$id_ptp = $_POST['id_perhitungan_tanah_pembanding'.$val1[1]];			
			if($id_ptp=='')
			{
				$id_ptp = $global->get_incrementID('perhitungan_tanah_pembanding','id_perhitungan_tanah_pembanding');
				$arr_data2['id_perhitungan_tanah_pembanding'] = $id_ptp;
				$arr_data2['fk_penugasan'] = $id_penugasan;
				$arr_data2['fk_objek_pembanding'] = $val1[0];
				$result = $DML2->save($arr_data2);
			}
			else
			{				
				$cond = "id_perhitungan_tanah_pembanding='".$id_ptp."'";			
				$result = $DML2->update($arr_data2,$cond);
				
			}
			if(!$result)
			{
				$db->RollbackTrans();
				die('failed4');
			}

			$id_atp = $_POST['id_adjustment_tanah_pembanding'.$val1[1]];
			if($id_atp=='')
			{
				$id_atp = $global->get_incrementID('adjustment_tanah_pembanding','id_adjustment_tanah_pembanding');
				$arr_data3['id_adjustment_tanah_pembanding'] = $id_atp;
				$arr_data3['fk_perhitungan_tanah_pembanding'] = $id_ptp;
				$result = $DML3->save($arr_data3);
			}
			else
			{
				$cond = "id_adjustment_tanah_pembanding='".$id_atp."'";
				$result = $DML3->update($arr_data3,$cond);
			}
			
			if(!$result)
			{
				$db->RollbackTrans();
				die('failed5');
			}
		}

		$activity = "merubah data pada tabel perhitungan_tanah,perhitungan_tanah_pembanding,adjustment_tanah_pembanding (id_perhitungan_tanah=".$id_penugasan.")";
		$global->insert_logs($activity,$ip);		
	}
  	
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