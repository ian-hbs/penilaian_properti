<?php
	
	$pdf->AddPage();

	$pdf->Image('../../uploads/logo/01.jpg',16,6,12);
	
	$pdf->SetFont('Arial','B',8);

	$pdf->Cell(0,5,'PERHITUNGAN NILAI PASAR TANAH',0,1,'L');
	$pdf->Cell(0,5,'DENGAN METODE PENDEKATAN PERBANDINGAN DATA PASAR',0,1,'L');


	$pdf->SetFont('Arial','',8);
	$pdf->Cell(40,5,'Nama Penilai');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(3,5,':',0,0,'C');
	$pdf->Cell(0,5,$row1['nama_penilai1'],0,1);

	$pdf->SetFont('Arial','',8);
	$pdf->Cell(40,5,'Tanggal Survey');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(3,5,':',0,0,'C');
	$pdf->Cell(0,5,indo_date_format($row1['tgl_survei'],'longDate'),0,1);
	
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(40,5,'Calon Debitur');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(3,5,':',0,0,'C');
	$pdf->Cell(0,5,$row1['nama_debitur'],0,1);

	$pdf->SetFont('Arial','',8);
	$pdf->Cell(40,5,'Lokasi');
	$pdf->Cell(3,5,':',0,0,'C');
	$text = $row1['alamat'].", Kelurahan ".$row1['kelurahan'].", Kecamatan ".$row1['kecamatan'].", Kota ".$row1['kota']
          	.", Provinsi ".$row1['provinsi'];
	$pdf->MultiCell(0,5,$text);

    $pdf->SetFont('Arial','B',8);

    $pdf->cell(45,5,'URAIAN',1,0,'C');  
    $pdf->cell(35,5,'OBJEK PENILAIAN',1,0,'C'); 
    $pdf->cell(35,5,'DATA 1',1,0,'C'); 
    $pdf->cell(35,5,'DATA 2',1,0,'C'); 
    $pdf->cell(35,5,'DATA 3',1,1,'C'); 
   
 	$pdf->SetFont('Arial','',8);
    $pdf->Cell(45,5,'01. Jenis Properti',1,'L');
	$pdf->Cell(35,5,$row1['jenis_objek'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
    $pdf->Cell(45,15,'02. Alamat',1,'L'); 
    //$pdf->Cell(35,15,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,3,$text,1);
	$pdf->Cell(35,15,$text,1);
	$pdf->Cell(35,15,$text,1);
	$pdf->Cell(35,15,'Tanah Kosong',1,1,'L'); 
	
    $pdf->Cell(45,5,'03. Jarak dengan Properti',1,'L');
	$pdf->Cell(35,5,'',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
    $pdf->Cell(45,5,'04. Sumber Data',1,'L');
	$pdf->Cell(35,5,'',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
    $pdf->Cell(45,5,'05. Telepon',1,'L');
	$pdf->Cell(35,5,'',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	 
    $pdf->Cell(45,5,'06. Keterangan',1,'L');
	$pdf->Cell(35,5,'',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	  
    $pdf->Cell(45,5,'07. Penawaran/Transaksi',1,'L');
	$pdf->Cell(35,5,'',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	 
    $pdf->Cell(45,5,'08. Waktu Penawaran/Transaksi',1,'L');
	$pdf->Cell(35,5,'',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	 
    $pdf->Cell(45,5,'09. Discount',1,'L');
	$pdf->Cell(35,5,'',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	/*<--spesifikasi Data-->*/
	
	$pdf->SetFont('Arial','B','8');
	$pdf->Cell(0,5,'Spesifikasi Data',0,1,'L');
	$pdf->SetFont('Arial','','8');

    $pdf->Cell(45,5,'01. Lokasi',1,'L');
	$pdf->Cell(35,5,$row1['lokasi_perumahan'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
    $pdf->Cell(45,5,'02. Dokumen Tanah',1,'L');
	$pdf->Cell(35,5,$row1['jenis_sertifikat'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
    $pdf->Cell(45,5,'03. Luas Tanah (m2)',1,'L');
	$pdf->Cell(35,5,$row1['luas_tanah'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
    $pdf->Cell(45,5,'04. Bentuk Tanah',1,'L');
	$pdf->Cell(35,5,$row1['bentuk_tanah'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
    $pdf->Cell(45,5,'05. Lebar Depan (m)',1,'L');
	$pdf->Cell(35,5,$row1['lebar_depan'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	 
    $pdf->Cell(45,5,'06. Lebar Jalan (m)',1,'L');
	$pdf->Cell(35,5,$row1['lebar_jalan'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	  
    $pdf->Cell(45,5,'07. Letak Tanah',1,'L');
	$pdf->Cell(35,5,$row1['lokasi_agunan'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	 
    $pdf->Cell(45,5,'08. Kondisi Tanah',1,'L');
	$pdf->Cell(35,5,$row1['kondisi_tanah'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	 
    $pdf->Cell(45,5,'09. Peruntukan',1,'L');
	$pdf->Cell(35,5,$row1['peruntukan'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	$pdf->Cell(45,5,'10. Kontur Tanah/Topografi',1,'L');
	$pdf->Cell(35,5,$row1['kontur'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	$pdf->Cell(45,5,'11. Luas Bangunan (m2)',1,'L');
	$pdf->Cell(35,5,$row1['luas_bangunan'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	$pdf->Cell(45,5,'12. Fasilitas (Keamanan)',1,'L');
	$pdf->Cell(35,5,$row1['fasilitas'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	$pdf->Cell(45,5,'13. Indikasi Nilai Properti ',1,'L');
	$pdf->Cell(35,5,$row1['indikasi_nilai_properti'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	$pdf->Cell(45,5,'14. Harga Bangunan Baru/m2',1,'L');
	$pdf->Cell(35,5,$row1['harga_bangunan_baru'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	$pdf->Cell(45,5,'15. Indikasi Nilai Pasar Bang/m2',1,'L');
	$pdf->Cell(35,5,$row1['indkasi_nilai_pasar_bangunan'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	$pdf->Cell(45,5,'16. Indikasi Nilai Pasar Bangunan ',1,'L');
	$pdf->Cell(35,5,$row1['indkasi_nilai_pasar_bangunan'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	$pdf->Cell(45,5,'17. Indikasi Nilai Tanah',1,'L');
	$pdf->Cell(35,5,$row1['indkasi_nilai_tanah'],1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');


    /*<--Indikasi Nilai-->*/
	
	$pdf->SetFont('Arial','B','8');
	$pdf->Cell(45,5,'Indikasi Nilai Properti/Tanah',1,0,'C');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	$pdf->Cell(45,5,'Indikasi Nilai Tanah /m2',1,0,'C');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	$pdf->SetFont('Arial','','8');

    /*<--penyesuaian Data-->*/

    $pdf->SetFont('Arial','B','8');
	$pdf->Cell(0,5,'Penyesuaian',0,1,'L');
	$pdf->SetFont('Arial','','8');

    $pdf->Cell(80,5,'01. Lokasi',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
    $pdf->Cell(80,5,'02. Dokumen Tanah',1,'L'); 
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
    $pdf->Cell(80,5,'03. Luas Tanah (m2)',1,'L'); 
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
    $pdf->Cell(80,5,'04. Bentuk Tanah',1,'L'); 
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');

    $pdf->Cell(80,5,'05. Lebar Depan (m)',1,'L'); 
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	$pdf->Cell(80,5,'06. Lebar& kondisi Jalan',1,'L'); 
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	$pdf->Cell(80,5,'07. Letak Tanah',1,'L'); 
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	 
    $pdf->Cell(80,5,'08. Elevasi',1,'L');  
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	 
    $pdf->Cell(80,5,'09. Perkembangan Lingkungan',1,'L'); 
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	$pdf->Cell(80,5,'10. Faktor Ekonomis',1,'L'); 
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	$pdf->Cell(80,5,'11. Peruntukan',1,'L'); 
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	$pdf->Cell(80,5,'12. Fasilitas (Keamanan)',1,'L'); 
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	$pdf->ln(5);
	
	$pdf->Image('../../uploads/logo/01.jpg',16,6,12);
	
	$pdf->SetFont('Arial','B',8);
		
	$pdf->Cell(80,5,'Total Penyesuaian',1,0,'C'); 
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');
	
	$pdf->Cell(80,5,'Nilai Indikasi',1,0,'C'); 
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah dan Bangunan',1,'L');
	$pdf->Cell(35,5,'Tanah Kosong',1,1,'L');

    /*--Pembebanan --*/
    
    $pdf->SetFont('Arial','B','8');
	$pdf->Cell(115,5,'PEMBEBANAN',1,0,'L');
	$pdf->Cell(70,5,'Penilai','TR',1,'C');
	$pdf->SetFont('Arial','','8');
	
	$pdf->SetFont('Arial','',8);
    $pdf->Cell(45,5,'Data Pembanding',1,'C');
	$pdf->Cell(35,5,'Bobot',1,0,'C');
	$pdf->Cell(35,5,'Indikasi Nilai Tanah/m2',1,'C');
	$pdf->Cell(70,5,'','R',1,'L');
	
	$pdf->SetFont('Arial','',8);
    $pdf->Cell(45,5,'Data #1',1,'R');
	$pdf->Cell(35,5,'%',1,0,'R');
	$pdf->Cell(35,5,'Rp.',1,'L');
	$pdf->Cell(70,5,'','R',1,'L');
	
	$pdf->SetFont('Arial','',8);
    $pdf->Cell(45,5,'Data #2',1,'L');
	$pdf->Cell(35,5,'%',1,0,'R');
	$pdf->Cell(35,5,'Rp.',1,'L');
	$pdf->Cell(70,5,'','R',1,'L');
	
	$pdf->SetFont('Arial','',8);
    $pdf->Cell(45,5,'Data #3',1,'L');
	$pdf->Cell(35,5,'%',1,0,'R');
	$pdf->Cell(35,5,'Rp.',1,'L');
	$pdf->Cell(70,5,'','R',1,'L');
	
	$pdf->SetFont('Arial','B',8);
    $pdf->Cell(45,5,'Nilai Indikasi',1,0,'C');
	$pdf->Cell(35,5,'%',1,0,'R');
	$pdf->Cell(35,5,'Rp.',1,'L');
	$pdf->Cell(70,5,'','R',1,'L');

    $pdf->Cell(80,5,'Dibulatkan',1,0,'C');
	$pdf->Cell(35,5,'Rp. ',1,'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(70,5,$row1['nama_penilai1'],'BR',1,'C');

?>