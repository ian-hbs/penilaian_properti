<?php
	$pdf->AddPage();	

    $pdf->ln(15);

	$pdf->SetFont('Arial','',10);
	
	$pdf->Cell(0,8,$_SYSTEM_PARAMS['dt2'].', '.indo_date_format($row1['tgl_laporan'],'longDate'),0,1,'R');
	$pdf->Cell(0,5,'Kepada Yth.',0,1);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,5,'Kepala Cabang',0,1);
	$pdf->Cell(0,5,$row1['perusahaan_penunjuk'],0,1);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(0,5,$row1['alamat_perusahaan_penunjuk'],0,1);
	$pdf->Cell(0,5,$row1['kota_perusahaan_penunjuk'].' '.$row1['kode_pos_perusahaan_penunjuk'],0,1);

	$pdf->ln(3);

	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(20,5,'No.');
	$pdf->Cell(3,5,':',0,0,'C');
	$pdf->Cell(0,5,$row1['no_laporan'],0,1);
	$pdf->Cell(20,5,'Hal');
	$pdf->Cell(3,5,':',0,0,'C');
	$pdf->SetFont('Arial','BU',10);
	$pdf->Cell(0,5,'Laporan Penilaian',0,1);

	$pdf->ln(3);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(0,5,'Dengan hormat,',0,1);
	$pdf->ln(1);
	
	$pdf->SetFont('Arial','',10);
	$text="Sesuai dengan penugasan, kami telah melakukan inspeksi lapangan dan penilaian properti atas nama ".$row1['nama_debitur'].", yang berlokasi di "
		  .$row1['alamat'].", Kelurahan ".$row1['kelurahan'].", Kecamatan ".$row1['kecamatan'].", Kota ".$row1['kota'].", Provinsi ".$row1['provinsi'].".";
	$pdf->MultiCell(0,5,$text,0,'J',0);
	
	$pdf->ln(1);
	
	$text="Dalam melakukan penilaian ini kami telah ditunjuk sebagai konsultan penilaian independen oleh ".$row1['perusahaan_penunjuk'].", "
           ."sesuai dengan Perjanjian Kerjasama No. ".$row1['no_kerjasama_perusahaan_penunjuk']." pada tanggal ".$row1['tgl_kerjasama_perusahaan_penunjuk']
           ."dan Surat Permohonan Appraisal Agunan Kredit No. ".$row1['no_penugasan']." tanggal ".indo_date_format($row1['tgl_penugasan'],'longDate').".";               
	$pdf->MultiCell(0,5,$text,0,'J',0);
	
	$text = "Dalam penilaian ini kami berpedoman pada Kode Etik Penilai Indonesia (KEPI) dan Standar Penilaian Indonesia (SPI Edisi VI-2015). Tujuan dalam penilaian ini "
           ."adalah untuk memberikan opini mengenai Nilai Pasar dan Indikasi Nilai Likuidasi dari properti tersebut pada tanggal "
           .indo_date_format($row1['tgl_pemeriksaan'],'longDate').", laporan ini akan digunakan dalam menunjang kepentingan Jaminan Kredit pada "
           .$row1['perusahaan_penunjuk'];
	$pdf->MultiCell(0,5,$text,0,'J',0);

	$pdf->ln(1);
	$text = "Kami telah melakukan inspeksi lapangan pada tanggal ".indo_date_format($row1['tgl_survei'],'longDate')." terhadap properti yang dinilai. "
            ."Sehubungan dengan kemungkinan perubahan yang terjadi terhadap kondisi pasar dan kondisi properti tersebut, maka laporan ini hanya dapat merepresentasikan "
            ."tentang opini Nilai Pasar dan Indikasi Nilai Likuidasi pada saat tanggal penilaian. Kami berasumsi bahwa kondisi properti tersebut pada saat tanggal penilaian "
            ."sama dengan pada saat inspeksi lapangan.";
	$pdf->MultiCell(0,5,$text,0,'J',0);

	$pdf->ln(1);
	$pdf->Cell(0,5,'Untuk melakukan penilaian kami menggunakan Pendekatan Biaya.',0,1);		
	$text = "Berdasarkan praktek penilaian yang normal dan berdasarkan perhitungan serta analisa yang dilakukan serta faktor lain yang berkaitan dengan "
            ."penilaian dan berpedoman pada kondisi pembatas dalam laporan ini, maka kami berkesimpulan bahwa representasi Nilai Pasar dan Indikasi Nilai Likuidasi "
            ."dari properti tersebut pada tanggal ".indo_date_format($row1['tgl_pemeriksaan'],'longDate')." adalah :";
	$pdf->MultiCell(0,5,$text,0,'J',0);		

	$pdf->ln(1);
	$pdf->SetFont('Arial','B',10);

	$w = ($row1['fk_perusahaan_penunjuk']=='1'?50:65);

	$pdf->Cell($w,5,'Nilai Pasar Properti');
	$pdf->Cell(3,5,':',0,0,'C');
	$pdf->Cell(10,5,'Rp.');
	$pdf->Cell(40,5,($n_row2>0?number_format($row2['pembulatan_pasar_objek']):'-'),0,1,'R');

	if($row1['jenis_perusahaan_penunjuk']=='2')
	{
		$pdf->Cell($w,5,'Nilai Pasar Setelah Safety Margin');
		$pdf->Cell(3,5,':',0,0,'C');
		$pdf->Cell(10,5,'Rp.');
		$pdf->Cell(40,5,($n_row2>0?number_format($row2['pembulatan_safetymargin_objek']):'-'),0,1,'R');
	}

	$pdf->Cell($w,5,'Indikasi Nilai Likuidasi');
	$pdf->Cell(3,5,':',0,0,'C');
	$pdf->Cell(10,5,'Rp.');
	$pdf->Cell(40,5,($n_row2>0?number_format($row2['pembulatan_likuidasi_objek']):'-'),0,1,'R');

	$pdf->ln(2);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(0,5,'Surat ini merupakan bagian yang tak terpisahkan dan tidak dapat dibaca terpisah dari laporan secara keseluruhan.');

	$pdf->ln(5);

	$pdf->Cell(0,5,'Hormat Kami,',0,1);
	$pdf->Cell(0,5,strtoupper($row1['perusahaan_penilai']),0,1);
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(0,5,'Registered Public Appraisers and Consultants',0,1);

	$pdf->ln(20);

	$pdf->SetFont('Arial','BU',10);
	$pdf->Cell(0,5,strtoupper($row1['reviewer1']),0,1);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,5,'Kepala Cabang',0,1);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(0,5,'Ijin Penilai Properti : '.$row1['ijin_penilai_reviewer1'],0,1);
	$pdf->Cell(0,5,'MAPPI : '.$row1['mappi_reviewer1'],0,1);
?>