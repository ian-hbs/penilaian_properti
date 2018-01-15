<?php
	$list_sql = "SELECT a.id_perhitunganbkb_master,a.no_bct,b.jenis_objek,c.klasifikasi_bangunan,a.nm_perumahan,a.alamat,a.provinsi,
				 a.kota,a.kecamatan,a.kelurahan,DATE_FORMAT(a.tgl_penilaian,'%d-%m-%Y') as tgl_penilaian,
				 a.luas_bangunan,a.jumlah_lantai,d.rounded FROM perhitunganbkb_master as a
				 LEFT JOIN ref_jenis_objek as b ON (a.fk_jenis_objek=b.id_jenis_objek)
				 LEFT JOIN ref_klasifikasi_bangunan as c ON (a.fk_klasifikasi_bangunan=c.id_klasifikasi_bangunan)
				 LEFT JOIN perhitunganbkb_hasil as d ON (a.id_perhitunganbkb_master=d.fk_perhitunganbkb_master)";
?>