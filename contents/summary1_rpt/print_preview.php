<?php
  session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../config/app_param.php";
    include_once "../../libraries/cipher.php";
    include_once "../../libraries/global_obj.php";
    include_once "../../helpers/date_helper.php";
    include_once "../../helpers/mix_helper.php";    

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
        .img-footer{
            position:absolute;bottom:0;border:1px solid #000;width:100%;height:0.8cm;text-align:center;font-weight:bold;
            overflow:hidden;
        }
      </style>
    </head>
    <body>    
      <?php
      if($n_penugasan>0)
      {
        $checked = array(3,11,14,15,17);
        $need_entry = $global->get_need_entry($id_penugasan_dec,$checked);
      
        if($need_entry[0])
        {
          $sql = "SELECT a.perusahaan_penilai,a.no_penugasan,a.tgl_penugasan,a.no_laporan,a.tgl_laporan,a.tgl_survei,a.perusahaan_penunjuk,a.jenis_perusahaan_penunjuk,a.kantor_cabang,
            a.alamat_perusahaan_penunjuk,a.kota_perusahaan_penunjuk,a.kode_pos_perusahaan_penunjuk,a.nama_reviewer1,a.mappi_reviewer1,a.ijin_penilai_reviewer1,
            b.alamat,b.kelurahan,b.kecamatan,b.kota,b.provinsi,b.perancang_foto_properti,b.skala_foto_properti,
            c.tgl_pemeriksaan,d.nama as nama_debitur,e.*
            FROM (SELECT x.id_penugasan,x.perusahaan_penilai,x.no_penugasan,x.tgl_penugasan,x.no_laporan,x.tgl_laporan,x.tgl_survei,
            y.jenis as jenis_perusahaan_penunjuk,y.perusahaan_penunjuk,y.kantor_cabang,y.alamat as alamat_perusahaan_penunjuk,y.kota as kota_perusahaan_penunjuk,
            y.kode_pos as kode_pos_perusahaan_penunjuk,z.nama as nama_reviewer1,z.no_mappi as mappi_reviewer1,z.ijin_penilai as ijin_penilai_reviewer1 FROM penugasan as x, 
            ref_perusahaan_penunjuk as y, ref_penilai as z 
            WHERE (x.fk_perusahaan_penunjuk=y.id_perusahaan_penunjuk) AND (x.reviewer1=z.id_penilai)) as a, 
            properti as b,
            pemeriksaan as c,
            debitur as d,
            kesimpulan_rekomendasi as e
            WHERE(a.id_penugasan=b.fk_penugasan AND a.id_penugasan=c.fk_penugasan AND a.id_penugasan=d.fk_penugasan AND a.id_penugasan=e.fk_penugasan)
            AND (a.id_penugasan='".$id_penugasan_dec."')";

          $result = $db->Execute($sql);
          if(!$result)
            die($db->ErrorMsg());
          
          $row = $result->FetchRow();
          
          echo "              
          <div>
            Kepada Yth.<br />
            <b>Kepala Cabang<br />
            ".$row['perusahaan_penunjuk']."</b><br />
            ".$row['alamat_perusahaan_penunjuk']."<br />
            ".$row['kota_perusahaan_penunjuk']." ".$row['kode_pos_perusahaan_penunjuk']."
          </div><br />
          <table width='100%' style='font-weight:bold'>
          <tr><td width='5%'>No</td><td>: ".$row['no_laporan']."</td></tr>
          <tr><td>Hal</td><td>: <u>Ringkasan Hasil Penilaian</u></td></tr>
          </table><br />

          <p>
          Dengan hormat,<br />
          Sesuai dengan penugasan, kami telah melakukan inspeksi lapangan dan penilaian properti sebagai berikut :
          </p>
          <table>
            <tr>
              <td width='25%'>Nomor Penugasan</td><td width='1%'>:</td><td>".$row['no_penugasan']."</td>
            </tr>
            <tr>
              <td>Tanggal Penugasan</td><td>:</td><td>".indo_date_format($row['tgl_penugasan'],'longDate')."</td>
            </tr>
            <tr>
              <td>Inspeksi Lapangan</td><td>:</td><td>".indo_date_format($row['tgl_pemeriksaan'],'longDate')."</td>
            </tr>
            <tr>
              <td>Tanggal Penilaian</td><td>:</td><td>".indo_date_format($row['tgl_pemeriksaan'],'longDate')."</td>
            </tr>
            <tr>
              <td>Alamat Properti</td><td>:</td><td>".$row['alamat'].", Kelurahan ".$row['kelurahan'].", Kecamatan ".$row['kecamatan'].", Kota 
              ".$row['kota'].", Provinsi ".$row['provinsi']."</td>
            </tr>
            <tr>
              <td>Calon Debitur Atas Nama</td><td>:</td><td><b>".$row['nama_debitur']."</b></td>
            </tr>
          </table>
          
          <div style='border:1px solid #000;margin-top:5px;text-align:center'>
            <h3>FOTO PROPERTI</h3>              
          </div>";
          
          $sql = "SELECT * FROM foto_properti WHERE fk_penugasan='".$id_penugasan_dec."' limit 0,4";
          $result = $db->Execute($sql);
          if(!$result)
            echo $db->ErrorMsg();
          $photos = array();
          $i=0;
          $j=0;
          while($row2 = $result->FetchRow())
          {
            $photos[$i][$j] = array('file_foto'=>$row2['file_foto'],'keterangan'=>$row2['keterangan']);
            if(($j+1)%2==0)
            {
              $j=0;
              $i++;
            }
            else
              $j++;
          }

          echo "
          <table style='width:19cm;' cellpadding=0 cellspacing=0>";
          
          foreach($photos as $key1=>$val1)
          {              

            echo "<tr>";
            foreach($val1 as $key2=>$val2)
            {
              echo "<td width='50%' style='height:6.5cm;position:relative;' valign='top'>
                <img src='../../uploads/property_photos/".$val2['file_foto']."' width='100%' height='86%'/>
                <div class='img-footer'>
                  ".$val2['keterangan']."
                </div>
              </td>
              </td>";
            }
            echo "</tr>";              
          }

          echo "
          </table>
          <h3 style='margin:10px 0 5px 0!important'><u>RINGKASAN</u></h3>
          <table class='report main' cellpadding=0 cellspacing=0>          
            <tr>
              <td colspan='3' align='center' valign='middle' style='font-weight:bold;background:#e5e5e5;'>OBJEK</td>
              <td colspan='2' align='center' valign='middle' style='font-weight:bold;background:#e5e5e5;'>NILAI PASAR</td>
              <td colspan='2' align='center' valign='middle' style='font-weight:bold;background:#e5e5e5;'>RATA-RATA/M<sup>2</sup><br />(Rp.)</td>";
              if($row['jenis_perusahaan_penunjuk']=='2')
                echo "<td colspan='2' align='center' valign='middle' style='font-weight:bold;background:#e5e5e5;'>NILAI PASAR<br />SETELAH SAFETY MARGIN</td>";

              echo "<td colspan='2' align='center' valign='middle' style='font-weight:bold;background:#e5e5e5;'>INDIKASI<br />NILAI LIKUIDASI</td>
              <td colspan='2' align='center' valign='middle' style='font-weight:bold;background:#e5e5e5;'>RATA-RATA/M<sup>2</sup><br />(Rp.)</td>
            </tr>
            <tr>
              <td><b>a.&nbsp;Tanah</b></td>
              <td align='right' style='border-left:none'>".number_format($row['luas_tanah'],2,'.',',')."</td>
              <td align='center' style='border-left:none'>m<sup>2</sup></td>
              <td>Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_pasar_tanah'])."</td>
              <td>@Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_satuan_tanah'])."</td>";
              if($row['jenis_perusahaan_penunjuk']=='2')
                echo "<td>Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_safetymargin_tanah'])."</td>";

              echo "<td>Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_likuidasi_tanah'])."</td>
              <td>@Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_satuan_likuidasi_tanah'])."</td>
            </tr>
            <tr>
              <td><b>a.&nbsp;Bangunan</b></td>
              <td align='right' style='border-left:none'>".number_format($row['luas_bangunan'],2,'.',',')."</td>
              <td align='center' style='border-left:none'>m<sup>2</sup></td>
              <td>Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_pasar_bangunan'])."</td>
              <td>@Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_satuan_bangunan'])."</td>";
              if($row['jenis_perusahaan_penunjuk']=='2')
                echo "<td>Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_safetymargin_bangunan'])."</td>";

              echo "
              <td>Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_likuidasi_bangunan'])."</td>
              <td>@Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_satuan_likuidasi_bangunan'])."</td>
            </tr>";

            if($row['jenis_perusahaan_penunjuk']=='1')
            {
              echo "              
              <tr>
                <td><b>b.&nbsp;Sarana Pelengkap</b></td>
                <td style='border-left:none'></td>
                <td style='border-left:none'></td>
                <td>Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_pasar_sarana_pelengkap'])."</td>
                <td></td><td style='border-left:none'></td><td>Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_likuidasi_sarana_pelengkap'])."</td>
                <td></td><td style='border-left:none'></td>
              </tr>";
            }
            echo "
            <tr>
              <td colspan='3'><b>Nilai Properti</b></td>
              <td style='background:#d1d1d1;'><b>Rp.</b></td><td style='border-left:none;background:#d1d1d1;' align='right'><b>".number_format($row['nilai_pasar_objek'])."</b></td>
              <td></td><td style='border-left:none'></td>";
              if($row['jenis_perusahaan_penunjuk']=='2')
                echo "<td style='background:#d1d1d1;'><b>Rp.</b></td><td style='border-left:none;background:#d1d1d1;' align='right'><b>".number_format($row['nilai_safetymargin_objek'])."</b></td>";
              
              echo "<td style='background:#d1d1d1;'><b>Rp.</b></td><td style='border-left:none;background:#d1d1d1;' align='right'><b>".number_format($row['nilai_likuidasi_objek'])."</b></td>
              <td></td><td style='border-left:none'></td>
            </tr>
            <tr>
              <td colspan='3'><b>Pembulatan</b></td>
              <td style='background:#d1d1d1;'><b>Rp.</b></td><td style='border-left:none;background:#d1d1d1;' align='right'><b>".number_format($row['pembulatan_pasar_objek'])."</b></td>
              <td></td><td style='border-left:none'></td>";
              if($row['jenis_perusahaan_penunjuk']=='2')
                echo "<td style='background:#d1d1d1;'><b>Rp.</b></td><td style='border-left:none;background:#d1d1d1;' align='right'><b>".number_format($row['pembulatan_safetymargin_objek'])."</b></td>";
              
              echo "
              <td style='background:#d1d1d1;'><b>Rp.</b></td><td style='border-left:none;background:#d1d1d1;' align='right'><b>".number_format($row['pembulatan_likuidasi_objek'])."</b></td>
              <td></td><td style='border-left:none'></td>
            </tr>          
          </table><br />
          Catatan :<br />
          Berdasarkan surat penugasan No. ".$row['no_penugasan']." alamat properti berada di ".$row['alamat']." Kelurahan ".$row['kelurahan'].", 
          Kecamatan ".$row['kecamatan']." ".$row['kota'].". Pada saat inspeksi lapangan, alamat lengkap properti berada di ".$row['alamat']." Kelurahan ".$row['kelurahan'].", 
          Kecamatan ".$row['kecamatan'].", Kota ".$row['kota'].", Provinsi ".$row['provinsi']."
          <br /><br />
          <div>Hormat Kami,<br />
            <b>".strtoupper($row['perusahaan_penilai'])."</b><br />
            <small><i>Registered Public Appraisers and Consultants</i></small>
            <br /><br /><br /><br /><br />
            <b><u>".$row['nama_reviewer1']."</u></b><br />
            <b>Partner</b><br />
            Ijin Penilai Properti : ".$row['ijin_penilai_reviewer1']."<br />
            MAPPI : ".$row['mappi_reviewer1']."
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
