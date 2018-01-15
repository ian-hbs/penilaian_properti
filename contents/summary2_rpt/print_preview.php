<?php
	session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../config/app_param.php";    
    include_once "../../libraries/cipher.php";
    include_once "../../libraries/global_obj.php";
    

    //instance object
    $cipher = new cipher(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $global = new global_obj($db);

    $dec_key = "+^?:^&%*S!3!c!12!31T";
    $id_penugasan = urldecode($_GET['id']);
    $id_penugasan_dec = $cipher->decrypt($id_penugasan,$dec_key);    

    $sql = "SELECT COUNT(1) n_penugasan FROM penugasan WHERE id_penugasan='".$id_penugasan_dec."'";
    $n_penugasan = $db->GetOne($sql);

    $_BASE_PARAMS = $_APP_PARAM['base'];
    $_SYSTEM_PARAMS = $_APP_PARAM['system_params'];
?>
<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title><?php echo $_BASE_PARAMS['sys_name_full'];?> - Ringkasan Laporan Penilaian</title>
    	<link rel="stylesheet" type="text/css" href="../../assets/dist/css/report-style.css"/>    	
      <style type="text/css">
        .border{border:1px solid #000;}
        table{width:100%;}        
      </style>
  	</head>
  	<body>
      <?php
      if($n_penugasan>0)
      {        
        $checked = array(3,14,15,17);
        $need_entry = $global->get_need_entry($id_penugasan_dec,$checked);        

        if($need_entry[0])
        {
          $sql = "SELECT a.perusahaan_penilai,a.nama_reviewer1,a.mappi_reviewer1,a.ijin_penilai_reviewer1,a.jenis_perusahaan_penunjuk,
            b.alamat,b.kelurahan,b.kecamatan,b.kota,b.provinsi,c.nama as nama_debitur,d.*
            FROM 
            (SELECT x.id_penugasan,x.perusahaan_penilai,y.nama as nama_reviewer1,y.no_mappi as mappi_reviewer1,y.ijin_penilai as ijin_penilai_reviewer1,
            z.jenis as jenis_perusahaan_penunjuk FROM penugasan as x
            LEFT JOIN ref_penilai as y ON (x.reviewer1=y.id_penilai)
            LEFT JOIN ref_perusahaan_penunjuk as z ON (x.fk_perusahaan_penunjuk=z.id_perusahaan_penunjuk)) as a, 
            properti as b,            
            debitur as c,
            kesimpulan_rekomendasi as d
            WHERE(a.id_penugasan=b.fk_penugasan AND a.id_penugasan=c.fk_penugasan AND a.id_penugasan=d.fk_penugasan)
            AND (a.id_penugasan='".$id_penugasan_dec."')";

          $result = $db->Execute($sql);
          if(!$result)
            die($db->ErrorMsg());
          
          $row = $result->FetchRow();
          
          echo "
      		<div class='header'>
            <img src='../../uploads/logo/01.png' width='36px'/>
          </div>
          <div style='margin-top:1cm;'>
      		  <center><h3>Ringkasan Penilaian</h3></center><br />
            <table cellpadding=0 cellspacing=0>
              <tr>
                <td width='18%'>Calon Debitur</td><td width='1%'>:</td>
                <td><b>".$row['nama_debitur']."</b></td>
              </tr>
              <tr>
                <td>Alamat Properti</td><td>:</td>
                <td>".$row['alamat'].", Kelurahan ".$row['kelurahan'].", Kecamatan ".$row['kecamatan'].", Kota ".$row['kota'].",
                Provinsi ".$row['provinsi']."</td>
              </tr>
            </table><br />
            <table class='report' cellpadding=0 cellspacing=0>
              <tr>
                <td colspan='4' style='font-weight:bold;background:#e5e5e5;' align='center' valign='middle'>OBYEK</td>
                <td colspan='2' style='font-weight:bold;background:#e5e5e5;' align='center' valign='middle'>NILAI PASAR</td>";
                if($row['jenis_perusahaan_penunjuk']=='2')
                  echo "<td colspan='2' style='font-weight:bold;background:#e5e5e5;' align='center' valign='middle'>NILAI PASAR<br />SETELAH SAFETY MARGIN</td>";
                echo "<td colspan='2' style='font-weight:bold;background:#e5e5e5;' align='center' valign='middle'>INDIKASI NILAI LIKUIDASI</td>
              </tr>
              <tr>
                <td><b>a.</b></td>
                <td style='border-left:none'><b>Tanah</b></td>
                <td align='right' style='border-left:none'><b>".number_format($row['luas_tanah'],2,'.',',')."</b></td>
                <td align='center' style='border-left:none'>m<sup>2</sup></td>
                <td width='5%'>Rp.</td><td align='right' style='border-left:none'>".number_format($row['nilai_pasar_tanah'])."</td>";
                if($row['jenis_perusahaan_penunjuk']=='2')
                  echo "<td width='5%'>Rp.</td><td align='right' style='border-left:none'>".number_format($row['nilai_safetymargin_tanah'])."</td>";

                echo "<td width='5%'>Rp.</td><td align='right' style='border-left:none'>".number_format($row['nilai_likuidasi_tanah'])."</td>
              </tr>
              <tr>
                <td><b>b.</b></td>
                <td style='border-left:none'><b>Bangunan</b></td>
                <td align='right' style='border-left:none'><b>".number_format($row['luas_bangunan'],2,'.',',')."</b></td>
                <td align='center' style='border-left:none'>m<sup>2</sup></td>
                <td>Rp.</td><td align='right' style='border-left:none'>".number_format($row['nilai_pasar_bangunan'])."</td>";
                if($row['jenis_perusahaan_penunjuk']=='2')
                  echo "<td width='5%'>Rp.</td><td align='right' style='border-left:none' width='25%'>".number_format($row['nilai_safetymargin_bangunan'])."</td>";

                echo "
                <td>Rp.</td><td align='right' style='border-left:none'>".number_format($row['nilai_likuidasi_bangunan'])."</td>
              </tr>";

              if($row['jenis_perusahaan_penunjuk']=='1')
              {
                echo "
                <tr>
                  <td><b>c.</b></td>
                  <td colspan='3' style='border-left:none'><b>Sarana Pelengkap</b></td>              
                  <td>Rp.</td><td align='right' style='border-left:none'>".number_format($row['nilai_pasar_sarana_pelengkap'])."</td>
                  <td>Rp.</td><td align='right' style='border-left:none'>".number_format($row['nilai_likuidasi_sarana_pelengkap'])."</td>
                </tr>";
              }
              echo "
              <tr>
                <td colspan='4' style='font-weight:bold;background:#e5e5e5;'>Nilai Properti</td>
                <td><b>Rp.</b></td><td align='right' style='border-left:none'><b>".number_format($row['nilai_pasar_objek'])."</b></td>";
                if($row['jenis_perusahaan_penunjuk']=='2')
                  echo "<td><b>Rp.</b></td><td align='right' style='border-left:none'><b>".number_format($row['nilai_safetymargin_objek'])."</b></td>";

                echo "<td><b>Rp.</td><td style='border-left:none' align='right'><b>".number_format($row['nilai_likuidasi_objek'])."</b></td>
              </tr>
              <tr>
                <td colspan='4' style='font-weight:bold;background:#e5e5e5;'>Pembulatan</td>
                <td><b>Rp.</b></td><td align='right' style='border-left:none'><b>".number_format($row['pembulatan_pasar_objek'])."</b></td>";
                if($row['jenis_perusahaan_penunjuk']=='2')
                  echo "<td><b>Rp.</b></td><td align='right' style='border-left:none'><b>".number_format($row['pembulatan_safetymargin_objek'])."</b></td>";

                echo "
                <td><b>Rp.</td><td style='border-left:none' align='right'><b>".number_format($row['pembulatan_likuidasi_objek'])."</b></td>
              </tr>
            </table>

            <br /><br />
            <div>Hormat Kami,<br />
              <b>".strtoupper($row['perusahaan_penilai'])."</b><br />
              <small><i>Registered Public Appraisers and Consultants</i></small>
              <br /><br /><br /><br /><br />
              <b><u>".$row['nama_reviewer1']."</u></b><br />
              <b>Partner</b><br />
              Ijin Penilai Properti : ".$row['ijin_penilai_reviewer1']."<br />
              MAPPI : ".$row['mappi_reviewer1']."
            </div>
          </div>";
        }
        else
        {
          $err_msg = "Data pada form ";
          $s = false;
          foreach($need_entry[1] as $val)
          {
            $err_msg .= ($s?", ":"")."(".$val.")";
            $s = true;
          }
          $err_msg .= ' belum diinput.';
          echo "<br />
          <center>".$err_msg."<br />
          Silahkan lengkapi terlebih dahulu!";
        }
      }
      else
      {
        echo "<br />
              <center>
                Data tidak ditemukan!
              </center>";
      }
      ?>
  	</body>
</html>

