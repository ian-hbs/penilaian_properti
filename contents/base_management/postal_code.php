<?php
	include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";        
    include_once "../../libraries/DML.php";

    $village_id = $_POST['village_id'];    
    $sql = "SELECT postal_code FROM ref_villages WHERE id='".$village_id."'";
    $postal_code = $db->getOne($sql);
    echo $postal_code;
?>