<?php
	  $pdf->SetMargins(16,15,10);

	  $pdf->AddPage();

    $pdf->Image('../../uploads/logo/01.jpg',16,6,12);
    
    $pdf->ln(8);
    
    $pdf->SetFont('Arial','B',10);
      
    $pdf->Cell(0,7,'DATA PEMBANDING',1,1,'C');


    $sql = "SELECT a.no_urut,b.file_foto,b.keterangan FROM objek_pembanding as a LEFT JOIN foto_properti_pembanding as b ON (a.id_objek_pembanding=b.fk_objek_pembanding) 
            WHERE a.fk_penugasan='".$id_penugasan_dec."'";

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

    $w = 92;
    $h = 53;
    $_x = $pdf->GetX();

    $pdf->SetWidths(array($w,$w));
    $pdf->SetFont('Arial','B',8);

    foreach($photos as $key1=>$val1)
    {
    	$marks = array();
      	foreach($val1 as $key2=>$val2)
      	{
      		$src = (!is_null($val2['file_foto'])?"../../uploads/comparative_property_photos/".$val2['file_foto']:"../../assets/images/no-thumb.png");
      		$pdf->Image($src,$pdf->GetX(),$pdf->GetY(),$w,$h);
      		$marks[] = array($val2['keterangan'],'C');

      		$pdf->SetX($pdf->GetX()+$w);      		
      	}
      	$pdf->SetX($_x);
      	$pdf->SetY($pdf->GetY()+$h);

      	$pdf->Row($marks);      	
    }

    $pdf->ln(3);
    $pdf->SetFont('Arial','B',10);    
    $pdf->Cell(0,7,'PETA LOKASI',1,1,'C');
    $pdf->Image('../../uploads/location_maps/'.$row1['foto_peta'],$pdf->GetX(),$pdf->GetY(),184,110);
?>