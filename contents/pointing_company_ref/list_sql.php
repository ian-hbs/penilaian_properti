<?php
	$list_sql = "SELECT id_perusahaan_penunjuk,perusahaan_penunjuk,kantor_cabang,alamat,kota,kode_pos,no_kerjasama,
				 DATE_FORMAT(tgl_kerjasama,'%d-%m-%Y') as tgl_kerjasama FROM ref_perusahaan_penunjuk";
?>