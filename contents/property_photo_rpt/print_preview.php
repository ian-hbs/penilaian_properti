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
      <title><?php echo $_BASE_PARAMS['sys_name_full'];?> - Foto Properti Laporan Penilaian</title>
      <link rel="stylesheet" type="text/css" href="../../assets/dist/css/report-style.css"/>      
      <style type="text/css">
        .border{border:1px solid #000;}
        table{width:100%;}
        .img-container{width:9cm;height:8cm;float:left;margin-right:9px;margin-bottom:5px;position:relative;}
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

        $sql = "SELECT a.no_laporan,a.perusahaan_penunjuk,a.kantor_cabang,a.nama_reviewer1,
            b.alamat,b.kelurahan,b.kecamatan,b.kota,b.provinsi,b.perancang_foto_properti,b.skala_foto_properti,
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
        
        $row = $result->FetchRow();        

        echo "
        <div class='header'>
          <img src='../../uploads/logo/01.jpg' width='36px'/>
        </div>
        <div style='margin-top:1cm;padding:2px'>
            <div style='border:1px solid #000;text-align:center'>
              <h3>FOTO PROPERTI</h3>
              Beberapa foto properti yang diperoleh pada saat inspeksi di lapangan
            </div>";
            $sql = "SELECT * FROM foto_properti WHERE fk_penugasan='".$id_penugasan_dec."'";
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
            <table style='width:18.6cm;' cellpadding=0 cellspacing=0>";
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
                      ".$row['perancang_foto_properti']."
                      </td>
                      
                      
                      <td rowspan='2' colspan='3'>No. Laporan : <br />
                      ".$row['no_laporan']."
                      </td>                      
                    </tr>
                    <tr>
                      <td colspan='4' style='border-right:none;'>Diperiksa :<br />
                      ".$row['nama_reviewer1']."
                      </td>
                      
                    </tr>
                    <tr>
                      <td colspan='4'>Disetujui :
                      </td>
                      
                      <td>Skala</td>
                      <td width='1%' style='border-left:none'>:</td>
                      <td style='border-left:none'>".$row['skala_foto_properti']."</td>
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
                      <td colspan='3' align='center'>FOTO PROPERTI</td>                      
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
