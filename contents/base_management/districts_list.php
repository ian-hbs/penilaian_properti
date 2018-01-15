<?php
	
	include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";        
    include_once "../../libraries/DML.php";

    $regency_id = $_POST['regency_id'];    
    
    $DML = new DML('ref_districts',$db);
    $sql = "SELECT * FROM ref_districts WHERE regency_id='".$regency_id."'";    
    $data = $DML->fetchData($sql);
    echo "<option value=''></option>";
    foreach($data as $row)
    {
    	echo "<option value='".$row['id']."'>".$row['name']."</option>";
    }
?>