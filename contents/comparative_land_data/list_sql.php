<?php
	$list_sql = "SELECT a.id_penugasan,a.no_penugasan,a.no_penilaian,b.nama,c.alamat,c.kelurahan,c.kecamatan,a.status,
				(SELECT count(1) FROM objek_pembanding as x WHERE x.fk_penugasan=a.id_penugasan) as num_spesifikasi FROM penugasan as a 
				LEFT JOIN debitur as b ON (a.id_penugasan=b.fk_penugasan)
				LEFT JOIN properti as c ON (a.id_penugasan=c.fk_penugasan)";
?>