<?php
	$list_sql = "SELECT a.*,b.kelompok_komponen_bangunan FROM ref_jenis_komponen_bangunan as a LEFT JOIN ref_kelompok_komponen_bangunan as b
	             ON (a.fk_kelompok_komponen_bangunan=b.id_kelompok_komponen_bangunan)";
?>