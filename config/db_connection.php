<?php

	require_once 'db_getconfig.php';	
	require_once 'adodb5/adodb.inc.php';

	//Define Database Connection
	$db_active='mysqli';	

	$db_param['mysqli']['dbserver']= db_getconfig::getConfig('dbhost');
	$db_param['mysqli']['dbport']= db_getconfig::getConfig('dbport');
	$db_param['mysqli']['dbusername']= db_getconfig::getConfig('dbuser');
	$db_param['mysqli']['dbpassword']= db_getconfig::getConfig('dbpassword');
	$db_param['mysqli']['dbname']= db_getconfig::getConfig('dbname');
	

	//MySQL
	$db = ADONewConnection('mysqli');
	$db->NLS_DATE_FORMAT = 'RRRR-MM-DD HH24:MI:SS';
	$db->Connect($db_param[$db_active]['dbserver'],$db_param[$db_active]['dbusername'],$db_param[$db_active]['dbpassword'],$db_param[$db_active]['dbname']);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	
	// Oracle
	/*$db	= ADONewConnection('oci8');
	$db->NLS_DATE_FORMAT = 'RRRR-MM-DD HH24:MI:SS';
	$db->PConnect($db_param[$db_active]['dbserver'].':'.$db_param[$db_active]['dbport'].'/'.$db_param[$db_active]['database'],$db_param[$db_active]['dbusername'],$db_param[$db_active]['dbpassword']);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);*/
	
?>