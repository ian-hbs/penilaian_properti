<?php
	$_SYSTEM_PARAMS = $_APP_PARAM['system_params'];
	$list_sql = "SELECT * FROM ref_districts WHERE(regency_id='".$_SYSTEM_PARAMS['kode_dt2']."')";
?>