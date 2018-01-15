<?php

	$cfn = $_CONTENT_FOLDER_NAME;
	$page='';
	if(!isset($_GET['ct']))
	{
		$page='contents/'.$cfn[0].'/main.php';
	}
	else
	{		
		$ct = $_GET['ct'];
		$min=1;
		$max=52;

		$arrCT = array();
		for($i=$min;$i<=$max;$i++)
		{
			$arrCT[$i-1] = $i;
		}

		$page = 'contents/error404/main.php';
		if(in_array($ct,$arrCT))
		{
			for($i=$min;$i<=$max;$i++)
			{
				if($i==$ct)
				{					
					$page='contents/'.$cfn[$i].'/main.php';
					break;
				}
			}
		}		
	}
	include_once $page;

	// EOF content_manager.php
	// Location : ./backoffice/content_manager.php 
	
?>

