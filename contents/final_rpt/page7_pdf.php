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

      if($j==1)
      {        
        $photos[$i][$j] = array('file_foto'=>'','keterangan'=>$i);

      }
    }
    

    $w = 75;
    $h = 44;
    $_x = $pdf->GetX();

    $pdf->SetWidths(array($w,$w));
    $pdf->SetFont('Arial','B',8);

    foreach($photos as $key1=>$val1)
    {
        $y = $pdf->GetY();

        $pdf->Cell(0,$h,'',1,'LR');

        $pdf->SetY($y);
    	$marks = array();

      	foreach($val1 as $key2=>$val2)
      	{
            if($val2['file_foto']!='')
            {
                $src = "../../uploads/property_photos/".$val2['file_foto'];
      		    $pdf->Image($src,$pdf->GetX(),$pdf->GetY(),$w,$h);
            }

      		$marks[] = array($val2['keterangan'],'C',4);

      		$pdf->SetX($pdf->GetX()+$w);      		
      	}
      	$pdf->SetX($_x);
      	$pdf->SetY($pdf->GetY()+$h);

      	$pdf->Row($marks);      	
    }
    
    $pdf->Cell(67.5,0.5,'','LT');
    $pdf->Cell(0,0.5,'','LRT',1);
    
    $pdf->SetFont('Arial','',8);
    
    $pdf->Cell(67.5,3,'','L',0,'C');    
    $pdf->Cell(0,3,'No. Laporan :','LR',1);
    
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(67.5,3,'TANAH DAN BANGUNAN','L',0,'C');    
    
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(0,3,$row1['no_laporan'],'LR',1);

    $pdf->Cell(67.5,0.5,'','L',0,'C');    
    $pdf->Cell(0,0.5,'','LR',1);
    
    $pdf->Cell(67.5,0.5,'','L');
    $pdf->Cell(0,0.5,'','LTR',1);

    $pdf->SetFont('Arial','',7);
    $pdf->Cell(67.5,3,$row1['alamat'].",",'L',0,'C');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(0,3,'Digambar :','LR',1);
    
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(67.5,3,"Kelurahan ".ucwords(strtolower($row1['kelurahan'])).", Kecamatan ".ucwords(strtolower($row1['kecamatan'])).",",'L',0,'C');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(0,3,$row1['perancang_foto_properti'],'LR',1);
    
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(67.5,3,ucwords(strtolower($row1['kota'])).", Provinsi ".ucwords(strtolower($row1['provinsi'])),'L',0,'C');
    $pdf->Cell(0,0.5,'','LR',1);

    $pdf->Cell(67.5,0.5,'','L');
    $pdf->Cell(0,0.5,'','LRT',1);

    $pdf->Cell(67.5,3,'','L',0,'C');    
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(0,3,'Diperiksa :','LR',1);
    
    $pdf->Cell(67.5,3,'','L',0,'C');    
    $pdf->Cell(0,3,$row1['nama_reviewer1'],'LR',1);

    $pdf->Cell(67.5,0.5,'','L',0,'C');    
    $pdf->Cell(53,0.5,'','L',0);
    $pdf->Cell(0,0.5,'','R',1);
    
    $pdf->Cell(67.5,0.5,'','L',0,'C');    
    $pdf->Cell(53,0.5,'','LT',0);
    $pdf->Cell(0,0.5,'','LRT',1);
    
    $pdf->Cell(67.5,3,'Pemberi Tugas :','L',0,'C');
    $pdf->Cell(53,3,'Disetujui :','L');
    $pdf->Cell(0,3,'Skala : '.$row1['skala_foto_properti'],'LR',1);
    
    $pdf->Cell(67.5,0.5,'','L');
    $pdf->Cell(53,0.5,'','L');
    $pdf->Cell(0,0.5,'','LR',1);
    
    $pdf->Cell(67.5,0.5,'','L');
    $pdf->Cell(26.5,0.5,'','LT',0,'C');
    $pdf->Cell(26.5,0.5,'','LT',0,'C');
    $pdf->Cell(0,0.5,'','LRT',1);
    
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(67.5,3,$row1['perusahaan_penunjuk'],'L',0,'C');    
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(26.5,3,'Tanggal','L',0,'C');
    $pdf->Cell(26.5,3,'Lembar','L',0,'C');
    $pdf->Cell(0,3,'Gambar :','LR',1);
    
    $pdf->Cell(67.5,0.5,'','L');
    $pdf->Cell(26.5,0.5,'','L',0,'C');
    $pdf->Cell(26.5,0.5,'','L',0,'C');
    $pdf->Cell(0,0.5,'','LR',1);
    
    $pdf->Cell(67.5,0.5,'','L');
    $pdf->Cell(26.5,0.5,'','LT',0,'C');
    $pdf->Cell(26.5,0.5,'','LT',0,'C');
    $pdf->Cell(0,0.5,'','LRT',1);
    
    $pdf->Cell(67.5,3,'Kantor Cabang '.$row1['kantor_cabang'],'L',0,'C');        
    $pdf->Cell(26.5,3,indo_date_format($row1['tgl_pemeriksaan'],'longDate'),'L',0,'C');
    $pdf->Cell(26.5,3,'01','L',0,'C');
    $pdf->Cell(0,3,'FOTO PROPERTI','LR',1,'C');
    
    $pdf->Cell(67.5,0.5,'','L');
    $pdf->Cell(26.5,0.5,'','L',0,'C');
    $pdf->Cell(26.5,0.5,'','L',0,'C');
    $pdf->Cell(0,0.5,'','LR',1);
    
    $pdf->Cell(67.5,1,'','L');    
    $pdf->Cell(0,1,'','LTR',1);

    $pdf->Cell(67.5,4,'','L',0,'C');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(0,4,strtoupper($_SYSTEM_PARAMS['nama_instansi']),'LR',1,'C');
    
    $pdf->Cell(67.5,3,'','L');    
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(0,3,'Ijin Usaha Jasa Penilai Publik No. '.$_SYSTEM_PARAMS['no_ijin_usaha'],'LR',1,'C');

    $pdf->Cell(67.5,1,'','LB');
    $pdf->Cell(0,1,'','LRB');
    
?>