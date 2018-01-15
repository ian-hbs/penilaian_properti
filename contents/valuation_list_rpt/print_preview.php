<?php
	session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../config/app_param.php";
    include_once "../../libraries/cipher.php";    
    include_once "../../helpers/date_helper.php";

    //instance object
    $cipher = new cipher(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);

    $dec_key = "+^?:^&%*S!3!c!12!31T";
    $dasar = str_replace(' ','+',urldecode($_GET['dasar']));
    $tgl1 = str_replace(' ','+',urldecode($_GET['tgl1']));
    $tgl2 = str_replace(' ','+',urldecode($_GET['tgl2']));  

    $tgl1_dec = $cipher->decrypt($tgl1,$dec_key);
    $tgl2_dec = $cipher->decrypt($tgl2,$dec_key);
    $dasar_dec = $cipher->decrypt($dasar,$dec_key);

    $sql = "SELECT a.id_penugasan,a.no_penugasan,DATE_FORMAT(a.tgl_penugasan,'%d-%m-%Y') as tgl_penugasan, DATE_FORMAT(a.tgl_survei,'%d-%m-%Y') as tgl_survei,
             a.no_laporan,DATE_FORMAT(a.tgl_laporan,'%d-%m-%Y') as tgl_laporan,b.nama,c.alamat,c.kelurahan,c.kecamatan,
             d.pembulatan_pasar_objek,d.pembulatan_likuidasi_objek FROM penugasan as a 
            LEFT JOIN debitur as b ON (a.id_penugasan=b.fk_penugasan)
            LEFT JOIN properti as c ON (a.id_penugasan=c.fk_penugasan)
            LEFT JOIN kesimpulan_rekomendasi as d ON (a.id_penugasan=d.fk_penugasan)
            WHERE (a.".$dasar_dec." BETWEEN '".$tgl1_dec."' AND '".$tgl2_dec."')";
    
    $result = $db->Execute($sql);
    if(!$result)
    	die($db->ErroMsg());    

    $_BASE_PARAMS = $_APP_PARAM['base'];
?>
<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title><?php echo $_BASE_PARAMS['sys_name_full'];?> - Daftar Hasil Penilaian</title>
    	<link rel="stylesheet" type="text/css" href="../../assets/dist/css/report-style.css"/>    	

  	</head>
  	<body>
      <?php      
        echo "
    		<div class='header'>
    			<img src='../../uploads/logo/01.png' width='36px'/>
    		</div>
        <div style='margin-top:1cm;'>
        <center><h3><u>DAFTAR HASIL PENILAIAN</u></h3>
        Periode : ".mix_2Date($tgl1_dec,$tgl2_dec)."
        </center><br />
        <table class='report' cellpadding=0 cellspacing=0>
          <thead>
            <tr>
              <th width='4%'>No.</th>
              <th>No. Penugasan</th>
              <th>Tgl. Penugasan</th>
              <th>Tgl. Survei</th>
              <th>No. Laporan</th>
              <th>Tgl. Laporan</th>
              <th>Nama Debitur</th>
              <th>Alamat Properti</th>
              <th>Nilai Pasar</th>
              <th>Nilai Likuidasi</th>
            </tr>
          </thead>
          <tbody>";
            $no=0;
            
            while($row = $result->FetchRow())
            {
                $no++;
                foreach($row as $key => $val){
                    $key=strtolower($key);
                    $$key=$val;
                }            
                echo "
                <tr>
                  <td align='center'>".$no."</td>
                  <td>".$no_penugasan."</td>
                  <td>".$tgl_penugasan."</td>
                  <td>".$tgl_survei."</td>
                  <td>".$no_laporan."</td>
                  <td>".$tgl_laporan."</td>
                  <td>".$nama."</td>
                  <td>".$alamat.", Kel. ".$kelurahan.", Kec. ".$kecamatan."</td>              
                  <td align='right'>".number_format($pembulatan_pasar_objek)."</td>
                  <td align='right'>".number_format($pembulatan_likuidasi_objek)."</td>
                </tr>
                ";
            }
          echo "</tbody>
        </table>
        </div>
    		";
      ?>      
  	</body>
</html>
