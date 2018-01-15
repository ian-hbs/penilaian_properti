<?php

    if(isset($_POST['object_type']))
    {
        include_once "../../config/superglobal_var.php";
        include_once "../../config/db_connection.php";
        
        $ot = $_POST['object_type'];
        $bc = $_POST['building_classification'];
    }
	
    $sql = "SELECT fk_jenis_komponen_bangunan,nilai_1lantai,nilai_2lantai,nilai_3lantai,nilai_4lantai,nilai_nlantai FROM ref_nilai_komponen_bangunan 
    		WHERE(fk_jenis_objek='".$ot."' OR fk_jenis_objek='5') AND (fk_klasifikasi_bangunan='".$bc."' or fk_klasifikasi_bangunan='8')";
    $result = $db->Execute($sql);
    if(!$result)
    	die('ERROR: terjadi kesalahan saat mengambil data dari server!');

    $bcv = array();
    while($row = $result->FetchRow())
    {
    	$bcv[$row['fk_jenis_komponen_bangunan']] = array('1'=>$row['nilai_1lantai'],
							    				         '2'=>$row['nilai_2lantai'],
							    				         '3'=>$row['nilai_3lantai'],
							    				         '4'=>$row['nilai_4lantai'],
							    				         'n'=>$row['nilai_nlantai']);
    }
    
    $sql = "SELECT nilai_1lantai,nilai_2lantai,nilai_3lantai,nilai_4lantai,nilai_nlantai FROM ref_tarif_imb WHERE(fk_jenis_objek='".$ot."')";
    $result = $db->Execute($sql);
    if(!$result)
        die('ERROR: terjadi kesalahan saat mengambil data dari server!');
    
    $imb = array();
    if($result->RecordCount()>0)
    {
        $row = $result->FetchRow();
        $imb = array('1'=>$row['nilai_1lantai'],'2'=>$row['nilai_2lantai'],'3'=>$row['nilai_3lantai'],'4'=>$row['nilai_4lantai'],'n'=>$row['nilai_nlantai']);
    }

    $bcv_imb = json_encode(array('bcv'=>$bcv,'imb'=>$imb));
    
    if(isset($_POST['object_type']))
        echo $bcv_imb;

?>