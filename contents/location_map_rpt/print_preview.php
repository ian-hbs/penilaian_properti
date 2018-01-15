<?php
  session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../config/app_param.php";
    include_once "../../libraries/cipher.php";
    include_once "../../helpers/date_helper.php";
    include_once "../../helpers/mix_helper.php";    

    //instance object
    $cipher = new cipher(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);

    $dec_key = "+^?:^&%*S!3!c!12!31T";
    $id_penugasan = urldecode($_GET['id']);
    $id_penugasan_dec = $cipher->decrypt($id_penugasan,$dec_key);    

    $sql = "SELECT a.no_laporan,a.perusahaan_penunjuk,a.kantor_cabang,a.nama_reviewer1,
            b.alamat,b.kelurahan,b.kecamatan,b.kota,b.provinsi,b.perancang_peta_lokasi,b.skala_peta_lokasi,
            c.tgl_pemeriksaan
            FROM (SELECT x.id_penugasan,x.no_laporan,y.perusahaan_penunjuk,y.kantor_cabang,z.nama as nama_reviewer1 FROM penugasan as x, ref_perusahaan_penunjuk as y, ref_penilai as z 
            WHERE (x.fk_perusahaan_penunjuk=y.id_perusahaan_penunjuk) AND (x.reviewer1=z.id_penilai)) as a, 
            properti as b,
            pemeriksaan as c            
            WHERE(a.id_penugasan=b.fk_penugasan AND a.id_penugasan=c.fk_penugasan)
            AND (a.id_penugasan='".$id_penugasan_dec."')";

    $result = $db->Execute($sql);
    if(!$result)
      die($db->ErrorMsg());
    $n_row = $result->RecordCount();
    if($n_row>0)
      $row = $result->FetchRow();

    $sql = "SELECT jenis,file_foto,keterangan FROM peta_lokasi WHERE(fk_penugasan='".$id_penugasan_dec."') AND (jenis='peta2' OR jenis='peletakan_tanah' OR jenis='peletakan_bangunan')";
    $result = $db->Execute($sql);
    if(!$result)
      echo $db->ErrorMsg();
    
    $maps = array();
    while($row2 = $result->FetchRow())
    {
      $maps[$row2['jenis']] = $row2['file_foto'];
    }

    $_BASE_PARAMS = $_APP_PARAM['base'];
    $_SYSTEM_PARAMS = $_APP_PARAM['system_params'];
?>
<!DOCTYPE html>
<html>
    <head>
      <meta charset="UTF-8">
      <title><?php echo $_BASE_PARAMS['sys_name_full'];?> - Peta Lokasi Laporan Penilaian</title>
      <link rel="stylesheet" type="text/css" href="../../assets/dist/css/report-style.css"/>      
      <style type="text/css">
        .border{border:1px solid #000;}
        table{width:100%;}
        .img-container{width:9cm;height:8cm;float:left;margin-right:9px;margin-bottom:5px;position:relative;}
        .map-container{width:18.5cm;height:9cm;}
        .img-footer{
            position:absolute;bottom:0;border:1px solid #000;width:100%;height:0.8cm;text-align:center;font-weight:bold;
            overflow:hidden;
        }
      </style>
    </head>
    <body>    
      <?php
      if($n_row>0)
      {
        echo "
        <div class='header'>
          <img src='../../uploads/logo/01.jpg' width='36px'/>
        </div>
        <div style='border:1px solid #000;margin-top:1cm;padding:2px'>
          <div style='border:1px solid #000;padding:5px'>                      

            <div>
              <table cellpadding=0 cellspacing=0>
                <tr>
                </tr>
                <tr>
                  <td style='border:1px solid #000;border-right:none' width='50%' align='center'>
                    <b>PELETAKAN TANAH</b>
                  </td>
                  <td style='border:1px solid #000;border-left:none' align='center'>
                    <b>PELETAKAN BANGUNAN</b>
                  </td>
                </tr>
                <tr>
                  <td align='center'>
                    <img src='../../uploads/location_maps/".$maps['peletakan_tanah']."' width='80%'/>
                  </td>
                  <td align='center'>
                    <img src='../../uploads/location_maps/".$maps['peletakan_bangunan']."' width='80%'/>
                  </td>
                </tr>
              </table>                  
            </div>




            <table cellpadding=0 cellspacing=0 style='font-size:0.7em;'>
              <tr>
                <td align='center' width='50%' style='border:1px solid #000;border-right:none;padding:10px 0 10px 0' valign='top'>
                  <h3>TANAH DAN BANGUNAN</h3><br />
                  ".$row['alamat'].", <br /> Kelurahan ".$row['kelurahan'].", Kecamatan 
                  ".$row['kecamatan'].", <br />Kota ".$row['kota'].", Provinsi 
                  ".$row['provinsi']."<br /><br />
                  Pemberi Tugas :<br />
                  <h2>".$row['perusahaan_penunjuk']."</h2>
                  Kantor Cabang ".$row['kantor_cabang']."
                </td>
                <td style='' valign='top'>
                  <table class='report' style='border:none!important' cellpadding=0 cellspacing=0>
                    <tr>
                      <td width='20%' colspan='4'>Digambar :<br />
                      ".$row['perancang_peta_lokasi']."</td>                      
                      
                      <td rowspan='2' colspan='3'>No. Laporan :<br />
                      ".$row['no_laporan']."
                      </td>                      
                    </tr>
                    <tr>
                      <td colspan='4' style='border-right:none!important'>Diperiksa :<br />
                      ".$row['nama_reviewer1']."
                      </td>                      
                    </tr>
                    <tr>
                      <td colspan='4'>Disetujui :</td>                      
                      
                      <td>Skala</td>
                      <td width='1%' style='border-left:none'>:</td>
                      <td style='border-left:none'>".$row['skala_peta_lokasi']."</td>
                    </tr>
                    <tr>
                      <td colspan='3' align='center' width='25%'>Tanggal</td>
                      <td align='center' width='25%'>Lembar</td>
                      
                      <td>Gambar</td>
                      <td colspan='2' style='border-left:none'>:</td>                      
                    </tr>
                    <tr>
                      <td colspan='3' align='center'>".indo_date_format($row['tgl_pemeriksaan'],'longDate')."</td>
                      <td align='center'>01</td>
                      <td colspan='3' align='center'>DENAH LOKASI</td>
                    </tr>
                    <tr>
                      <td colspan='7' align='center' valign='middle'>
                        <h3>".strtoupper($_SYSTEM_PARAMS['nama_instansi'])."</h3>
                        Ijin Usaha Jasa Penilai Publik No. ".$_SYSTEM_PARAMS['no_ijin_usaha']."
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </div>
        </div>";
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
