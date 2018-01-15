<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php"; 
	include_once "../../libraries/DML.php";    	
	include_once "../../libraries/global_obj.php";
	include_once "../../helpers/mix_helper.php";
	include_once "../../helpers/date_helper.php";

	$DML1 = new DML('nilai_safetymargin',$db);
	$DML2 = new DML('faktor_safetymargin',$db);
	$global = new global_obj($db);
	$uc = new user_controller($db);

	$uc->check_access();

	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$menu_id = $_POST['menu_id'];	
	$kunci_pencarian = $_POST['kunci_pencarian'];	
	$id_penugasan = $_POST['fk_penugasan'];
	$n_param_safetymargin = $_POST['n_param_safetymargin'];

	$ip = get_ip();

	$arr_data=array();	
	$arr_field = array('fk_penugasan','jenis_objek','total_score','prosentase','nilai');
	
	foreach($_POST as $key => $val)
	{
		if(in_array($key,$arr_field))
		{
			if($key=='nilai')
				$arr_data[$key] = str_replace(',','',$val);
			else
				$arr_data[$key]=$val;
		}
	}

	$db->BeginTrans();

	if($act=='add')
	{
		$id_nilai_safetymargin = $global->get_incrementID('nilai_safetymargin','id_nilai_safetymargin');
		$arr_data['id_nilai_safetymargin'] = $id_nilai_safetymargin;		
		
		$result = $DML1->save($arr_data);

		if(!$result)
			die('failed');

		$arr_data = array();
		for($i=1;$i<=$n_param_safetymargin;$i++)
		{
			$id_faktor_safetymargin = $global->get_incrementID('faktor_safetymargin','id_faktor_safetymargin');
			$id_param_safetymargin = $_POST['id_param_safetymargin'.$i];
			$faktor = $_POST['faktor_safetymargin'.$i];

			$arr_data = array('id_faktor_safetymargin'=>$id_faktor_safetymargin,
							  'fk_nilai_safetymargin'=>$id_nilai_safetymargin,							  
							  'fk_param_safetymargin'=>$id_param_safetymargin,
							  'faktor'=>$faktor
							  );
			
			$result = $DML2->save($arr_data);
			if(!$result)
				die('failed');
		}

		$activity = "menambah data ke tabel nilai_safetymargin (id_nilai_safetymargin=".$id_nilai_safetymargin.")";
		$global->insert_logs($activity,$ip);
	}
	else if($act=='edit')
	{
		$id_nilai_safetymargin = $_POST['id_nilai_safetymargin'];
		$cond = "id_nilai_safetymargin='".$id_nilai_safetymargin."'";
		$result = $DML1->update($arr_data,$cond);
		
		if(!$result)
			die('failed');

		$arr_data = array();
		for($i=1;$i<=$n_param_safetymargin;$i++)
		{
			$id_faktor_safetymargin = $_POST['id_faktor_safetymargin'.$i];
			$id_param_safetymargin = $_POST['id_param_safetymargin'.$i];
			$faktor = $_POST['faktor_safetymargin'.$i];

			$arr_data = array('fk_param_safetymargin'=>$id_param_safetymargin,'faktor'=>$faktor);
			
			$cond = "id_faktor_safetymargin='".$id_faktor_safetymargin."'";
			$result = $DML2->update($arr_data,$cond);
			
			if(!$result)
				die('failed');
		}

		$activity = "merubah data pada tabel nilai_safetymargin (id_nilai_safetymargin=".$id_nilai_safetymargin.")";
		$global->insert_logs($activity,$ip);
	}

    $addAccess = $uc->check_priviledge('add',$menu_id);
    $editAccess = $uc->check_priviledge('update',$menu_id);    
  	  	
  	include_once "list_sql.php";

  	if($kunci_pencarian!='')
  		$list_sql .= " WHERE (a.no_penilaian LIKE '%".$kunci_pencarian."%' OR a.id_penugasan  LIKE '%".$kunci_pencarian."%' OR b.nama LIKE '%".$kunci_pencarian."%')";
  	else
  		$list_sql .= "WHERE (a.status='0') ORDER BY id_penugasan DESC LIMIT 0,10";
  	
    $list_of_data = $db->Execute($list_sql);

    if (!$list_of_data)
        print $db->ErrorMsg();

  	include_once "list_of_data1.php";
?>