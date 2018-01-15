<?php
	$list_sql = "SELECT a.*,b.jenis_komponen_bangunan,b.fk_kelompok_komponen_bangunan FROM ref_nilai_komponen_bangunan as a 
				 INNER JOIN (SELECT id_jenis_komponen_bangunan,jenis_komponen_bangunan,fk_kelompok_komponen_bangunan
				 FROM ref_jenis_komponen_bangunan as x INNER JOIN ref_kelompok_komponen_bangunan as y ON (x.fk_kelompok_komponen_bangunan=y.id_kelompok_komponen_bangunan)) as b 
				 ON (a.fk_jenis_komponen_bangunan=b.id_jenis_komponen_bangunan)";
?>