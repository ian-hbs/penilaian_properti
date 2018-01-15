<?php
	$pdf->AddPage();
				
	$pdf->Image('../../uploads/logo/01.jpg',16,6,12);
	
	$pdf->SetFont('Arial','B',11);
	$pdf->ln(150);
	$pdf->Cell(24);
	$pdf->Cell(140,8,'','LTR',2);
	$pdf->Cell(140,5,$row1['no_laporan'],'LR',2,'C');
	$pdf->Cell(140,5,'LAPORAN PENILAIAN PROPERTI','LR',2,'C');
	$pdf->Cell(140,8,'','LR',2);
	$pdf->Cell(140,5,'NAMA CALON DEBITUR','LR',2,'C');
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(140,6,strtoupper($row1['nama_debitur']),'LR',2,'C');
	$pdf->Cell(140,8,'','LR',2);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(140,5,'BERLOKASI DI','LR',2,'C');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(140,5,$row1['alamat'],'LR',2,'C');
	$pdf->Cell(140,5,"Kelurahan ".$row1['kelurahan'].", Kecamatan ".$row1['kecamatan'].",",'LR',2,'C');
	$pdf->Cell(140,5,"Kota ".$row1['kota'].", Provinsi ".$row1['provinsi'],'LR',2,'C');
	$pdf->Cell(140,8,'','LBR',2);
	
?>