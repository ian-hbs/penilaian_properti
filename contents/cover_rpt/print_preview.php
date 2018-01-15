<?php
	session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/cipher.php";    
    include_once "../../config/app_param.php";  	

    //instance object
    $cipher = new cipher(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);

    $dec_key = "+^?:^&%*S!3!c!12!31T";
    $id_penugasan = urldecode($_GET['id']);
    $id_penugasan_dec = $cipher->decrypt($id_penugasan,$dec_key);    

    $sql = "SELECT a.no_laporan,b.nama as nama_debitur,c.alamat,c.kelurahan,c.kecamatan,c.kota,c.provinsi FROM penugasan as a, debitur as b, properti as c
    		WHERE(a.id_penugasan=b.fk_penugasan AND a.id_penugasan=c.fk_penugasan) and (a.id_penugasan='".$id_penugasan_dec."')";
    
    $result = $db->Execute($sql);
    if(!$result)
    	die($db->ErroMsg());
    $n_row = $result->RecordCount();
    
    if($n_row>0)
      $row = $result->FetchRow();

    $_BASE_PARAMS = $_APP_PARAM['base'];
?>
<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title><?php echo $_BASE_PARAMS['sys_name_full'];?> - Sampul Laporan Penilaian</title>
    	<link rel="stylesheet" type="text/css" href="../../assets/dist/css/report-style.css"/>    	

  	</head>
  	<body>
      <?php
      if($n_row>0)
      {

        echo "

    		<div class='header'>
    			<img src='../../uploads/logo/01.jpg' width='36px'/>
    		</div>
    		<div class='center-box'>
  				".$row['no_laporan']."<br />
  				LAPORAN PENILAIAN PROPERTI<br /><br />
  				NAMA CALON DEBITUR</br>
  				<h2>".strtoupper($row['nama_debitur'])."</h2><br />
  				BERLOKASI DI<br />
  				<p style='font-weight:normal'>".$row['alamat'].", Kelurahan ".$row['kelurahan'].", 
  				Kecamatan ".$row['kecamatan'].", Kota ".$row['kota'].", 
  				Provinsi ".$row['provinsi']."</p>
  		  </div>";
      }
      else
        echo "<br /><center>Data tidak ditemukan!</center>";
      ?>
  	</body>
</html>
