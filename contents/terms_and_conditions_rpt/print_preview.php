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

    $sql = "SELECT a.perusahaan_penilai,a.no_penugasan,a.tgl_penugasan,a.no_laporan,a.tgl_laporan,a.tgl_survei,d.tgl_pemeriksaan,b.nama as nama_debitur,
            c.alamat,c.kelurahan,c.kecamatan,c.kota,c.provinsi ,e.perusahaan_penunjuk,e.kantor_cabang,
            e.alamat as alamat_perusahaan_penunjuk,e.kota as kota_perusahaan_penunjuk,e.kode_pos as kode_pos_perusahaan_penunjuk,
            e.no_kerjasama as no_kerjasama_perusahaan_penunjuk,e.tgl_kerjasama as tgl_kerjasama_perusahaan_penunjuk,
            f.nama as reviewer1, f.no_mappi as mappi_reviewer1, f.ijin_penilai as ijin_penilai_reviewer1
            FROM penugasan as a, debitur as b, properti as c, pemeriksaan as d, ref_perusahaan_penunjuk as e, ref_penilai as f
    		    WHERE(a.id_penugasan=b.fk_penugasan AND a.id_penugasan=c.fk_penugasan AND a.id_penugasan=d.fk_penugasan 
            AND a.fk_perusahaan_penunjuk=e.id_perusahaan_penunjuk AND a.reviewer1=f.id_penilai)
            AND (a.id_penugasan='".$id_penugasan_dec."')";
    
    $result = $db->Execute($sql);
    if(!$result)
    	die($db->ErroMsg());
    $row1 = $result->FetchRow();

    $sql = "SELECT * FROM kesimpulan_rekomendasi WHERE(fk_penugasan='".$id_penugasan_dec."')";
    $result = $db->Execute($sql);
    if(!$result)
      die($db->ErroMsg());

    $n_row2 = $result->RecordCount();
    if($n_row2)
      $row2 = $result->FetchRow();

    $_BASE_PARAMS = $_APP_PARAM['base'];
?>
<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title><?php echo $_BASE_PARAMS['sys_name_full'];?> - Syarat & Ketentuan Laporan Penilaian</title>
    	<link rel="stylesheet" type="text/css" href="../../assets/dist/css/report-style.css"/>    	
      <style type="text/css">
        p{margin-bottom:8px!important;}
      </style>
  	</head>
  	<body>
        <div class="header">
            <img src="../../uploads/logo/01.jpg" width="36px"/>
        </div>
        <div style="margin-top:2cm">
  		    <h2 align="center" style="margin-bottom:25px!important;"><u>ASUMSI DAN SYARAT-SYARAT PEMBATASAN</u></h2>
            <ol type="1" style="font-size:1.0em;">
                <li>Semua penggugatan / sengketa dan hipotik yang masih berjalan, jika ada dapat diabaikan dan properti yang dinilai bebas dan bersih di bawah tanggung jawab pemilik.</li>
                <li>Dalam penilaian ini telah diabaikan beberapa item yang menurut hemat kami memiliki nilai yang sangat minimal dan yang umumnya diklasifikasikan sebagai biaya operasional perusahaan.</li>
                <li>Jumlah keseluruhan dari properti yang tercantum dalam laporan ini hakekatnya merupakan satu kesatuan nilai, oleh karenanya upaya untuk memisah-misahkan satu atau beberapa nilai aset untuk kepentingan tertentu akan membuat laporan penilaian ini tidak berlaku.</li>
                <li>Kami telah memeriksa kondisi properti yang dinilai namun kami tidak berkewajiban untuk memeriksa struktur bangunan ataupun bagian yang tertutup dan tidak terlihat dan bukan tanggung jawab kami sebagai penilai apabila ada pelapukan dan atau kerusakan lain.</li>
                <li>Opini nilai properti dalam penilaian ini merupakan cerminan kondisi pasar properti pada saat tanggal penilaian serta kondisi penggunaan dan hunian atas properti tersebut merupakan pengamatan pada saat tanggal tersebut.</li>
                <li>Nilai Pasar adalah estimasi sejumlah uang yang dapat diperoleh dari hasil penukaran suatu aset atau liabilitas pada tanggal penilaian, antara pembeli yang berminat membeli dengan penjual yang berminat menjual, dalam suatu transaksi bebas ikatan, yang pemasarannya dilakukan secara layak, di mana kedua pihak masing-masing bertindak atas dasar pemahaman yang dimilikinya, kehati-hatian dan tanpa paksaan (SPI 101).</li>
                <li>Nilai Likuidasi adalah sejumlah uang yang mungkin diterima dari penjualan suatu aset dalam jangka waktu yang relatif pendek untuk dapat memenuhi jangka waktu pemasaran dalam definisi Nilai Pasar. Pada beberapa situasi, Nilai Likuidasi dapat melibatkan penjual yang tidak berminat menjual, dan pembeli yang membeli dengan mengetahui situasi yang tidak menguntungkan penjual (SPI 102).</li>
                <li>Laporan penilaian ini hanya dapat digunakan secara terbatas sesuai dengan tujuan yang dijelaskan dalam laporan serta ditujukan terbatas kepada klien dimaksud.</li>
                <li>Berkaitan dengan penugasan penilaian ini kami tidak melakukan penyelidikan yang berkaitan dengan status hukum kepemilikan, keuangan dan lain sebagainya atas properti tersebut.</li>
                <li>Dalam penilaian ini kami berasumsi bahwa seluruh properti didukung oleh dokumen kepemilikan yang sah dan bebas dari sengketa dan atau hipotik.</li>
                <li>Baik perusahaan maupun para penilai dan karyawan lainnya sama sekali tidak mempunyai kepentingan finansial terhadap properti yang dinilai.</li>
                <li>Biaya untuk penilaian ini tidak tergantung pada besarnya nilai yang tercantum dalam laporan.</li>
                <li>Karmanto & Rekan, sehubungan dengan penilaian ini tidak diwajibkan memberi kesaksian atau hadir dalam pengadilan atau instansi pemerintah lainnya yang berhubungan dengan properti yang dinilai kecuali apabila perjanjian telah dibuat sebelumnya.</li>
                <li>Laporan ini dianggap tidak sah apabila tidak dicetak di atas kertas berlogo  dan tertera cap KJPP Karmanto & Rekan.</li>
                <li>Laporan ini tidak dapat dipublikasikan baik sebagian maupun keseluruhan laporan, referensi di dalam laporan, opini nilai atau nama dan afiliasi penilai tanpa persetujuan dari penilai atau KJPP Karmanto & Rekan.</li>
            </ol>
        </div>
  	</body>
</html>
