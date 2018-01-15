<?php
    $pdf->SetMargins(15,15,10);
    
    $pdf->AddPage();

    $pdf->Image('../../uploads/logo/01.jpg',16,6,12);
    
    $pdf->ln(8);
    
    $pdf->SetFont('Arial','B',8);
            
    $pdf->Cell(0,5,'D.1 DATA PEMBANDING',0,1);
    
    $pdf->SetWidths(array(10,69,20,20,18,18,30));
    $pdf->SetFont('Arial','B',8);
    $pdf->Row(array(
                array("No.","C"),
                array("Alamat","C"),
                array("Jenis Properti","C"),
                array("Surat Tanah","C"),
                array("LT (m2)","C"),
                array("LB (m2)","C"),
                array("Penawaran/  Transaksi","C"),
    ));

    $sql = "SELECT a.land_title,a.land_area,a.building_area,a.offering_price,b.alamat,b.jenis_objek FROM perhitungan_tanah_pembanding as a 
            LEFT JOIN (SELECT x.id_objek_pembanding,x.alamat,y.jenis_objek FROM objek_pembanding as x 
            LEFT JOIN ref_jenis_objek as y ON (x.fk_jenis_objek=y.id_jenis_objek)) as b ON (a.fk_objek_pembanding=b.id_objek_pembanding)
            WHERE (fk_penugasan='".$id_penugasan_dec."')";
    $result = $db->Execute($sql);
    if(!$result)
        echo $db->ErrorMsg();
    $pdf->SetFont('Arial','',8);
    $no=0;
    while($row3 = $result->FetchRow())
    {
        $no++;

        $pdf->Row(array(
                    array($no,'C'),
                    array($row3['alamat']),
                    array($row3['jenis_objek'],'C'),
                    array($row3['land_title'],'C'),
                    array(number_format($row3['land_area'],2,'.',','),'R'),
                    array(number_format($row3['building_area'],2,'.',','),'R'),
                    array(number_format($row3['offering_price']),'R'),
                ));            
    }     
    $pdf->ln();
    $pdf->Cell(0,1,'','LTR',1);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(0,5,'KESIMPULAN DAN REKOMENDASI','LR',1);
    
    $no = 1;

    $pdf->SetFont('Arial','',8);
    $pdf->Cell(5,5,$no.'.','L');
    $pdf->Cell(0,5,'NILAI PASAR TANAH DAN BANGUNAN SERTA SARANA PELENGKAP ADALAH SEBESAR :','R',1);        

    $pdf->Cell(27,5,'','L');
    $pdf->Cell(95,5,'NILAI PASAR TANAH');
    $pdf->Cell(10,5,'Rp.');

    $npt = $row1['nilai_pasar_tanah'];

    $pdf->Cell(30,5,number_format($npt),0,0,'R');
    $pdf->Cell(0,5,'','R',1);

    $pdf->Cell(27,5,'','L');
    $pdf->Cell(95,5,'NILAI PASAR BANGUNAN & SARANA PELENGKAP');
    $pdf->Cell(10,5,'Rp.');
    
    $npb_sp = $row1['nilai_pasar_bangunan']+$row1['nilai_pasar_sarana_pelengkap'];

    $pdf->Cell(30,5,number_format($npb_sp),0,0,'R');
    $pdf->Cell(0,5,'','R',1);

    $pdf->Cell(27,5,'','L');
    $pdf->Cell(95,5,'NILAI OBYEK KESELURUHAN');
    $pdf->Cell(10,5,'Rp.');
    
    $nok = $npt+$npb_sp;
    
    $pdf->Cell(30,5,number_format($nok),0,0,'R');
    $pdf->Cell(0,5,'','R',1);


    $pdf->Cell(27,5,'','L');
    $pdf->Cell(95,5,'NILAI PASAR');
    $pdf->SetFont('Arial','B','8');
    $pdf->Cell(10,5,'Rp.');        
    $pdf->Cell(30,5,number_format($row1['pembulatan_pasar_objek']),0,0,'R');
    $pdf->Cell(0,5,'','R',1);


    if($row1['jenis_perusahaan_penunjuk']=='2')
    {
        $no++;
        $pdf->Cell(0,5,'','LR',1);

        $pdf->SetFont('Arial','',8);
        $pdf->Cell(5,5,$no.'.','L');
        $pdf->Cell(0,5,'NILAI PASAR SETELAH SAFETY MARGIN UNTUK TANAH DAN BANGUNAN ADALAH SEBESAR :','R',1);        

        $pdf->Cell(27,5,'','L');
        $pdf->Cell(95,5,'NILAI PASAR TANAH SETELAH SAFETY MARGIN');
        $pdf->Cell(10,5,'Rp.');

        $nsmt = $row1['nilai_safetymargin_tanah'];
        $pdf->Cell(30,5,number_format($nsmt),0,0,'R');
        $pdf->Cell(0,5,'','R',1);

        $pdf->Cell(27,5,'','L');
        $pdf->Cell(95,5,'NILAI PASAR BANGUNAN SETELAH SAFETY MARGIN');
        $pdf->Cell(10,5,'Rp.');
        
        $nsmb = $row1['nilai_safetymargin_bangunan'];
        $pdf->Cell(30,5,number_format($nsmb),0,0,'R');
        $pdf->Cell(0,5,'','R',1);

        $pdf->Cell(27,5,'','L');
        $pdf->Cell(95,5,'NILAI OBYEK KESELURUHAN');
        $pdf->Cell(10,5,'Rp.');
        
        $nok = $nsmt+$nsmb;
        
        $pdf->Cell(30,5,number_format($nok),0,0,'R');
        $pdf->Cell(0,5,'','R',1);


        $pdf->Cell(27,5,'','L');
        $pdf->Cell(95,5,'NILAI PASAR SETELAH SAFETY MARGIN');
        $pdf->SetFont('Arial','B','8');
        $pdf->Cell(10,5,'Rp.');
        $pdf->Cell(30,5,number_format($row1['pembulatan_safetymargin_objek']),0,0,'R');
        $pdf->Cell(0,5,'','R',1);
    }

    $pdf->Cell(0,5,'','LR',1);

    $pdf->SetFont('Arial','',8);

    $no++;
    $pdf->Cell(5,5,$no.'.','L');
    $pdf->Cell(0,5,'INDIKASI NILAI LIKUIDASI ATAS TANAH DAN BANGUNAN ADALAH SEBESAR (PEMBULATAN) :','R',1);        

    $pdf->Cell(27,5,'','L');
    $pdf->Cell(95,5,'INDIKASI NILAI LIKUIDASI');
    $pdf->SetFont('Arial','B','8');
    $pdf->Cell(10,5,'Rp.');        
    $pdf->Cell(30,5,number_format($row1['pembulatan_likuidasi_objek']),0,0,'R');
    $pdf->Cell(0,5,'','R',1);

    $pdf->Cell(0,5,'','LR',1);

   /* $no++;
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(5,5,$no.'.','L');
    $pdf->Cell(0,5,'FAKTOR YANG DAPAT MENAMBAH NILAI :','R',1);

    $pdf->Cell(5,5,'','L');
    $pdf->Cell(20,5,'Tanah');
    $pdf->Cell(5,5,':');
    $pdf->Cell(0,5,$row1['faktor_penambah_nilai_tanah'],'R',1);

    $pdf->Cell(5,5,'','L');
    $pdf->Cell(20,5,'Bangunan');
    $pdf->Cell(5,5,':');
    $pdf->Cell(0,5,$row1['faktor_penambah_nilai_bangunan'],'R',1);

    $pdf->Cell(0,5,'','LR',1);
    
    $no++;
    $pdf->Cell(5,5,$no.'.','L');
    $pdf->Cell(0,5,'FAKTOR YANG DAPAT MENGURANGI NILAI :','R',1);

    $pdf->Cell(5,5,'','L');
    $pdf->Cell(20,5,'Tanah');
    $pdf->Cell(5,5,':');
    $pdf->Cell(0,5,$row1['faktor_pengurang_nilai_tanah'],'R',1);

    $pdf->Cell(5,5,'','L');
    $pdf->Cell(20,5,'Bangunan');
    $pdf->Cell(5,5,':');
    $pdf->Cell(0,5,$row1['faktor_pengurang_nilai_bangunan'],'R',1);
*/
    $pdf->Cell(0,5,'','LR',1);

    $pdf->SetFont('Arial','U',8);
    $pdf->Cell(0,3,'Catatan Penilai','LR',1);

    $pdf->SetFont('Arial','',8);

    $sql = "SELECT * FROM catatan_penilai WHERE fk_penugasan='".$id_penugasan_dec."'";
    $result = $db->Execute($sql);
    if(!$result)
        echo $db->ErrorMsg();

    while($row3 = $result->FetchRow())
    {
        $pdf->Cell(0,3,'- '.$row3['catatan'],'LR',1);
    }

    $pdf->SetFont('Arial','',10);

    $pdf->Cell(0,5,'','LBR',1);
?>