<?php
	
	include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";        
    include_once "../../libraries/DML.php";

    $x_province_id = explode('_',$_POST['province_id']);
    $province_id = $x_province_id[0];
    
    $DML = new DML('ref_regencies',$db);
    $sql = "SELECT * FROM ref_regencies WHERE province_id='".$province_id."'";
    $data = $DML->fetchData($sql);
    echo "<option value=''></option>";
    foreach($data as $row)
    {
    	echo "<option value='".$row['id']."'>".$row['name']."</option>";
    }
?>