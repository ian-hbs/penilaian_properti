<?php
	$list_sql = "SELECT a.id_indeks,a.indeks,b.name as nm_propinsi FROM ref_indeks_kemahalan_konstruksi as a LEFT JOIN ref_provinces as b
	             ON (a.fk_propinsi=b.id)";
?>