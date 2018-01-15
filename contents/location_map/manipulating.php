<?php // You need to add server side validation and better error handling here
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php"; 
	include_once "../../libraries/DML.php";    
	include_once "../../helpers/mix_helper.php";
	include_once "../../libraries/global_obj.php";	

	$DML = new DML('peta_lokasi',$db);
	$global = new global_obj($db);
	$uc = new user_controller($db);

	$uc->check_access();

	$data = array();
	$error = false;
	$err_msg = '';

	if(isset($_GET['files']))
	{			
		$files = array();

		$uploaddir = '../../uploads/location_maps/';
		foreach($_FILES as $file)
		{
			if($file['error']==0)
			{
				$filename = rand(0,999).basename($file['name']);
				$url = $uploaddir.$filename;

				$upload_result = $global->compress_image($file['tmp_name'], $url, 80);

				$files[] = $filename;
			}
			else
			{
			    $error = true;
			    $err_msg = $global->_phpFileUploadErrors[$file['error']];
			}
		}
		$data = ($error) ? array('error' => $err_msg) : array('files' => $files);
	}
	else
	{
		$id_penugasan = $_POST['fk_penugasan'];
		$act = $_POST['act'];
		$fn = $_POST['fn'];
		$menu_id = $_POST['menu_id'];	
		$kunci_pencarian = $_POST['kunci_pencarian'];
		$filename = $_POST['filename'];
		$ip = get_ip();

		$activity = '';

		if($act=='add')
		{
			$id_peta = $global->get_incrementID('peta_lokasi','id_peta');
			$jenis = $_POST['jenis'];
			$keterangan = $global->real_escape_string($_POST['keterangan']);
			$arr_data = array('id_peta'=>$id_peta,'fk_penugasan'=>$id_penugasan,'jenis'=>$jenis,'file_foto'=>$filename,'keterangan'=>$keterangan);

			$result = $DML->save($arr_data);
				
			if(!$result)
			{
				$uploaddir = '../../uploads/location_maps/';
				if(file_exists($uploaddir.$filename))
					unlink($uploaddir.$filename);
				$error = true;
			}

			$activity = "menambah data ke tabel peta_lokasi (id_peta=".$id_peta.")";
		}
		else if($act=='edit')
		{
			$id = $_POST['id'];			
			$keterangan = $global->real_escape_string($_POST['keterangan']);
			$_file_foto = $_POST['_file_foto'];

			$arr_data = array('file_foto'=>$filename,'keterangan'=>$keterangan);
			$cond = "id_peta='".$id."'";

			$result = $DML->update($arr_data,$cond);
			
			$uploaddir = '../../uploads/location_maps/';
			if(!$result)
			{
				if(file_exists($uploaddir.$filename))
					unlink($uploaddir.$filename);
				$error = true;
			}
			else
			{
				if(file_exists($uploaddir.$_file_foto))
					unlink($uploaddir.$_file_foto);
			}

			$activity = "merubah data pada tabel peta_lokasi (id_peta=".$id.")";
		}
		else if($act=='delete')
		{
			$id=$_POST['id'];
			$cond = "id_peta='".$id."'";
			$result = $DML->delete($cond);
			
			if(!$result)
				$error = true;

			$uploaddir = '../../uploads/location_maps/';
			$filename = $_POST['filename'];
			if(file_exists($uploaddir.$filename))
				unlink($uploaddir.$filename);

			$activity = "menghapus data dari tabel peta_lokasi (id_peta=".$id.")";			
		}
		if(!$error)
		{
			$global->insert_logs($activity,$ip);

			$readAccess = $uc->check_priviledge('read',$menu_id);
		    $addAccess = $uc->check_priviledge('add',$menu_id);
		    $editAccess = $uc->check_priviledge('update',$menu_id);
		    $deleteAccess = $uc->check_priviledge('delete',$menu_id);
		    
		  	$maps = array();
		    $maps[0] = $global->get_location_map_data($id_penugasan,'peta1');
		    $maps[1] = $global->get_location_map_data($id_penugasan,'peta2');
		    $maps[2] = $global->get_location_map_data($id_penugasan,'peletakan_tanah');
		    $maps[3] = $global->get_location_map_data($id_penugasan,'peletakan_bangunan');

			include_once "list_of_data2.php";			

			include_once "list_sql.php";

			if($kunci_pencarian!='')
		  		$list_sql .= " WHERE (a.no_penilaian LIKE '%".$kunci_pencarian."%' OR a.id_penugasan  LIKE '%".$kunci_pencarian."%' OR b.nama LIKE '%".$kunci_pencarian."%')";
		  	else
		  		$list_sql .= "WHERE (a.status='0') ORDER BY id_penugasan DESC LIMIT 0,10";
		  	
		    $list_of_data = $db->Execute($list_sql);

		    if (!$list_of_data)
		        print $db->ErrorMsg();

			include_once "list_of_data1.php";
		  	
			if($act=='add' or $act=='edit')
			{
				$data = array('success' => 'Form was submitted', 'page_content1' => $list_of_data1, 'page_content2' => $list_of_data2);
			}
			else
			{
				echo $list_of_data2;
				echo "|$*{()}*$|";
				die($list_of_data1);
			}
			
		}
		else
		{
			$data = array('error' => 'Gagal menyimpan data');
		}		
	}
	
	echo json_encode($data);	

?>