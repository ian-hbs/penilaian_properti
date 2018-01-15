<?php
	$pdf->SetMargins(32,5,28);

	$pdf->AddPage();

    $pdf->Image('../../uploads/logo/01.jpg',16,6,12);
    
    $pdf->ln(15);
    
    $pdf->SetFont('Arial','B',10);
    
    $pdf->Cell(0,1,'','LTR',1);
    $pdf->Cell(0,3,'FOTO PROPERTI','LR',1,'C');

	$pdf->SetFont('Arial','',10);    	
    $pdf->Cell(0,3,'Beberapa foto properti yang diperoleh pada saat inspeksi di lapangan','LR',1,'C');

    $pdf->Cell(0,1,'','LBR',1);

    $sql = "SELECT * FROM foto_properti WHERE fk_penugasan='".$id_penugasan_dec."'";
    $result = $db->Execute($sql);
    if(!$result)
      echo $db->ErrorMsg();

    $photos = array();
    $i=0;
    $j=0;
    while($row2 = $result->FetchRow())
    {
      $photos[$i][$j] = array('file_foto'=>$row2['file_foto'],'keterangan'=>$row2['keterangan']);
      if(($j+1)%2==0)
      {
        $j=0;
        $i++;
      }
      else
        $j++;
    }

    $w = 75;
    $h = 44;
    $_x = $pdf->GetX();

    $pdf->SetWidths(array($w,$w));
    $pdf->SetFont('Arial','B',8);

    foreach($photos as $key1=>$val1)
    {
    	$marks = array();
      	foreach($val1 as $key2=>$val2)
      	{
      		$pdf->Image('../../uploads/property_photos/'.$val2['file_foto'],$pdf->GetX(),$pdf->GetY(),$w,$h);
      		$marks[] = array($val2['keterangan'],'C',4);

      		$pdf->SetX($pdf->GetX()+$w);      		
      	}
      	$pdf->SetX($_x);
      	$pdf->SetY($pdf->GetY()+$h);

      	$pdf->Row($marks);      	
    }

    $pdf->Cell(67.5,2,'','LT');
    $pdf->Cell(53,2,'','LT');
    $pdf->Cell(0,2,'','LTR',1);

    $pdf->SetFont('Arial','',8);
    $pdf->Cell(67.5,3,'','L');
    $pdf->Cell(53,3,'Digambar :','L');
    $pdf->Cell(0,3,'No. Laporan :','LR',1);

    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(67.5,3,'TANAH DAN BANGUNAN','L',0,'C');
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(53,3,$row1['perancang_foto_properti'],'L');
    $pdf->Cell(0,3,$row1['no_laporan'],'LR',1);

    $pdf->Cell(67.5,2,'','L');
    $pdf->Cell(53,2,'','L');
    $pdf->Cell(0,2,'','LR',1);

    $pdf->Cell(67.5,2,'','L');
    $pdf->Cell(53,2,'','LT');
    $pdf->Cell(0,2,'','LR',1);

    $pdf->Cell(67.5,3,$row1['alamat'],'L',0,'C');    
    $pdf->Cell(53,3,'Diperiksa :','L');
    $pdf->Cell(0,3,'','LR',1);

    $pdf->Cell(67.5,3,"Kelurahan ".$row1['kelurahan'].", Kecamatan ".$row1['kecamatan'].",",'L',0,'C');    
    $pdf->Cell(53,3,$row1['nama_reviewer1'],'L');
    $pdf->Cell(0,3,'','LR',1);

    $pdf->Cell(67.5,3,"Kota ".$row1['kota'].", Provinsi ".$row1['provinsi'].",",'L',0,'C');    
    $pdf->Cell(53,3,'','L');
    $pdf->Cell(0,3,'','LR',1);

    $pdf->Cell(67.5,2,'','L');
    $pdf->Cell(53,2,'','LT');
    $pdf->Cell(0,2,'','LTR',1);

    $pdf->Cell(67.5,3,'','L',0,'C');    
    $pdf->Cell(53,3,'Disetujui :','L');
    $pdf->Cell(0,3,'Skala : '.$row1['skala_foto_properti'],'LR',1);

    $pdf->Cell(67.5,3,'Pemberi Tugas :','L',0,'C');    
    $pdf->Cell(53,3,'','L');
    $pdf->Cell(0,3,'','LR',1);

    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(67.5,4,$row1['perusahaan_penunjuk'],'L',0,'C');    
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(26.5,4,'Tanggal','LT',0,'C');
    $pdf->Cell(26.5,4,'Lembar','LT',0,'C');
    $pdf->Cell(0,4,'Gambar :','LRT',1);

    $pdf->Cell(67.5,4,'Kantor Cabang '.$row1['kantor_cabang'],'L',0,'C');        
    $pdf->Cell(26.5,4,indo_date_format($row1['tgl_pemeriksaan'],'longDate'),'LT',0,'C');
    $pdf->Cell(26.5,4,'01','LT',0,'C');
    $pdf->Cell(0,4,'FOTO PROPERTI','LRT',1,'C');

    $pdf->Cell(67.5,1,'','L');
    $pdf->Cell(0,1,'','LTR',1);

    $pdf->Cell(67.5,4,'','L',0,'C');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(0,4,strtoupper($_SYSTEM_PARAMS['nama_instansi']),'LR',1,'C');
    
    $pdf->Cell(67.5,3,'','L');    
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(0,3,'Ijin Usaha Jasa Penilai Publik No. '.$_SYSTEM_PARAMS['no_ijin_usaha'],'LR',1,'C');

    $pdf->Cell(67.5,1,'','LB');
    $pdf->Cell(0,1,'','LRB');

    
    
?>