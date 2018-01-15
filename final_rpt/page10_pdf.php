<?php
    $pdf->SetMargins(15,15,10);

	$pdf->AddPage();
	
	$pdf->SetFont('Arial','',10);	
	$pdf->Cell(0,4,'Kepada Yth.',0,1);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,4,'Kepala Cabang',0,1);
	$pdf->Cell(0,4,$row1['perusahaan_penunjuk'],0,1);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(0,4,$row1['alamat_perusahaan_penunjuk'],0,1);
	$pdf->Cell(0,4,$row1['kota_perusahaan_penunjuk'].' '.$row1['kode_pos_perusahaan_penunjuk'],0,1);

	$pdf->ln(4);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(20,4,'No.');
	$pdf->Cell(3,4,':',0,0,'C');
	$pdf->Cell(0,4,$row1['no_laporan'],0,1);
	$pdf->Cell(20,4,'Hal');
	$pdf->Cell(3,4,':',0,0,'C');
	$pdf->SetFont('Arial','BU',10);
	$pdf->Cell(0,4,'Laporan Penilaian',0,1);

	$pdf->ln(4);
	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(0,4,'Dengan hormat,',0,1);	
	$pdf->Cell(0,4,'Sesuai dengan penugasan, kami telah melakukan inspeksi dan penilaian properti sebagai berikut :',0,1);	

	$pdf->Cell(50,4,'Nomor Penugasan');
	$pdf->Cell(3,4,':');
	$pdf->Cell(0,4,$row1['no_penugasan'],0,1);

	$pdf->Cell(50,4,'Tanggal Penugasan');
	$pdf->Cell(3,4,':');
	$pdf->Cell(0,4,indo_date_format($row1['tgl_penugasan'],'longDate'),0,1);

	$pdf->Cell(50,4,'Inspeksi Lapangan');
	$pdf->Cell(3,4,':');
	$pdf->Cell(0,4,indo_date_format($row1['tgl_pemeriksaan'],'longDate'),0,1);

	$pdf->Cell(50,4,'Tanggal Penilaian');
	$pdf->Cell(3,4,':');
	$pdf->Cell(0,4,indo_date_format($row1['tgl_pemeriksaan'],'longDate'),0,1);

	$pdf->Cell(50,4,'Alamat Properti');
	$pdf->Cell(3,4,':');
	$text = $row1['alamat'].", Kelurahan ".$row1['kelurahan'].", Kecamatan ".$row1['kecamatan'].", Kota "
            .$row1['kota'].", Provinsi ".$row1['provinsi'];
	$pdf->MultiCell(0,4,$text);

	$pdf->Cell(50,4,'Calon Debitur Atas Nama');
	$pdf->Cell(3,4,':');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,4,$row1['nama_debitur'],0,1);

	$pdf->ln(2);

	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,4,'FOTO PROPERTI',1,1,'C');

	$sql = "SELECT * FROM foto_properti WHERE fk_penugasan='".$id_penugasan_dec."' limit 0,4";
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

    $w = 92.5;
    $h = 58;
    $_x = $pdf->GetX();

    $pdf->SetWidths(array($w,$w));
    $pdf->SetFont('Arial','B',8);

    foreach($photos as $key1=>$val1)
    {
    	$marks = array();
      	foreach($val1 as $key2=>$val2)
      	{
      		$pdf->Image('../../uploads/property_photos/'.$val2['file_foto'],$pdf->GetX(),$pdf->GetY(),$w,$h);
      		$marks[] = array($val2['keterangan'],'C');

      		$pdf->SetX($pdf->GetX()+$w);      		
      	}
      	$pdf->SetX($_x);
      	$pdf->SetY($pdf->GetY()+$h);

      	$pdf->Row($marks);      	
    }

    $pdf->ln(2);
    $pdf->SetFont('Arial','BU',8);
    $pdf->Cell(0,4,'RINGKASAN',0,1);
    
    if($row1['jenis_perusahaan_penunjuk']=='1')        
        $pdf->SetWidths(array(45,35,35,35,35));
    else
        $pdf->SetWidths(array(45,28,28,28,28,28));

    $pdf->SetFont('Arial','B',8);

    $_rows = array(                
                array("OBJEK","C"),
                array("NILAI PASAR (NP) (Rp.)","C"),
                array("RATA-RATA/M2 (Rp.)","C"));

    if($row1['jenis_perusahaan_penunjuk']=='2')
    {
        $_rows[] = array("(NP) SETELAH SAFETY MARGIN","C");
    }

    $_rows[] = array("INDIKASI NILAI LIKUIDASI","C");
    $_rows[] = array("RATA-RATA/M2 (Rp.)","C");

    $pdf->Row($_rows);

    $pdf->Cell(20,4,'a. Tanah','L');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(19,4,number_format($row1['luas_tanah'],2,'.',','),0,0,'R');
    $pdf->Cell(6,4,'m2',0);
    
    $pdf->Cell(6,4,'Rp.','L');
    $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?29:22),4,number_format($row1['nilai_pasar_tanah']),0,0,'R');

    $pdf->Cell(10,4,'@Rp.','L');
    $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?25:18),4,number_format($row1['nilai_satuan_tanah']),0,0,'R');

    if($row1['jenis_perusahaan_penunjuk']=='2')
    {
        $pdf->Cell(6,4,'Rp.','L');
        $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?29:22),4,number_format($row1['nilai_safetymargin_tanah']),0,0,'R');
    }

    $pdf->Cell(6,4,'Rp.','L');
    $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?29:22),4,number_format($row1['nilai_likuidasi_tanah']),0,0,'R');

    $pdf->Cell(10,4,'@Rp.','L');
    $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?25:18),4,number_format($row1['nilai_satuan_likuidasi_tanah']),'R',1,'R');

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(20,4,'b. Bangunan','LT');

    $pdf->SetFont('Arial','',8);
    $pdf->Cell(19,4,number_format($row1['luas_bangunan'],2,'.',','),'T',0,'R');
    $pdf->Cell(6,4,'m2','T');

    $pdf->Cell(6,4,'Rp.','LT');
    $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?29:22),4,number_format($row1['nilai_pasar_bangunan']),'T',0,'R');

    $pdf->Cell(10,4,'@Rp.','LT');
    $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?25:18),4,number_format($row1['nilai_satuan_bangunan']),'T',0,'R');

    if($row1['jenis_perusahaan_penunjuk']=='2')
    {
        $pdf->Cell(6,4,'Rp.','LT');
        $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?29:22),4,number_format($row1['nilai_safetymargin_bangunan']),'T',0,'R');
    }

    $pdf->Cell(6,4,'Rp.','LT');
    $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?29:22),4,number_format($row1['nilai_likuidasi_bangunan']),'T',0,'R');

    $pdf->Cell(10,4,'@Rp.','LT');
    $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?25:18),4,number_format($row1['nilai_satuan_likuidasi_bangunan']),'TR',1,'R');


    if($row1['jenis_perusahaan_penunjuk']=='1')
    {
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(45,4,'c. Sarana Pelengkap','LT');

        $pdf->SetFont('Arial','',8);
        $pdf->Cell(6,4,'Rp.','LT');
        $pdf->Cell(29,4,number_format($row1['nilai_pasar_sarana_pelengkap']),'T',0,'R');

        $pdf->Cell(35,4,'','LT');        

        $pdf->Cell(6,4,'Rp.','LT');
        $pdf->Cell(29,4,number_format($row1['nilai_likuidasi_sarana_pelengkap']),'T',0,'R');

        $pdf->Cell(35,4,'','LTR',1);
    }


    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(45,4,'Nilai Properti','LT');
    
    $pdf->Cell(6,4,'Rp.','LT');
    $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?29:22),4,number_format($row1['nilai_pasar_objek']),'T',0,'R');

    $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?35:28),4,'','LT');    

    if($row1['jenis_perusahaan_penunjuk']=='2')
    {
        $pdf->Cell(6,4,'Rp.','LT');
        $pdf->Cell(22,4,number_format($row1['nilai_safetymargin_objek']),'T',0,'R');
    }

    $pdf->Cell(6,4,'Rp.','LT');
    $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?29:22),4,number_format($row1['nilai_likuidasi_objek']),'T',0,'R');

    $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?35:28),4,'','LTR',1);


    $pdf->Cell(45,4,'Pembulatan','LTB');
    
    $pdf->Cell(6,4,'Rp.','LTB');
    $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?29:22),4,number_format($row1['pembulatan_pasar_objek']),'TB',0,'R');

    $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?35:28),4,'','LTB');

    if($row1['jenis_perusahaan_penunjuk']=='2')
    {
        $pdf->Cell(6,4,'Rp.','LTB');
        $pdf->Cell(22,4,number_format($row1['pembulatan_safetymargin_objek']),'TB',0,'R');
    }

    $pdf->Cell(6,4,'Rp.','LTB');
    $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?29:22),4,number_format($row1['pembulatan_likuidasi_objek']),'TB',0,'R');

    $pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?35:28),4,'','LTRB',1);

    $pdf->ln(2);

    $pdf->SetFont('Arial','',8);
    $pdf->Cell(0,4,'Catatan :',0,1);
    $text = "Berdasarkan surat penugasan No. ".$row1['no_penugasan']." alamat properti berada di ".$row1['alamat']." Kelurahan ".$row1['kelurahan']
	        ."Kecamatan ".$row1['kecamatan']." ".$row1['kota'].". Pada saat inspeksi lapangan, alamat lengkap properti berada di ".$row1['alamat']." Kelurahan ".$row1['kelurahan'].", "
	        ."Kecamatan ".$row1['kecamatan'].", Kota ".$row1['kota'].", Provinsi ".$row1['provinsi'];
    $pdf->MultiCell(0,3,$text);

?>