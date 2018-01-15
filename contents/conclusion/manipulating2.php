<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php";
	include_once "../../libraries/DML.php";	
	include_once "../../libraries/global_obj.php";
	include_once "../../helpers/mix_helper.php";	
	include_once "../../helpers/date_helper.php";

	//instantiate objects	
	$global = new global_obj($db);
	$uc = new user_controller($db);

	$uc->check_access();

	$DML1 = new DML('penugasan',$db);
	$DML2 = new DML('log_kesimpulan_rekomendasi',$db);
	$DML3 = new DML('kesimpulan_rekomendasi',$db);

	$id_penugasan = $_POST['id_penugasan'];	
	$id_kesimpulan_rekomendasi = $_POST['id_kesimpulan_rekomendasi'];
	$jenis_perusahaan_penunjuk = $_POST['jenis_perusahaan_penunjuk'];
	$menu_id = $_POST['menu_id'];
  	$fn = $_POST['fn'];
  	$kunci_pencarian = $_POST['kunci_pencarian'];
  	$change_act = $_POST['change_act'];

	$ip = get_ip();

	$db->BeginTrans();


	if($change_act=='open')
	{
		//check changing data
		$sql = "SELECT COUNT(1) n_data FROM log_kesimpulan_rekomendasi WHERE(fk_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."') AND (user_input='-')";
		$n_data = $db->getOne($sql);		
		
		if($n_data>0)
			die('ERROR : Mode Perubahan tidak bisa dibuka. Ada Data Perubahan yang belum diinput!');
		// ====== //
	
		//update field status='0' in penugasan table according to id_penugasan
		$arr_data = array('status'=>'0');
		$cond = "id_penugasan='".$id_penugasan."'";
		$result = $DML1->update($arr_data,$cond);
		if(!$result)
		{
			$db->RollBackTrans();
			die('failed');
		}
		// ====== //

		//update field status='N' in log_kesimpulan_rekomendasi according to id_kesimpulan_rekomendasi
		$arr_data = array('status'=>'N');
		$cond = "fk_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."'";
		$result = $DML2->update($arr_data,$cond);
		if(!$result)
		{
			$db->RollBackTrans();
			die('failed');
		}	

		$id_log_kesimpulan_rekomendasi = $global->get_incrementID('log_kesimpulan_rekomendasi','id_log_kesimpulan_rekomendasi');
		$order_num = $global->get_order_num_conclusion($id_kesimpulan_rekomendasi);	

		$valued_fields = array('id_log_kesimpulan_rekomendasi'=>$id_log_kesimpulan_rekomendasi,'fk_kesimpulan_rekomendasi'=>$id_kesimpulan_rekomendasi,
							   'no_urut'=>$order_num,'tgl_input'=>'0000-00-00 00:00:00','user_input'=>'-','status'=>'Y');
		$arr_data = array();

		$sql = "DESC log_kesimpulan_rekomendasi";
		$result = $db->Execute($sql);
		if(!$result)
		{
			$db->RollBackTrans();
			die('failed');
		}
		while($row=$result->FetchRow())
		{

			$val = (array_key_exists($row['Field'],$valued_fields)?$valued_fields[$row['Field']]:0);
			$arr_data[$row['Field']] = $val;
		}

		$result = $DML2->save($arr_data);
		if(!$result)
		{
			$db->RollBackTrans();
			die('failed');
		}

		$reconcile_conclusion = reconcile_conclusion($id_kesimpulan_rekomendasi);
		if(!$reconcile_conclusion)
		{
			$db->RollBackTrans();
			die('failed');
		}

		$activity = "membuka mode perubahan (fk_kesimpulan_rekomendasi=".$id_kesimpulan_rekomendasi.";no_urut=".$order_num.")";
		$global->insert_logs($activity,$ip);
	}
	else if($change_act=='change')
	{
		//update field status='N' in log_kesimpulan_rekomendasi according to id_kesimpulan_rekomendasi
		$arr_data = array('status'=>'N');
		$cond = "fk_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."'";
		$result = $DML2->update($arr_data,$cond);
		if(!$result)
		{
			$db->RollBackTrans();
			die('failed');
		}	
		// ====== //

	  	//update field status='Y' in log_kesimpulan_rekomendasi according to id_kesimpulan_rekomendasi and no_urut		
		$no_urut = $_POST['no_urut'];

		$arr_data = array('status'=>'Y');
		$cond = "fk_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."' AND no_urut='".$no_urut."'";
		$result = $DML2->update($arr_data,$cond);
		if(!$result)
		{
			$db->RollBackTrans();
			die('failed');
		}	
		// ====== //

		//update field status='0' in penugasan table according to id_penugasan
		$reconcile_status_assigment = reconcile_status_assigment($id_kesimpulan_rekomendasi);
		if(!$reconcile_status_assigment)
		{
			$db->RollBackTrans();
			die('failed');
		}				
		// ====== //

		$reconcile_conclusion = reconcile_conclusion($id_kesimpulan_rekomendasi);
		if(!$reconcile_conclusion)
		{
			$db->RollBackTrans();
			die('failed');
		}

		$activity = "mengganti data aktif (fk_kesimpulan_rekomendasi=".$id_kesimpulan_rekomendasi.";no_urut=".$no_urut.")";
		$global->insert_logs($activity,$ip);		
	}
	else
	{
		$sql = "SELECT COUNT(1) n_data FROM log_kesimpulan_rekomendasi WHERE(fk_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."')";
		$n_data = $db->getOne($sql);

		$no_urut = $_POST['no_urut'];
		$cond = "fk_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."' AND no_urut='".$no_urut."'";
		$result = $DML2->delete($cond);
		if(!$result)
		{
			$db->RollBackTrans();
			die('failed');			
		}

		if($n_data>1)
		{
			$activate_proper_conclusion = activate_proper_conclusion($id_kesimpulan_rekomendasi);
			if(!$activate_proper_conclusion)
			{
				$db->RollBackTrans();
				die('failed');
			}						

			$reconcile_conclusion = reconcile_conclusion($id_kesimpulan_rekomendasi);
			if(!$reconcile_conclusion)
			{
				$db->RollBackTrans();
				die('failed');
			}

			$reorder_conclusion = reorder_conclusion($id_kesimpulan_rekomendasi);
			if(!$reorder_conclusion)
			{
				$db->RollBackTrans();
				die('failed');
			}
		}
		else
		{
			$cond = "id_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."'";
			$result = $DML3->delete($cond);
			if(!$result)
			{
				$db->RollBackTrans();
				die('failed');
			}
		}

		$reconcile_status_assigment = reconcile_status_assigment($id_kesimpulan_rekomendasi);
		if(!$reconcile_status_assigment)
		{
			$db->RollBackTrans();
			die('failed');
		}

	}

	function reconcile_status_assigment($id_penugasan)
	{
		global $db,$DML1;

		$sql = "SELECT b.user_input FROM kesimpulan_rekomendasi as a 
				INNER JOIN (SELECT fk_kesimpulan_rekomendasi,user_input FROM log_kesimpulan_rekomendasi WHERE(status='Y')) as b 
				ON (a.id_kesimpulan_rekomendasi=b.fk_kesimpulan_rekomendasi) WHERE(fk_penugasan='".$id_penugasan."')";
		$result = $db->Execute($sql);
		if(!$result)
		{
			return false;
		}

		$status = '0';

		if($result->RecordCount()>0)
		{
			$row = $result->FetchRow();
			$status = ($row['user_input']=='-'?'0':'1');
		}

		$arr_data = array('status'=>$status);
		$cond = "id_penugasan='".$id_penugasan."'";
		$result = $DML1->update($arr_data,$cond);
		if(!$result)
		{
			return false;
		}

		return true;
	}

	function reorder_conclusion($id_kesimpulan_rekomendasi)
	{
		global $db,$DML2;

		$sql = "SELECT id_log_kesimpulan_rekomendasi,no_urut FROM log_kesimpulan_rekomendasi WHERE(fk_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."') ORDER BY no_urut ASC";
		$result1 = $db->Execute($sql);
		if(!$result1)
		{
			return false;
		}
		$no = 0;
		while($row = $result1->FetchRow())
		{
			$no++;
			$arr_data = array('no_urut'=>$no);
			$cond = "id_log_kesimpulan_rekomendasi='".$row['id_log_kesimpulan_rekomendasi']."'";
			$result2 = $DML2->update($arr_data,$cond);
			if(!$result2)
			{
				return false;
			}			
		}
		return true;
	}

	function activate_proper_conclusion($id_kesimpulan_rekomendasi)
	{
		global $db,$DML2;
		
		$sql = "SELECT id_log_kesimpulan_rekomendasi FROM log_kesimpulan_rekomendasi WHERE(fk_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."') ORDER BY no_urut DESC LIMIT 0,1";
		$result = $db->Execute($sql);
		if(!$result)
		{
			return false;
		}

		$row = $result->FetchRow();

		$arr_data = array('status'=>'Y');
		$cond = "id_log_kesimpulan_rekomendasi='".$row['id_log_kesimpulan_rekomendasi']."'";
		$result = $DML2->update($arr_data,$cond);
		if(!$result)
		{
			return false;
		}

		return true;
	}

	function reconcile_conclusion($id_kesimpulan_rekomendasi)
	{
		global $db,$DML3;

		$sql = "SELECT * FROM log_kesimpulan_rekomendasi WHERE(fk_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."') AND (status='Y')";
		$result = $db->Execute($sql);
		$not_include = array('id_log_kesimpulan_rekomendasi','fk_kesimpulan_rekomendasi','no_urut','tgl_input','user_input','status');
		
		if(!$result)
		{
			return false;
		}

		$row = $result->FetchRow();
		
		$arr_data = array();
		foreach($row as $key => $val){            
			if(!in_array($key,$not_include))
			{
				$arr_data[$key] = $val;
			}
        }

        $cond = "id_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."'";
        $result = $DML3->update($arr_data,$cond);
        if(!$result)
        {
        	return false;
        }
        return true;
	}

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