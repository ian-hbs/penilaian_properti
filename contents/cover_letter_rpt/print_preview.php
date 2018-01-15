<?php
	session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/cipher.php";    
    include_once "../../config/app_param.php";  	
    include_once "../../helpers/date_helper.php";

    //instance object
    $cipher = new cipher(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);

    $dec_key = "+^?:^&%*S!3!c!12!31T";
    $id_penugasan = urldecode($_GET['id']);
    $id_penugasan_dec = $cipher->decrypt($id_penugasan,$dec_key);    

    $sql = "SELECT a.perusahaan_penilai,a.no_penugasan,a.fk_perusahaan_penunjuk,a.tgl_penugasan,a.no_laporan,a.tgl_laporan,a.tgl_survei,d.tgl_pemeriksaan,b.nama as nama_debitur,
            c.alamat,c.kelurahan,c.kecamatan,c.kota,c.provinsi ,e.perusahaan_penunjuk,e.kantor_cabang,
            e.alamat as alamat_perusahaan_penunjuk,e.kota as kota_perusahaan_penunjuk,e.kode_pos as kode_pos_perusahaan_penunjuk,
            e.no_kerjasama as no_kerjasama_perusahaan_penunjuk,e.tgl_kerjasama as tgl_kerjasama_perusahaan_penunjuk,e.jenis as jenis_perusahaan_penunjuk,
            f.nama as reviewer1, f.no_mappi as mappi_reviewer1, f.ijin_penilai as ijin_penilai_reviewer1
            FROM penugasan as a, debitur as b, properti as c, pemeriksaan as d, ref_perusahaan_penunjuk as e, ref_penilai as f
    		WHERE(a.id_penugasan=b.fk_penugasan AND a.id_penugasan=c.fk_penugasan AND a.id_penugasan=d.fk_penugasan 
            AND a.fk_perusahaan_penunjuk=e.id_perusahaan_penunjuk AND a.reviewer1=f.id_penilai)
            AND (a.id_penugasan='".$id_penugasan_dec."')";
    
    $result = $db->Execute($sql);
    if(!$result)
    	die($db->ErroMsg());
    
    $n_row1 = $result->RecordCount();
    if($n_row1>0)
        $row1 = $result->FetchRow();

    $sql = "SELECT * FROM kesimpulan_rekomendasi WHERE(fk_penugasan='".$id_penugasan_dec."')";
    
    $result = $db->Execute($sql);
    if(!$result)
      die($db->ErroMsg());

    $n_row2 = $result->RecordCount();
    if($n_row2)
      $row2 = $result->FetchRow();

    $_BASE_PARAMS = $_APP_PARAM['base'];
    $_SYSTEM_PARAMS = $_APP_PARAM['system_params'];
?>
<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title><?php echo $_BASE_PARAMS['sys_name_full'];?> - Surat Pengantar Laporan Penilaian</title>
    	<link rel="stylesheet" type="text/css" href="../../assets/dist/css/report-style.css"/>    	
      <style type="text/css">
        p{margin-bottom:8px!important;}
      </style>
  	</head>
  	<body>
  		<?php
        if($n_row1>0)
        {
            echo "
            <p align='right'>".$_SYSTEM_PARAMS['dt2'].", ".indo_date_format($row1['tgl_laporan'],'longDate')."</p>";        
            echo "
            <div>
              <br />
              Kepada Yth.<br />
              <b>Kepala Cabang<br />
              ".$row1['perusahaan_penunjuk']."
              </b><br />            
              ".$row1['alamat_perusahaan_penunjuk']."<br />
              ".$row1['kota_perusahaan_penunjuk']." ".$row1['kode_pos_perusahaan_penunjuk']."
            </div><br /><br /><br />

            <table width='100%' style='font-weight:bold'>
            <tr><td width='5%'>No</td><td>: ".$row1['no_laporan']."</td></tr>
            <tr><td>Hal</td><td>: <u>Laporan Penilaian</u></td></tr>
            </table>
            <br /><br />
            <p align='justify'>
            Dengan hormat,
            </p>
            <p align='justify'>
            Sesuai dengan penugasan, kami telah melakukan inspeksi lapangan dan penilaian properti atas nama ".$row1['nama_debitur'].", 
            yang berlokasi di ".$row1['alamat'].", Kelurahan ".$row1['kelurahan'].", Kecamatan ".$row1['kecamatan'].", 
            Kota ".$row1['kota'].", Provinsi ".$row1['provinsi']."
            </p>
            <p align='justify'>
            Dalam melakukan penilaian ini kami telah ditunjuk sebagai konsultan penilaian independen oleh ".$row1['perusahaan_penunjuk'].", 
            sesuai dengan Perjanjian Kerjasama No. ".$row1['no_kerjasama_perusahaan_penunjuk']." pada tanggal ".$row1['tgl_kerjasama_perusahaan_penunjuk']." 
            dan Surat Permohonan Appraisal Agunan Kredit No. ".$row1['no_penugasan']." tanggal ".indo_date_format($row1['tgl_penugasan'],'longDate').".<br />
            Dalam penilaian ini kami berpedoman pada Kode Etik Pennilai Indonesia (KEPI) dan Standar Penilaian Indonesia (SPI Edisi VI-2015). Tujuan dalam penilaian ini 
            adalah untuk memberikan opini mengenai Nilai Pasar dan Indikasi Nilai Likuidasi dari properti tersebut pada tanggal 
            ".indo_date_format($row1['tgl_pemeriksaan'],'longDate').", laporan ini akan digunakan dalam menunjang kepentingan Jaminan Kredit pada 
            ".$row1['perusahaan_penunjuk']."
            </p>
            <p align='justify'>
            Kami telah melakukan inspeksi lapangan pada tanggal ".indo_date_format($row1['tgl_survei'],'longDate')." terhadap properti yang dinilai. 
            Sehubungan dengan kemungkinan perubahan yang terjadi terhadap kondisi pasar dan kondisi properti tersebut, maka laporan ini hanya dapat merepresentasikan 
            tentang opini Nilai Pasar dan Indikasi Nilai Likuidasi pada saat tanggal penilaian. Kami berasumsi bahwa kondisi properti tersebut pada saat tanggal penilaian 
            sama dengan pada saat inspeksi lapangan.
            </p>
            <p align='justify'>
            Untuk melakukan penilaian kami menggunakan Pendekatan Biaya.<br />
            Berdasarkan praktek penilaian yang normal dan berdasarkan perhitungan serta analisa yang dilakukan serta faktor lain yang berkaitan dengan 
            penilaian dan berpedoman pada kondisi pembatas dalam laporan ini, maka kammi berkesimpulan bahwa representasi Nilai Pasar dan Indikasi Nilai Likuidasi 
            dari properti tersebut pada tanggal ".indo_date_format($row1['tgl_pemeriksaan'],'longDate')." adalah :
            </p>
            <table width='40%' style='font-weight:bold'>
                <tr><td width='50%'>Nilai Pasar Properti</td><td width='5%'>:</td><td width='5%'>Rp.</td><td align='right'>".($n_row2>0?number_format($row2['pembulatan_pasar_objek']):'-')."</td></tr>";
                if($row1['jenis_perusahaan_penunjuk']=='2')
                {
                    echo "<tr><td>Nilai Pasar Setelah Safety Margin</td><td>:</td><td>Rp.</td><td align='right'>".($n_row2>0?number_format($row2['pembulatan_safetymargin_objek']):'-')."</td></tr>";
                }
              echo "<tr><td>Indikasi Nilai Likuidasi</td><td>:</td><td>Rp.</td><td align='right'>".($n_row2>0?number_format($row2['pembulatan_likuidasi_objek']):'-')."</td></tr>
            </table><br />
            <p>
            Surat ini merupakan bagian yang tak terpisahkan dan tidak dapat dibaca terpisah dari laporan secara keseluruhan.
            </p><br /><br />

            <div>Hormat Kami,<br />
                <b>".strtoupper($row1['perusahaan_penilai'])."</b><br />
                <small><i>Registered Public Appraisers and Consultants</i></small>
                <br /><br /><br /><br /><br />
                <b><u>".$row1['reviewer1']."</u></b><br />
                <b>Partner</b><br />
                Ijin Penilai Properti : ".$row1['ijin_penilai_reviewer1']."<br />
                MAPPI : ".$row1['mappi_reviewer1']."
            </div>";
        }
        else
            echo "<br /><center>Data tidak ditemukan!</center>";
      ?>
  	</body>
</html>
