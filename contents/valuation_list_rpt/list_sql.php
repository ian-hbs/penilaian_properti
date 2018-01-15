<?php
	$list_sql = "SELECT a.id_penugasan,a.no_penugasan,DATE_FORMAT(a.tgl_penugasan,'%d-%m-%Y') as tgl_penugasan, DATE_FORMAT(a.tgl_survei,'%d-%m-%Y') as tgl_survei,
				 a.no_laporan,DATE_FORMAT(a.tgl_laporan,'%d-%m-%Y') as tgl_laporan,b.nama,c.alamat,c.kelurahan,c.kecamatan,
				 d.pembulatan_pasar_objek,d.pembulatan_likuidasi_objek FROM penugasan as a 
				LEFT JOIN debitur as b ON (a.id_penugasan=b.fk_penugasan)
				LEFT JOIN properti as c ON (a.id_penugasan=c.fk_penugasan)
				LEFT JOIN kesimpulan_rekomendasi as d ON (a.id_penugasan=d.fk_penugasan)";
?>