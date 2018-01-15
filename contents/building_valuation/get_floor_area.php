<?php
	
	include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";        
    
    $fk_penugasan = $_POST['fk_penugasan'];
    $built_year = $_POST['built_year'];
    
    $sql = "SELECT total FROM luas_bangunan WHERE((fk_penugasan='".$fk_penugasan."') AND (tahun_bangun='".$built_year."'))";

    $result = $db->Execute($sql);    
    $tot_floor_area = 0;
    $floor_area = 0;
    
    while($row = $result->FetchRow())
    {
        $sql = "SELECT COUNT(1) n_perhitungan_bangunan FROM perhitungan_bangunan WHERE(fk_penugasan='".$fk_penugasan."') AND (floor_area='".$row['total']."')";
        $n_perhitungan_bangunan = $db->GetOne($sql);
        
        if((($floor_area==$row['total']) || $tot_floor_area==0) && ($n_perhitungan_bangunan==0))
        {
            $tot_floor_area += $row['total'];
        }
        $floor_area = $row['total'];
    }

    echo $tot_floor_area;

?>