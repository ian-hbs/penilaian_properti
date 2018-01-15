<?php	
	require_once 'db_connection.php';

	$sql = "SELECT * FROM system_params";
	$result = $db->Execute($sql);
	if(!$result)
	{
		die($db->ErrorMsg());
	}

	$_APP_PARAM['system_params'] = array();

	while($row=$result->FetchRow())
	{
		$_APP_PARAM['system_params'][$row['name']] = $row['value'];
	}

	$_APP_PARAM['base']['sys_name_acr1'] = 'SIPP';
	$_APP_PARAM['base']['sys_name_acr2'] = 'SIPP';
	$_APP_PARAM['base']['sys_name_full'] = 'Sistem Informasi Penilaian Properti';
	$_APP_PARAM['base']['release_year'] = 2017;
	$_APP_PARAM['base']['version'] = 'v1.0.0';
	$_APP_PARAM['base']['development_year'] = 2017;
	$_APP_PARAM['base']['development_start'] = '2017-03-30';
	$_APP_PARAM['base']['development_finish'] = '';
	$_APP_PARAM['base']['developer'] = 'HBS';

	/* End of file app_param.php */
	/* Location: ./config/app_param.php */
?>