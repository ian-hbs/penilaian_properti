<?php
	$list_sql = "SELECT a.*,b.jenis_objek FROM ref_tarif_imb as a LEFT JOIN ref_jenis_objek as b ON (a.fk_jenis_objek=b.id_jenis_objek)";
?>