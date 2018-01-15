<?php
	
	$pdf->AddPage();

	$pdf->Image('../../uploads/logo/01.jpg',16,6,12);
	
	$pdf->ln(8);

	$pdf->SetFont('Arial','B',10);

	$pdf->Cell(0,5,'Ringkasan Penilaian',0,1,'C');

	$pdf->ln(5);

	$pdf->SetFont('Arial','',10);
	$pdf->Cell(40,5,'Calon Debitur');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(3,5,':',0,0,'C');
	$pdf->Cell(0,5,$row1['nama_debitur'],0,1);

	$pdf->SetFont('Arial','',10);
	$pdf->Cell(40,5,'Alamat Properti');
	$pdf->Cell(3,5,':',0,0,'C');
	$text = $row1['alamat'].", Kelurahan ".ucwords(strtolower($row1['kelurahan'])).", Kecamatan ".ucwords(strtolower($row1['kecamatan'])).", ".ucwords(strtolower($row1['kota']))
          	.", Provinsi ".ucwords(strtolower($row1['provinsi']));
	$pdf->MultiCell(0,5,$text);

	$pdf->ln(3);	

	if($row1['jenis_perusahaan_penunjuk']=='1')        
        $pdf->SetWidths(array(73,56,56));
    else
        $pdf->SetWidths(array(65,40,40,40));

    $pdf->SetFont('Arial','B',10);

    $_rows = array(                
                array("OBJEK","C"),
                array("NILAI PASAR","C"));

    if($row1['jenis_perusahaan_penunjuk']=='2')
    {
    	$_rows[] = array("N. PASAR SETELAH SAFETY MARGIN","C");
    }

    $_rows[] = array("INDIKASI NILAI LIKUIDASI","C");

	$pdf->Row($_rows);

	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?43:35),5,'a. Tanah','L');
	$pdf->Cell(20,5,number_format($row1['luas_tanah']),'T',0,'R');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(10,5,'m2',0);

	$pdf->Cell(10,5,'Rp.','L');
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?46:30),5,number_format($row1['nilai_pasar_tanah']),0,0,'R');

	if($row1['jenis_perusahaan_penunjuk']=='2')
	{
		$pdf->Cell(10,5,'Rp.','L');
		$pdf->Cell(30,5,number_format($row1['nilai_safetymargin_tanah']),0,0,'R');
	}

	$pdf->Cell(10,5,'Rp.','L');
	$pdf->Cell(0,5,number_format($row1['nilai_likuidasi_tanah']),'R',1,'R');

	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?43:35),5,'b. Bangunan','LT');
	$pdf->Cell(20,5,number_format($row['luas_bangunan']),'T',0,'R');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(10,5,'m2','T');

	$pdf->Cell(10,5,'Rp.','LT');
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?46:30),5,number_format($row1['nilai_pasar_bangunan']),'T',0,'R');

	if($row1['jenis_perusahaan_penunjuk']=='2')
	{
		$pdf->Cell(10,5,'Rp.','TL');
		$pdf->Cell(30,5,number_format($row1['nilai_safetymargin_bangunan']),'T',0,'R');
	}

	$pdf->Cell(10,5,'Rp.','LT');
	$pdf->Cell(0,5,number_format($row1['nilai_likuidasi_bangunan']),'TR',1,'R');

	if($row1['jenis_perusahaan_penunjuk']=='1')
	{
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(73,5,'c. Sarana Pelengkap','LT');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(10,5,'Rp.','LT');
		$pdf->Cell(46,5,number_format($row1['nilai_pasar_sarana_pelengkap']),'T',0,'R');		

		$pdf->Cell(10,5,'Rp.','LT');
		$pdf->Cell(0,5,number_format($row1['nilai_likuidasi_sarana_pelengkap']),'TR',1,'R');
	}


	$pdf->SetFont('Arial','B',10);
	
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?73:65),5,'Nilai Properti','LT',0,'L');

	$pdf->Cell(10,5,'Rp.','LT');
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?46:30),5,number_format($row1['nilai_pasar_objek']),'T',0,'R');

	if($row1['jenis_perusahaan_penunjuk']=='2')
	{		
		$pdf->Cell(40,5,number_format($row1['nilai_safetymargin_objek']),'LT',0,'R');
	}

	$pdf->Cell(10,5,'Rp.','LT');
	$pdf->Cell(0,5,number_format($row1['nilai_likuidasi_objek']),'TR',1,'R');


	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?73:65),5,'Pembulatan','LTB',0,'L');

	$pdf->Cell(10,5,'Rp.','LTB');
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?46:30),5,number_format($row1['pembulatan_pasar_objek']),'TB',0,'R');

	if($row1['jenis_perusahaan_penunjuk']=='2')
	{		
		$pdf->Cell(40,5,number_format($row1['pembulatan_safetymargin_objek']),'LTB',0,'R');
	}

	$pdf->Cell(10,5,'Rp.','LTB');
	$pdf->Cell(0,5,number_format($row1['pembulatan_likuidasi_objek']),'TRB',0,'R');

	$pdf->ln(10);

	$pdf->SetFont('Arial','',10);

	$pdf->Cell(0,5,'Hormat Kami,',0,1);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,5,strtoupper($row1['perusahaan_penilai']),0,1);
	$pdf->SetFont('Arial','I',10);
	$pdf->Cell(0,5,'Registered Public Appraisers and Consultants',0,1);

	$pdf->ln(18);

	$pdf->SetFont('Arial','BU',10);
	$pdf->Cell(0,5,$row1['nama_reviewer1'],0,1);

	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,5,'Kepala Cabang',0,1);

	$pdf->SetFont('Arial','',10);
	$pdf->Cell(0,5,'Ijin Penilai Properti : '.$row1['ijin_penilai_reviewer1'],0,1);
	$pdf->Cell(0,5,'MAPPI : '.$row1['mappi_reviewer1'],0,1);
?>