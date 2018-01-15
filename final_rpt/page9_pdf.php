<?php
	$pdf->SetMargins(25,15,18);    

	$pdf->AddPage();

    $pdf->Image('../../uploads/logo/01.jpg',16,6,12);
    
    $pdf->ln(8);
    
    $pdf->SetFont('Arial','B',10);
      
    // $pdf->Cell(0,7,'PETA DENAH LOKASI',1,1,'C');

    // $src = (isset($maps['peta2'])?"../../uploads/location_maps/".$maps['peta2']:"../../assets/images/no-thumb.png");
    // $pdf->Image($src,$pdf->GetX(),$pdf->GetY(),167,75);

    // $pdf->SetX($pdf->GetX()+167);
    // $pdf->SetY($pdf->GetY()+75);

    $pdf->Cell(83.7,7,'PELETAKAN TANAH','LTB',0,'C');
    $pdf->Cell(0,7,'PELETAKAN BANGUNAN','TBR',1,'C');

    $src = (isset($maps['peletakan_tanah'])?"../../uploads/location_maps/".$maps['peletakan_tanah']:"../../assets/images/no-thumb.png");
    $pdf->Image($src,$pdf->GetX(),$pdf->GetY(),83.7,110);

    $src = (isset($maps['peletakan_bangunan'])?"../../uploads/location_maps/".$maps['peletakan_bangunan']:"../../assets/images/no-thumb.png");
    $pdf->SetX($pdf->GetX()+83.7);
    $pdf->Image($src,$pdf->GetX(),$pdf->GetY(),83.7,110);


    $pdf->SetY($pdf->GetY()+110);

    $pdf->Cell(80.5,2,'','LT');
    $pdf->Cell(54,2,'','LT');
    $pdf->Cell(0,2,'','LTR',1);

    $pdf->SetFont('Arial','',8);
    $pdf->Cell(80.5,3,'','L');
    $pdf->Cell(54,3,'Digambar :','L');
    $pdf->Cell(0,3,'No. Laporan :','LR',1);

    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(80.5,3,'TANAH DAN BANGUNAN','L',0,'C');
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(54,3,$row1['perancang_peta_lokasi'],'L');
    $pdf->Cell(0,3,$row1['no_laporan'],'LR',1);

    $pdf->Cell(80.5,2,'','L');
    $pdf->Cell(54,2,'','L');
    $pdf->Cell(0,2,'','LR',1);

    $pdf->Cell(80.5,2,'','L');
    $pdf->Cell(54,2,'','LT');
    $pdf->Cell(0,2,'','LR',1);

    $pdf->Cell(80.5,3,$row1['alamat'],'L',0,'C');    
    $pdf->Cell(54,3,'Diperiksa :','L');
    $pdf->Cell(0,3,'','LR',1);

    $pdf->Cell(80.5,3,"Kelurahan ".$row1['kelurahan'].", Kecamatan ".$row1['kecamatan'].",",'L',0,'C');    
    $pdf->Cell(54,3,$row1['nama_reviewer1'],'L');
    $pdf->Cell(0,3,'','LR',1);

    $pdf->Cell(80.5,3,"Kota ".$row1['kota'].", Provinsi ".$row1['provinsi'].",",'L',0,'C');    
    $pdf->Cell(54,3,'','L');
    $pdf->Cell(0,3,'','LR',1);

    $pdf->Cell(80.5,2,'','L');
    $pdf->Cell(54,2,'','LT');
    $pdf->Cell(0,2,'','LTR',1);

    $pdf->Cell(80.5,3,'','L',0,'C');    
    $pdf->Cell(54,3,'Disetujui :','L');
    $pdf->Cell(0,3,'Skala : '.$row1['skala_peta_lokasi'],'LR',1);

    $pdf->Cell(80.5,3,'Pemberi Tugas :','L',0,'C');    
    $pdf->Cell(54,3,'','L');
    $pdf->Cell(0,3,'','LR',1);

    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(80.5,4,$row1['perusahaan_penunjuk'],'L',0,'C');    
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(27,4,'Tanggal','LT',0,'C');
    $pdf->Cell(27,4,'Lembar','LT',0,'C');
    $pdf->Cell(0,4,'Gambar :','LRT',1);

    $pdf->Cell(80.5,4,'Kantor Cabang '.$row1['kantor_cabang'],'L',0,'C');        
    $pdf->Cell(27,4,indo_date_format($row1['tgl_pemeriksaan'],'longDate'),'LT',0,'C');
    $pdf->Cell(27,4,'01','LT',0,'C');
    $pdf->Cell(0,4,'DENAH LOKASI','LRT',1,'C');

    $pdf->Cell(80.5,1,'','L');    
    $pdf->Cell(0,1,'','LTR',1);

    $pdf->Cell(80.5,4,'','L',0,'C');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(0,4,strtoupper($_SYSTEM_PARAMS['nama_instansi']),'LR',1,'C');
    
    $pdf->Cell(80.5,3,'','L');    
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(0,3,'Ijin Usaha Jasa Penilai Publik No. '.$_SYSTEM_PARAMS['no_ijin_usaha'],'LR',1,'C');

    $pdf->Cell(80.5,1,'','LB');
    $pdf->Cell(0,1,'','LRB');
?>