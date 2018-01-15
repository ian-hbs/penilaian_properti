<?php
	$list_sql = "SELECT a.id_penugasan,a.no_penugasan,a.fk_perusahaan_penunjuk,a.no_penilaian,b.nama,c.alamat,c.kelurahan,c.kecamatan,a.status,
				(SELECT count(1) FROM perhitungan_tanah as x WHERE x.fk_penugasan=a.id_penugasan) as num_spesifikasi,
				(SELECT count(1) FROM nilai_safetymargin as x WHERE(x.fk_penugasan=a.id_penugasan) and (x.jenis_objek='tanah')) as num_safetymargin,
				d.perusahaan_penunjuk,d.jenis as jenis_perusahaan_penunjuk
				FROM penugasan as a 
				LEFT JOIN debitur as b ON (a.id_penugasan=b.fk_penugasan)
				LEFT JOIN properti as c ON (a.id_penugasan=c.fk_penugasan)
				LEFT JOIN ref_perusahaan_penunjuk as d ON (a.fk_perusahaan_penunjuk=d.id_perusahaan_penunjuk)";
?>