<?php
	
	include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";        
    include_once "../../libraries/DML.php";

    $district_id = $_POST['district_id'];    
    
    $DML = new DML('ref_villages',$db);
    $sql = "SELECT * FROM ref_villages WHERE district_id='".$district_id."'";    
    $data = $DML->fetchData($sql);
    echo "<option value=''></option>";
    foreach($data as $row)
    {
    	echo "<option value='".$row['id']."'>".$row['name']."</option>";
    }
?>