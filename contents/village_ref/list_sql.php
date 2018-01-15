<?php
	$_SYSTEM_PARAMS = $_APP_PARAM['system_params'];
	$list_sql = "SELECT a.id,a.name,a.postal_code,b.name as district_name FROM ref_villages as a LEFT JOIN ref_districts as b ON (a.district_id=b.id)
	             WHERE(substr(a.district_id,1,4)='".$_SYSTEM_PARAMS['kode_dt2']."')";
  
?>