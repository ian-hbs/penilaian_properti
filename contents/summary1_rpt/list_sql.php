<?php
	$list_sql = "SELECT a.id_penugasan,a.no_penugasan,DATE_FORMAT(a.tgl_penugasan,'%d-%m-%Y') as tgl_penugasan,a.no_penilaian,b.nama,c.alamat,c.kelurahan,c.kecamatan FROM penugasan as a 
				LEFT JOIN debitur as b ON (a.id_penugasan=b.fk_penugasan)
				LEFT JOIN properti as c ON (a.id_penugasan=c.fk_penugasan)";
?>