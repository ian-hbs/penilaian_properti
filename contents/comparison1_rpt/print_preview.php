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
?>
<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title><?php echo $_BASE_PARAMS['sys_name_full'];?> - Data Pembanding D.1 Penilaian</title>
    	<link rel="stylesheet" type="text/css" href="../../assets/dist/css/report-style.css"/>    	

  	</head>
  	<body>
      <?php
        if($n_penugasan>0)
        {          
          $checked = array(3,14,15,17);
          $need_entry = $global->get_need_entry($id_penugasan_dec,$checked);

          if($need_entry[0])
          {
            $sql = "SELECT a.*,b.jenis_perusahaan_penunjuk FROM kesimpulan_rekomendasi as a 
                    LEFT JOIN (SELECT c.id_penugasan,d.jenis as jenis_perusahaan_penunjuk FROM penugasan as c LEFT JOIN ref_perusahaan_penunjuk as d ON (c.fk_perusahaan_penunjuk=d.id_perusahaan_penunjuk)) as b 
                    ON (a.fk_penugasan=b.id_penugasan) 
                    WHERE a.fk_penugasan='".$id_penugasan_dec."'";
    
            $result = $db->Execute($sql);
            if(!$result)
              die($db->ErroMsg());
            
            $row = $result->FetchRow();
            
            echo "
        		<div class='header'>
        			<img src='../../uploads/logo/01.jpg' width='36px'/>
        		</div>          
            <div style='border:1px solid #000;margin-top:1.2cm;padding:2px'>
              <h3>D.1. DATA PEMBANDING</h3>
          		<table class='report' cellpadding=0 cellspacing=0>
                <tr>
                  <td width='4%' align='center'><b>No.</b></td>
                  <td align='center'><b>Alamat</b></td>
                  <td align='center'><b>Jenis Properti</b></td>
                  <td align='center'><b>Surat Tanah</b></td>
                  <td align='center'><b>LT (m<sup>2</sup>)</b></td>
                  <td align='center'><b>LB (m<sup>2</sup>)</b></td>
                  <td align='center'><b>Penawaran/Transaksi</b></td>
                </tr>";
                $sql = "SELECT a.land_title,a.land_area,a.building_area,a.offering_price,b.alamat,b.jenis_objek FROM perhitungan_tanah_pembanding as a 
                        LEFT JOIN (SELECT x.id_objek_pembanding,x.alamat,y.jenis_objek FROM objek_pembanding as x 
                        LEFT JOIN ref_jenis_objek as y ON (x.fk_jenis_objek=y.id_jenis_objek)) as b ON (a.fk_objek_pembanding=b.id_objek_pembanding)
                        WHERE (fk_penugasan='".$id_penugasan_dec."')";
                $result = $db->Execute($sql);
                if(!$result)
                  echo $db->ErrorMsg();

                $no=0;
                while($row2 = $result->FetchRow())
                {
                  $no++;
                  echo "<tr><td align='center'>".$no."</td><td>".$row2['alamat']."</td>
                  <td align='center'>".$row2['jenis_objek']."</td>
                  <td align='center'>".$row2['land_title']."</td>
                  <td align='right'>".number_format($row2['land_area'],2,'.',',')."</td>
                  <td align='right'>".number_format($row2['building_area'],2,'.',',')."</td>
                  <td align='right'>".number_format($row2['offering_price'],0,'.',',')."</td>
                  </tr>";
                }
              echo "</table>
            </div>
            <div style='border:1px solid #000;margin-top:10px;padding:2px'>
              <div style='border:1px solid #000;padding:5px'>
                <h3>KESIMPULAN DAN REKOMENDASI</h3>
                <table width='100%'>";
                  $no = 1;
                  echo "
                  <tr>
                    <td width='3%'>".$no.".</td>
                    <td colspan='6'>NILAI PASAR TANAH DAN BANGUNAN SERTA SARANA PELENGKAP ADALAH SEBESAR :</td>
                  </tr>                
                  
                  <tr>
                    <td colspan='3'>&nbsp;</td>
                    <td>NILAI PASAR TANAH</td>
                    <td>Rp.</td>
                    <td align='right'>";
                    $npt = $row['nilai_pasar_tanah'];
                    echo number_format($npt,0,'.',',')."</td>
                    <td width='8%'>&nbsp</td>
                  </tr>
                  <tr>
                    <td colspan='3'>&nbsp;</td>
                    <td>NILAI PASAR BANGUNAN & SARANA PELENGKAP</td>
                    <td>Rp.</td>
                    <td align='right'>";
                    $npb_sp = $row['nilai_pasar_bangunan']+$row['nilai_pasar_sarana_pelengkap'];
                    echo number_format($npb_sp,0,'.',',')."</td>
                    <td>&nbsp</td>
                  </tr>
                  <tr>
                    <td colspan='3'>&nbsp;</td>
                    <td>NILAI OBYEK KESELURUHAN</td>
                    <td>Rp.</td>
                    <td align='right'>";
                    $nok = $npt+$npb_sp;
                    echo number_format($nok,0,'.',',')."</td>
                    <td>&nbsp</td>
                  </tr>
                  <tr>
                    <td colspan='3'>&nbsp;</td>
                    <td>NILAI PASAR</td>
                    <td><b>Rp.</b></td>
                    <td align='right'><b>".number_format($row['pembulatan_pasar_objek'],0,'.',',')."</b></td>
                    <td>&nbsp</td>
                  </tr>
                  <tr>
                    <td colspan='7'><br /></td>
                  </tr>";

                  if($row['jenis_perusahaan_penunjuk']=='2')
                  {
                    $no++;
                    echo "
                    <tr>
                      <td width='3%'>".$no.".</td>
                      <td colspan='6'>NILAI PASAR SETELAH SAFETY MARGIN UNTUK TANAH DAN BANGUNAN ADALAH SEBESAR :</td>
                    </tr>                
                    
                    <tr>
                      <td colspan='3'>&nbsp;</td>
                      <td>NILAI PASAR TANAH SETELAH SAFETYMARGIN</td>
                      <td>Rp.</td>
                      <td align='right'>";
                      $nsmt = $row['nilai_safetymargin_tanah'];
                      echo number_format($nsmt,0,'.',',')."</td>
                      <td width='8%'>&nbsp</td>
                    </tr>
                    <tr>
                      <td colspan='3'>&nbsp;</td>
                      <td>NILAI PASAR BANGUNAN SETELAH SAFETYMARGIN</td>
                      <td>Rp.</td>
                      <td align='right'>";
                      $nsmb = $row['nilai_safetymargin_bangunan'];
                      echo number_format($nsmb,0,'.',',')."</td>
                      <td>&nbsp</td>
                    </tr>
                    <tr>
                      <td colspan='3'>&nbsp;</td>
                      <td>NILAI OBYEK KESELURUHAN</td>
                      <td>Rp.</td>
                      <td align='right'>";
                      $nok = $nsmt+$nsmb;
                      echo number_format($nok,0,'.',',')."</td>
                      <td>&nbsp</td>
                    </tr>
                    <tr>
                      <td colspan='3'>&nbsp;</td>
                      <td>NILAI PASAR SETELAH SAFETY MARGIN</td>
                      <td><b>Rp.</b></td>
                      <td align='right'><b>".number_format($row['pembulatan_safetymargin_objek'],0,'.',',')."</b></td>
                      <td>&nbsp</td>
                    </tr>
                    <tr>
                      <td colspan='7'><br /></td>
                    </tr>";
                  }

                  $no++;
                  echo "
                  <tr>
                    <td>".$no.".</td>
                    <td colspan='6'>INDIKASI NILAI LIKUIDASI ATAS TANAH DAN BANGUNAN ADALAH SEBESAR (PEMBULATAN) :</td>
                  </tr>
                  <tr>
                    <td colspan='3'>&nbsp;</td>
                    <td>INDIKASI NILAI LIKUIDASI</td>
                    <td><b>Rp.</b></td>
                    <td align='right'><b>".number_format($row['pembulatan_likuidasi_objek'],0,'.',',')."</b></td>
                    <td>&nbsp</td>
                  </tr>
                  <tr>
                    <td colspan='7'><br /></td>
                  </tr>";

                  $no++;
                  echo "
                  <tr>
                    <td>".$no.".</td>
                    <td colspan='6'>FAKTOR YANG DAPAT MENAMBAH NILAI :</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>Tanah</td><td width='1%'>:</td>
                    <td>".$row['faktor_penambah_nilai_tanah']."</td>
                    <td colspan='3'></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>Bangunan</td><td width='1%'>:</td>
                    <td>".$row['faktor_penambah_nilai_bangunan']."</td>
                    <td colspan='3'></td>
                  </tr>
                  <tr>
                    <td colspan='7'><br /></td>
                  </tr>";

                  $no++;
                  echo "
                  <tr>
                    <td>".$no.".</td>
                    <td colspan='6'>FAKTOR YANG DAPAT MENGURANGI NILAI :</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>Tanah</td><td width='1%'>:</td>
                    <td>".$row['faktor_pengurang_nilai_tanah']."</td>
                    <td colspan='3'></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>Bangunan</td><td width='1%'>:</td>
                    <td>".$row['faktor_pengurang_nilai_bangunan']."</td>
                    <td colspan='3'></td>
                  </tr>
                </table>
                <br />
                <u>Catatan Penilai</u>";
                $sql = "SELECT * FROM catatan_penilai WHERE fk_penugasan='".$id_penugasan_dec."'";
                $result = $db->Execute($sql);
                if(!$result)
                  echo $db->ErrorMsg();
                echo "<ul style='margin:0 0 0 15px;padding:0'>";
                while($row2 = $result->FetchRow())
                {
                  echo "<li>".$row2['catatan']."</li>";
                }
              echo "</ul></div>
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
