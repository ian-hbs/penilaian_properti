<?php
	
	$pdf->SetMargins(10,15,3);

	$pdf->AddPage('L','A4');

	$pdf->Image('../../uploads/logo/01.jpg',12,6,12);

	$pdf->ln(8);
	
	$pdf->SetFillColor(229,229,229);
	$pdf->SetFont('Arial','B',8);

    $pdf->Cell(0,0.5,'','LTR',1,'C',true);    

    $pdf->Cell(0,3,'PROPERTY VALUATION BY COST APPROACH (FISIK)','LR',1,'C',true);

    $pdf->Cell(0,0.5,'','LBR',1,'C',true);
    
    $pdf->ln(1);

    $pdf->Cell(0,2,'','LTR',1);

    $pdf->SetFont('Arial','',8);

    $pdf->Cell(5,3,'','L');
    $pdf->Cell(0,3,'REAL ESTATE VALUATION','R',1);

    $pdf->Cell(5,3,'','L');
    $pdf->Cell(0,3,$row1['nama_debitur'],'R',1);

    $pdf->Cell(5,3,'','L');
    $pdf->Cell(0,3,$row1['alamat'].',','R',1);

    $pdf->Cell(5,3,'','L');
    $pdf->Cell(0,3,'Kelurahan '.ucwords(strtolower($row1['kelurahan'])).', Kecamatan '.ucwords(strtolower($row1['kecamatan'])).', '.ucwords(strtolower($row1['kota'])).',','R',1);

    $pdf->Cell(5,3,'','L');
    $pdf->Cell(0,3,'Provinsi '.ucwords(strtolower($row1['provinsi'])).',','R',1);

    $pdf->Cell(5,3,'','L');
    $pdf->Cell(0,3,$row1['kd_pos'],'R',1);

    $pdf->Cell(0,2,'','LBR',1);

    $pdf->ln(1);

    $pdf->SetFont('Arial','B',6);

    $pdf->Cell(5,8,'No.','LTB',0,'C',true);
    $pdf->Cell(35,8,'Description','LTB',0,'C',true);
    $pdf->Cell(5,8,'Qty','LTB',0,'C',true);
    $pdf->Cell(10,4,'Year','LT',0,'C',true);
    $pdf->Cell(10,4,'Year','LT',0,'C',true);
    $pdf->Cell(12,8,'Const.','LTB',0,'C',true);
    $pdf->Cell(12,4,'Eco. Use.','LT',0,'C',true);
    $pdf->Cell(14,4,'Cond. on','LT',0,'C',true);
    $pdf->Cell(12,4,'Main-','LT',0,'C',true);
    $pdf->Cell(12,4,'Phys.','LT',0,'C',true);
    $pdf->Cell(12,4,'Func.','LT',0,'C',true);
    $pdf->Cell(12,4,'Eco.','LT',0,'C',true);
    $pdf->Cell(12,4,'Location','LT',0,'C',true);
    $pdf->Cell(14,8,'Floor Area','LTB',0,'C',true);
    $pdf->Cell(14,4,'Total Floor','LT',0,'C',true);
    $pdf->Cell(16,4,'Cost/sqm','LT',0,'C',true);
    $pdf->Cell(16,4,'Cost/sqm','LT',0,'C',true);
    $pdf->Cell(16,4,'CRN','LT',0,'C',true);
    $pdf->Cell(12,4,$row1['remain_year'],'LT',0,'C',true);
    $pdf->Cell(16.5,4,'MV','LT',0,'C',true);
    $pdf->Cell(0,4,'LV','LTR',1,'C',true);

    $pdf->Cell(45,4,'','L');
    $pdf->Cell(10,4,'Build','LB',0,'C',true);
    $pdf->Cell(10,4,'Renov','LB',0,'C',true);
    $pdf->Cell(12,4,'','L');
  	$pdf->Cell(12,4,'Life','LB',0,'C',true);
  	$pdf->Cell(14,4,'Inspec.B/C/K','LB',0,'C',true);
  	$pdf->Cell(12,4,'tenance','LB',0,'C',true);
  	$pdf->Cell(12,4,'Deter.','LB',0,'C',true);
  	$pdf->Cell(12,4,'Obsc.','LB',0,'C',true);
  	$pdf->Cell(12,4,'Obsc.','LB',0,'C',true);
  	$pdf->Cell(12,4,'Index','LB',0,'C',true);
  	$pdf->Cell(14,4,'','L');
  	$pdf->Cell(14,4,'Area','LB',0,'C',true);
  	$pdf->Cell(16,4,'Rp.','LB',0,'C',true);
  	$pdf->Cell(16,4,'Rp.','LB',0,'C',true);
  	$pdf->Cell(16,4,'Rp.','LB',0,'C',true);
  	$pdf->Cell(12,4,'Remain','LB',0,'C',true);
  	$pdf->Cell(16.5,4,'Rp.','LB',0,'C',true);
  	$pdf->Cell(0,4,'Rp.','LBR',1,'C',true);

  	$pdf->ln(1);

  	$pdf->SetFont('Arial','B',8);

  	$pdf->Cell(40,4,'BUILDINGS',1,1,'L',true);

  	$pdf->ln(1);

  	$sql = "SELECT * FROM perhitungan_bangunan WHERE(fk_penugasan='".$id_penugasan_dec."') AND (type='building')";

  	$result = $db->Execute($sql);
  	if(!$result)
    	echo $db->ErrorMsg();

    $pdf->SetFont('Arial','',6);

    $n_rows = $result->RecordCount();
    $no = 0;

    $tot_floor_area = 0;
    $gtot_floor_area = 0;
    $tot_crn = 0;
    $tot_mv = 0;
    $tot_lv = 0;

    while($row2 = $result->FetchRow())
    {
    	$no++;
    	$b = ($no==$n_rows?'B':'');

    	$tot_floor_area += $row2['floor_area'];
    	$gtot_floor_area += $row2['total_floor_area'];
    	$tot_crn += $row2['crn'];
    	$tot_mv += $row2['market_value'];
    	$tot_lv += $row2['liquidation_value'];

    	$pdf->Cell(5,4,$no,'LT'.$b,0,'C');
    	$pdf->Cell(35,4,$row2['description'],'LT'.$b);
    	$pdf->Cell(5,4,$row2['qty'],'LT'.$b,0,'C');
    	$pdf->Cell(10,4,$row2['built_year'],'LT'.$b,0,'C');
    	$pdf->Cell(10,4,$row2['renov_year'],'LT'.$b,0,'C');
    	$pdf->Cell(12,4,$row2['construction'],'LT'.$b,0,'C');
    	$pdf->Cell(12,4,$row2['eco_use_life'],'LT'.$b,0,'C');
    	$pdf->Cell(14,4,$row2['cond_on_inspec'],'LT'.$b,0,'C');
    	$pdf->Cell(12,4,number_format($row2['maintenance'],1,'.',',').'%','LT'.$b,0,'C');
    	$pdf->Cell(12,4,number_format($row2['phys_deter'],1,'.',',').'%','LT'.$b,0,'C');
    	$pdf->Cell(12,4,number_format($row2['func_obsc'],1,'.',',').'%','LT'.$b,0,'C');
    	$pdf->Cell(12,4,number_format($row2['eco_obsc'],1,'.',',').'%','LT'.$b,0,'C');
    	$pdf->Cell(12,4,$row2['location_index'],'LT'.$b,0,'C');
    	$pdf->Cell(14,4,number_format($row2['floor_area'],2,'.',','),'LT'.$b,0,'R');
    	$pdf->Cell(14,4,number_format($row2['total_floor_area'],2,'.',','),'LT'.$b,0,'R');
    	$pdf->Cell(16,4,number_format($row2['cost_sqm1'],0,'.',','),'LT'.$b,0,'R');
    	$pdf->Cell(16,4,number_format($row2['cost_sqm2'],0,'.',','),'LT'.$b,0,'R');
    	$pdf->Cell(16,4,number_format($row2['crn'],0,'.',','),'LT'.$b,0,'R');
    	$pdf->Cell(12,4,$row2['remain'],'LT'.$b,0,'R');
    	$pdf->Cell(16.5,4,number_format($row2['market_value'],0,'.',','),'LT'.$b,0,'R');
    	$pdf->Cell(0,4,number_format($row2['liquidation_value'],0,'.',','),'LTR'.$b,1,'R');
    }

    $pdf->SetFont('Arial','B',8);
  	
  	$pdf->Cell(40,4,'TOTAL BUILDINGS',1,0,'L',true);
  	
  	$pdf->Cell(123,4,'',0);
  	
  	$pdf->SetFont('Arial','B',6);

  	$pdf->Cell(14,4,number_format($tot_floor_area,2,'.',','),'LBT',0,'R',true);
  	$pdf->Cell(14,4,number_format($gtot_floor_area,2,'.',','),1,0,'R',true);

  	$pdf->Cell(32,4,'',0);

	$pdf->Cell(16,4,number_format($tot_crn),1,0,'R',true);

	$pdf->Cell(12,4,'',0);

	$pdf->Cell(16.5,4,number_format($tot_mv),1,0,'R',true);

	$pdf->Cell(0,4,number_format($tot_lv),1,1,'R',true);

	$pdf->ln(2);

	
	$pdf->SetFont('Arial','B',8);

	$pdf->Cell(163,4,'TOTAL BUILDINGS',1,0,'L',true);

	$pdf->SetFont('Arial','B',6);

	$pdf->Cell(14,4,number_format($tot_floor_area,2,'.',','),'LBT',0,'R',true);
  	$pdf->Cell(14,4,number_format($gtot_floor_area,2,'.',','),1,0,'R',true);

  	$pdf->Cell(32,4,'','TB',0,'C',true);

	$pdf->Cell(16,4,number_format($tot_crn),'TB',0,'R',true);

	$pdf->Cell(12,4,'','TB',0,'C',true);

	$pdf->Cell(16.5,4,number_format($tot_mv),'TB',0,'R',true);

	$pdf->Cell(0,4,number_format($tot_lv),'TBR',1,'R',true);
?>