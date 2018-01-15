<?php

	$pdf->AddPage();

	$pdf->Image('../../uploads/logo/01.jpg',16,6,12);

	$pdf->ln(8);
	
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(0,5,'','LTR',1);
	
	$pdf->Cell(2,3,'','L');
	$pdf->Cell(89,3,'Spesifikasi Bangunan');
	$pdf->Cell(3,3,'');
	$pdf->Cell(89,3,'Sarana Bangunan');
	$pdf->Cell(0,3,'','R',1);

	$labels_left = array(array('pondasi','Pondasi',0,4),array('dinding','Dinding',5,10),array('lantai','Lantai',11,16),array('dinding_dalam','Dinding dalam',17,19),
						 array('dinding_luar','Dinding luar',20,24),
					   	 array('kusen','Kusen',25,30),array('atap','Atap',31,36),array('pagar','Pagar',37,40));
	$labels_right = array(array('listrik','Listrik',0,1),array('daya_listrik','Daya Listrik',2,7),array('air_bersih','Air Bersih',8,11),array('bak_sampah','Bak Sampah',12,13),
						  array('dikelola_oleh','Dikelola oleh',14,14),array('telepon','Telepon',15,16),array('no_telepon','No. Telepon',17,17));

	$opts_left = array('Mini pile','Beton bertulang','Batu kali','Rolaag bata','Batako', //0-4
					   'Bata ringan aerasi diplester','Batubata diplester','Batako diplester','Bata tidak diplester','Batako tidak diplester','Papan/kayu/triplek', //5-10
					   'Marmer','Granit','Keramik 30 x 30','Tegel','Ubin Teraso','Semen/tajur', //11-16
					   'Cat halus','Cat sedang','Cat kasar', //17-19
					   'Tanpa cat','Cat halus','Cat sedang','Cat kasar','Tanpa cat', //20-24
					   'Alumunium','Pitur','Cat halus','Cat sedang','Cat kasar','Kayu meranti', //25-30
					   'Genteng keramik','Genteng beton','Dak beton','Asbes','Seng','Lainnya', //31-36
					   'Keliling','Depan saja','Samping','Tanpa pagar' //37-40
					   );
	$opts_right = array('Ada','Tidak ada', //0-1
						'900 Watt','1300 Watt','2200 Watt','3300 Watt','4400 Watt','>5500 Watt', //2-7
						'PDAM','Jetpump','Sumur pantek','Sumur gali', //8-11
						'Ada','Tidak ada','bak_sampah_dikelola_oleh','Ada','Tidak ada','no_telepon' //12-13
						);
	$from_db = array(14,17);
	$border_bottom = array(17);

	$s = 0;
	$e = 17;
	$x = 0;
	$y = 0;
	$label = '';

	$pdf->SetFont('Arial','',7);
	for($i=$s;$i<=$e;$i++)
	{
		//left
		$a = $labels_left[$x][2];
		$b = $labels_left[$x][3];
		$label = ($a==$i?$labels_left[$x][1]:'');
		$field = $labels_left[$x][0];

		$t = ($i==$a?'T':'');

		$checked = ($row1[$field]==$opts_left[$i]?'X':'');

		$pdf->Cell(2,3,'','L');
		$pdf->Cell(30,3,$label,'L'.$t);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(5,3,$checked,'LTR',0,'C');
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(54,3,$opts_left[$i],'R'.$t);

		$x += ($i==$b?1:0);

		$pdf->Cell(3,3,'');

		//right
		$a = $labels_right[$y][2];
		$b = $labels_right[$y][3];
		$label = ($a==$i?$labels_right[$y][1]:'');
		$field = $labels_right[$y][0];

		$t = ($i==$a?'T':'');
		$t = (!in_array($i,$from_db)?$t:'');
		$_b = (in_array($i,$border_bottom)?'B':'');

		$pdf->Cell(30,3,$label,'L'.$t.$_b);

		if(!in_array($i,$from_db))
		{
			$checked = ($row1[$field]==$opts_right[$i]?'X':'');
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(5,3,$checked,'LTR'.$_b,0,'C');
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(54,3,$opts_right[$i],'R'.$t.$_b);
		}
		else
		{
			$pdf->Cell(5,3,':','T'.$_b,0,'C');
			$pdf->Cell(54,3,$row1[$opts_right[$i]],'R'.$t.$_b);
		}
		
		$pdf->Cell(0,3,'','R',1);

		$y += ($i==$b?1:0);
	}
	
	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');
	$pdf->Cell(54,3,'Cata sedang','R');		
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');
	$pdf->Cell(54,3,'Cat kasar','R');
	$pdf->Cell(3,3,'');		
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(0,3,'Perijinan Bangunan','R');		
	$pdf->Cell(0,3,'','R',1);

	$pdf->SetFont('Arial','',7);
	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'Dinding Luar'.$row1['dinding_dalam'],'LT');
	$pdf->Cell(5,3,'','LTR');
	$pdf->Cell(54,3,'Tanpa cat','RT');
	$pdf->Cell(3,3,'');		
	$pdf->Cell(30,3,'No. IMB','LT');
	$pdf->Cell(59,3,': '.$row1['no_imb'],'TR');
	$pdf->Cell(0,3,'','R',1);
	
	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');
	$pdf->Cell(54,3,'Cat halus','R');
	$pdf->Cell(3,3,'');		
	$pdf->Cell(30,3,'Tanggal IMB','L');
	$pdf->Cell(59,3,': '.indo_date_format($row1['tanggal_imb'],'longDate'),'R');		
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');
	$pdf->Cell(54,3,'Cat sedang','R');
	$pdf->Cell(3,3,'');		
	$pdf->Cell(30,3,'Arsitek Bangunan','L');
	$pdf->Cell(59,3,': '.$row1['arsitek_bangunan'],'R');
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');
	$pdf->Cell(54,3,'Cat kasar','R');
	$pdf->Cell(3,3,'');		
	$pdf->Cell(30,3,'Tahun Pembuatan','L');
	$pdf->Cell(30,3,': '.$row1['tahun_pembuatan']);
	$pdf->Cell(15,3,'Renovasi');
	$pdf->Cell(14,3,': '.$row1['tahun_renovasi'],'R');
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');
	$pdf->Cell(54,3,'Tanpa cat','R');
	$pdf->Cell(3,3,'');		
	$pdf->Cell(30,3,'Penggunaan','L');
	$pdf->Cell(30,3,': '.$row1['penggunaan']);
	$pdf->Cell(15,3,'Luas IMB');
	$pdf->Cell(14,3,': '.($row['luas_imb']>0?number_format($row['luas_imb'],2,'.',','):'-').' m2','R');
	$pdf->Cell(0,3,'','R',1);

	$pdf->SetFont('Arial','',7);
	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'Kusen','LT');
	$pdf->Cell(5,3,'','LTR');
	$pdf->Cell(54,3,'Alumunium','RT');
	$pdf->Cell(3,3,'');
	$pdf->Cell(8,3,'Ket.','LT');
	$pdf->Cell(81,3,': Copy IMB yang kami terima merupakan IMB induk untuk mendirikan','TR');
	$pdf->Cell(0,3,'','R',1);
	
	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');
	$pdf->Cell(54,3,'Pitur','R');
	$pdf->Cell(3,3,'');
	$pdf->Cell(8,3,'','LB');
	$pdf->Cell(81,3,'  perumahan dan bangunan properti yang dinilai berada di IMB induk','RB');
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');
	$pdf->Cell(54,3,'Cat halus','R');		
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');
	$pdf->Cell(54,3,'Cat sedang','R');
	$pdf->Cell(3,3,'');		
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(30,3,'Luas Bangunan');		
	$pdf->Cell(0,3,'','R',1);

	$sql = "SELECT * FROM luas_bangunan WHERE fk_penugasan='".$id_penugasan_dec."'";
  	$result = $db->Execute($sql);
  	if(!$result)
    	echo $db->ErrorMsg();
  	$building_areas = array();
  	while($row2 = $result->FetchRow())
  	{
    	$building_areas[] = $row2;
  	}
  	$n = count($building_areas);

	$pdf->SetFont('Arial','',7);
	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');
	$pdf->Cell(54,3,'Cat kasar','R');
	$pdf->Cell(3,3,'');		
	$pdf->Cell(33,3,'','LTR');
	
	$mw = 56;
	$w = ($n>0?$mw/$n:$mw);

	$pdf->SetFont('Arial','BU',7);		
	foreach($building_areas as $row3)
	{
		$pdf->Cell($w,3,'Lantai '.NumToRomawi($row3['tingkat_lantai']),'TR',0,'C');
	}
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(54,3,'Kayu meranti','R');
	$pdf->Cell(3,3,'');		
	$pdf->Cell(30,3,'Teras/Balkon','L');
	$pdf->Cell(3,3,':','R',0,'C');

	foreach($building_areas as $row3)
	{
		$pdf->Cell($w,3,number_format($row3['teras'],2,'.',',').'  m2','R',0,'R');
	}
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'Atap','LT');
	$pdf->Cell(5,3,'','LTR');		
	$pdf->Cell(54,3,'Genteng keramik','RT');
	$pdf->Cell(3,3,'');
	$pdf->Cell(30,3,'Ruang Tamu','L');
	$pdf->Cell(3,3,':','R',0,'C');

	foreach($building_areas as $row3)
	{
		$pdf->Cell($w,3,number_format($row3['ruang_tamu'],2,'.',',').'  m2','R',0,'R');
	}
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');		
	$pdf->Cell(54,3,'Genteng beton','R');
	$pdf->Cell(3,3,'');
	$pdf->Cell(30,3,'Ruang Keluarga','L');
	$pdf->Cell(3,3,':','R',0,'C');

	foreach($building_areas as $row3)
	{
		$pdf->Cell($w,3,number_format($row3['ruang_keluarga'],2,'.',',').'  m2','R',0,'R');
	}
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');		
	$pdf->Cell(54,3,'Dak beton','R');
	$pdf->Cell(3,3,'');
	$pdf->Cell(30,3,'Ruang Tidur 1','L');
	$pdf->Cell(3,3,':','R',0,'C');

	foreach($building_areas as $row3)
	{
		$pdf->Cell($w,3,number_format($row3['ruang_tidur1'],2,'.',',').'  m2','R',0,'R');
	}
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');		
	$pdf->Cell(54,3,'Asbes','R');
	$pdf->Cell(3,3,'');
	$pdf->Cell(30,3,'Ruang Tidur 2','L');
	$pdf->Cell(3,3,':','R',0,'C');

	foreach($building_areas as $row3)
	{
		$pdf->Cell($w,3,number_format($row3['ruang_tidur2'],2,'.',',').'  m2','R',0,'R');
	}
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');		
	$pdf->Cell(54,3,'Seng','R');
	$pdf->Cell(3,3,'');
	$pdf->Cell(30,3,'Ruang Dapur','L');
	$pdf->Cell(3,3,':','R',0,'C');

	foreach($building_areas as $row3)
	{
		$pdf->Cell($w,3,number_format($row3['ruang_dapur'],2,'.',',').'  m2','R',0,'R');
	}
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');		
	$pdf->Cell(54,3,'Lainnya','R');
	$pdf->Cell(3,3,'');
	$pdf->Cell(30,3,'Kamar Mandi/WC','L');
	$pdf->Cell(3,3,':','R',0,'C');

	foreach($building_areas as $row3)
	{
		$pdf->Cell($w,3,number_format($row3['kamar_mandi'],2,'.',',').'  m2','R',0,'R');
	}
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'Pagar','LT');
	$pdf->Cell(5,3,'','LTR');		
	$pdf->Cell(54,3,'Keliling','RT');
	$pdf->Cell(3,3,'');
	$pdf->Cell(30,3,'Lain-lain','L');
	$pdf->Cell(3,3,':','R',0,'C');

	foreach($building_areas as $row3)
	{
		$pdf->Cell($w,3,number_format($row3['lain_lain'],2,'.',',').'  m2','R',0,'R');
	}
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');		
	$pdf->Cell(54,3,'Depan saja','R');
	$pdf->Cell(3,3,'');
	$pdf->Cell(30,3,'Jumlah','L');
	$pdf->Cell(3,3,':','R',0,'C');

	$grand_total = 0;

	foreach($building_areas as $row3)
	{
		$grand_total += $row3['total'];
		$pdf->Cell($w,3,number_format($row3['total'],2,'.',',').'  m2','RT',0,'R');
	}
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','L');
	$pdf->Cell(5,3,'','LTR');		
	$pdf->Cell(54,3,'Samping','R');
	$pdf->Cell(3,3,'');
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(30,3,'Total Luas Bangunan','LTB');
	$pdf->Cell(3,3,':','RTB',0,'C');		

	$pdf->Cell($mw,3,number_format($grand_total,2,'.',',').'  m2','RTB',0,'C');
	
	$pdf->Cell(0,3,'','R',1);

	$pdf->SetFont('Arial','',7);
	$pdf->Cell(2,3,'','L');
	$pdf->Cell(30,3,'','LB');
	$pdf->Cell(5,3,'','LTRB');
	$pdf->Cell(54,3,'Tanpa pagar','RB');
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(0,1,'','LR',1);


	if($row1['jenis_perusahaan_penunjuk']=='2')
	{
		// Kesimpulan Nilai Pasar Tanah & Bangunan

		$factors = array();
		
		$total_score1 = 0;
		$prosentase_sm1 = 0;
		$nilai_sm1 = 0;

		$total_score2 = 0;
		$prosentase_sm2 = 0;
		$nilai_sm2 = 0;

		$sql = "SELECT id_nilai_safetymargin,total_score,prosentase,nilai FROM nilai_safetymargin WHERE(fk_penugasan='".$id_penugasan_dec."') AND (jenis_objek='tanah')";
		$result = $db->Execute($sql);
		if(!$result)
		    echo $db->ErrorMsg();

		$n_row3 = $result->RecordCount();	

		if($n_row3>0)
		{
		    $row3 = $result->FetchRow();

		    $total_score1 = $row3['total_score'];
		    $prosentase_sm1 = $row3['prosentase'];
		    $nilai_sm1 = $row3['nilai'];

		    $sql = "SELECT a.faktor,b.deskripsi as param_sm,c.deskripsi as faktor_sm FROM faktor_safetymargin as a 
		            LEFT JOIN ref_param_safetymargin as b ON (a.fk_param_safetymargin=b.id_param_safetymargin)
		            LEFT JOIN ref_faktor_safetymargin as c ON (a.fk_param_safetymargin=c.fk_param_safetymargin) AND (a.faktor=c.nilai_faktor)
		            WHERE(fk_nilai_safetymargin='".$row3['id_nilai_safetymargin']."') ORDER BY a.fk_param_safetymargin ASC";

		    $result = $db->Execute($sql);
		    if(!$result)
		      echo $db->ErrorMsg();
		    $asc = 97;	

		    $x = 0;
		    while($row4 = $result->FetchRow())
	        {        	
	        	$alp = chr($asc);
	        	$land_factors = array($alp.'. '.$row4['param_sm'],$row4['faktor'],$row4['faktor_sm']);
	        	$factors[$x] = array('land'=>$land_factors);
	        	$asc++;
	        	$x++;
	        }
		}


		$sql = "SELECT id_nilai_safetymargin,total_score,prosentase,nilai FROM nilai_safetymargin WHERE(fk_penugasan='".$id_penugasan_dec."') AND (jenis_objek='bangunan')";
		$result = $db->Execute($sql);
		if(!$result)
		    echo $db->ErrorMsg();

		$n_row3 = $result->RecordCount();

		if($n_row3>0)
		{
		    $row3 = $result->FetchRow();

		    $total_score2 = $row3['total_score'];
		    $prosentase_sm2 = $row3['prosentase'];
		    $nilai_sm2 = $row3['nilai'];

		    $sql = "SELECT a.faktor,b.deskripsi as param_sm,c.deskripsi as faktor_sm FROM faktor_safetymargin as a 
		            LEFT JOIN ref_param_safetymargin as b ON (a.fk_param_safetymargin=b.id_param_safetymargin)
		            LEFT JOIN ref_faktor_safetymargin as c ON (a.fk_param_safetymargin=c.fk_param_safetymargin) AND (a.faktor=c.nilai_faktor)
		            WHERE(fk_nilai_safetymargin='".$row3['id_nilai_safetymargin']."') ORDER BY a.fk_param_safetymargin ASC";
		    $result = $db->Execute($sql);
		    if(!$result)
		      echo $db->ErrorMsg();
		    $asc = 97;

		    $x = 0;
		    while($row4 = $result->FetchRow())
	        {        	
	        	$alp = chr($asc);
	        	$building_factors = array($alp.'. '.$row4['param_sm'],$row4['faktor'],$row4['faktor_sm']);

	        	if(isset($factors[$x]))        	
	        		$factors[$x]['building'] = $building_factors;        	
	        	else        	
	        		$factors[$x] = array('land'=>'','building'=>$building_factors);
	        	
	        	$asc++;
	        	$x++;
	        }
		}	

		$pdf->SetFont('Arial','B',7);

		$pdf->Cell(2,3,'','L');
		$pdf->Cell(89,3,'Kesimpulan Nilai Pasar Tanah');
		$pdf->Cell(3,3,'');
		$pdf->Cell(89,3,'Kesimpulan Nilai Pasar Bangunan');
		$pdf->Cell(0,3,'','R',1);

		$pdf->SetFont('Arial','',7);
		$pdf->Cell(2,1,'','L');
		$pdf->Cell(89,1,'','LTR');
		$pdf->Cell(3,1,'');
		$pdf->Cell(89,1,'','LTR');
		$pdf->Cell(0,1,'','R',1);

		$pdf->Cell(2,3,'','L');
		$pdf->Cell(2,3,'','L');
		$pdf->Cell(3,3,'-');
		$pdf->Cell(38,3,'Nilai Pasar');
		$pdf->Cell(15,3,': Rp.');
		$pdf->Cell(31,3,number_format($row1['_nilai_pasar_tanah']),'R',0,'R');
		$pdf->Cell(3,3,'');
		$pdf->Cell(2,3,'','L');
		$pdf->Cell(3,3,'-');
		$pdf->Cell(38,3,'Nilai Biaya Pengganti');
		$pdf->Cell(15,3,': Rp.');
		$pdf->Cell(31,3,number_format($row1['_nilai_biaya_pengganti_bangunan']),'R',0,'R');
		$pdf->Cell(0,3,'','R',1);

		$pdf->Cell(2,3,'','L');
		$pdf->Cell(89,3,'','LR');
		$pdf->Cell(3,3,'');
		$pdf->Cell(2,3,'','L');
		$pdf->Cell(3,3,'-');
		$pdf->Cell(38,3,'Nilai Pasar');
		$pdf->Cell(15,3,': Rp.');
		$pdf->Cell(31,3,number_format($row1['_nilai_pasar_bangunan']),'R',0,'R');
		$pdf->Cell(0,3,'','R',1);

		$i = 0;
		foreach($factors as $arr)
		{
			$lands = $arr['land'];
			$buildings = $arr['building'];		
			
			$pdf->Cell(2,3,'','L');
			
			if(is_array($lands))
			{	
				$t = ($i==0?'T':'');
				$pdf->Cell(5,3,'','L');
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(29,3,$lands[0]);
				$pdf->Cell(7,3,$lands[1],'LBR'.$t,0,'C');
				$pdf->Cell(2,3,'');
				$pdf->SetFont('Arial','',6);
				$pdf->Cell(46,3,$lands[2],'R');
			}
			else
			{
				$pdf->Cell(89,3,'','LR');
			}

			$pdf->Cell(3,3,'');

			if(is_array($buildings))
			{
				$t = ($i==0?'T':'');
				$pdf->Cell(5,3,'','L');
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(29,3,$buildings[0]);
				$pdf->Cell(7,3,$buildings[1],'LBR'.$t,0,'C');
				$pdf->Cell(2,3,'');
				$pdf->SetFont('Arial','',6);
				$pdf->Cell(46,3,$buildings[2],'R');
			}
			else
			{
				$pdf->Cell(89,3,'','LR');
			}
			$pdf->Cell(0,3,'','R',1);

			$i++;
		}

		$pdf->SetFont('arial','B',7);
		$pdf->Cell(2,3,'','L');

		$pdf->Cell(34,3,'Total  ','L',0,'R');
		$pdf->Cell(7,3,$total_score1,'LTR',0,'C');
		$pdf->Cell(48,3,'','R');
		
		$pdf->Cell(3,3,'');
		
		$pdf->Cell(34,3,'Total  ','L',0,'R');
		$pdf->Cell(7,3,$total_score2,'LTR',0,'C');
		$pdf->Cell(48,3,'','R');
		$pdf->Cell(0,3,'','R',1);

		$pdf->Cell(2,3,'','L');

		$pdf->Cell(34,3,'Safety Margin  ','L',0,'R');
		$pdf->Cell(7,3,$prosentase_sm1.'%','LTBR',0,'C');
		$pdf->Cell(48,3,'','R');
		
		$pdf->Cell(3,3,'');
		
		$pdf->Cell(34,3,'Safety Margin  ','L',0,'R');
		$pdf->Cell(7,3,$prosentase_sm2.'%','LTBR',0,'C');
		$pdf->Cell(48,3,'','R');
		$pdf->Cell(0,3,'','R',1);


		$pdf->SetFont('arial','',7);
		
		$pdf->Cell(2,3,'','L');
		$pdf->Cell(2,3,'','L');
		$pdf->Cell(3,3,'-');
		$pdf->Cell(38,3,'Nilai Pasar');
		
		$pdf->SetFont('arial','B',7);

		$pdf->Cell(15,3,': Rp.');
		$pdf->Cell(31,3,number_format($nilai_sm1),'R',0,'R');
		$pdf->Cell(3,3,'');
		$pdf->Cell(2,3,'','L');
		$pdf->Cell(3,3,'-');

		$pdf->SetFont('arial','',7);

		$pdf->Cell(38,3,'Nilai Pasar');

		$pdf->SetFont('arial','B',7);

		$pdf->Cell(15,3,': Rp.');
		$pdf->Cell(31,3,number_format($nilai_sm2),'R',0,'R');
		$pdf->Cell(0,3,'','R',1);

		$pdf->Cell(2,3,'','L');
		$pdf->Cell(2,3,'','L');
		$pdf->Cell(3,3,'');

		$pdf->SetFont('arial','',7);

		$pdf->Cell(84,3,'Setelah Safety Margin','R');	

		$pdf->Cell(3,3,'');

		$pdf->Cell(2,3,'','L');
		$pdf->Cell(3,3,'');
		$pdf->Cell(84,3,'Setelah Safety Margin','R');
		
		$pdf->Cell(0,3,'','R',1);

		$pdf->Cell(2,3,'','L');
		$pdf->Cell(2,3,'','L');
		$pdf->Cell(3,3,'-');
		$pdf->Cell(29,3,'Indikasi Nilai Likuidasi');

		$pdf->SetFont('arial','B',7);

		$pdf->Cell(7,3,$row1['_prosentase_likuidasi_tanah'].'%','LBTR'.$t,0,'C');
		$pdf->Cell(2,3,'');
		$pdf->Cell(15,3,': Rp.');
		$pdf->Cell(31,3,number_format($row1['_nilai_pasar_tanah']),'R',0,'R');
		$pdf->Cell(3,3,'');
		$pdf->Cell(2,3,'','L');

		$pdf->SetFont('arial','',7);

		$pdf->Cell(3,3,'-');
		$pdf->Cell(38,3,'Indikasi Nilai Likuidasi');

		$pdf->SetFont('arial','B',7);
		$pdf->Cell(15,3,': Rp.');
		
		$likuidasi_bangunan = $row1['_nilai_likuidasi_bangunan'];
		
		$pdf->Cell(31,3,number_format($likuidasi_bangunan),'R',0,'R');
		$pdf->Cell(0,3,'','R',1);

		$pdf->Cell(2,1,'','L');
		$pdf->Cell(89,1,'','LBR');
		$pdf->Cell(3,1,'');
		$pdf->Cell(89,1,'','LBR');
		$pdf->Cell(0,1,'','R',1);

		// == end of Kesimpulan Nilai Pasar Tanah & Bangunan
	}

	$pdf->SetFont('arial','',7);

	$pdf->Cell(0,1,'','LR',1);

	// Kesimpulan & Rekomendasi
	$pdf->Cell(2,3,'','L');
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(0,3,'Kesimpulan & Rekomendasi','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(8,3,'','LT');
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(173,3,'Taksasi Nilai;','LRT');
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(8,3,'','L');
	$pdf->Cell(173,3,'Objek Penilaian','LRT');
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(8,3,'','L');
	$pdf->SetFont('Arial','B',7);
	
	$pdf->SetFillColor(229,229,229);
	
	$pdf->Cell(40,3,'OBJEK','LT',0,'C',true); //5
	
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?35:27),3,'NILAI PASAR','LT',0,'C',true);
	
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?31:26),3,'RATA-RATA/M2','LT',0,'C',true);

	if($row1['jenis_perusahaan_penunjuk']=='2')
	{
		$pdf->Cell(27,3,'N. PASAR SETELAH','LT',0,'C',true);
	}

	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?36:27),3,($row1['jenis_perusahaan_penunjuk']=='1'?'INDIKASI NILAI LIKUIDASI':'INDIKASI'),'LT',0,'C',true);
	
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?31:26),3,'RATA-RATA/M2','LTR',0,'C',true);
	$pdf->Cell(0,3,'','R',1);

	if($row1['jenis_perusahaan_penunjuk']=='2')
	{
		$pdf->Cell(2,3,'','L');
		$pdf->Cell(8,3,'','L');
		$pdf->SetFont('Arial','B',7);
		$pdf->SetFillColor(229,229,229);
		
		$pdf->Cell(40,3,'','L',0,'C',true); //5
		
		$pdf->Cell(27,3,'','L',0,'C',true);
		
		$pdf->Cell(26,3,'','L',0,'C',true);
		
		$pdf->Cell(27,3,'SAFETY MARGIN','L',0,'C',true);

		$pdf->Cell(27,3,'NILAI LIKUIDASI','L',0,'C',true);
		
		$pdf->Cell(26,3,'','LR',0,'C',true);
		$pdf->Cell(0,3,'','R',1);						
	}

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(8,3,'A','L',0,'C');
	$pdf->SetFont('Arial','',7);
	

	$pdf->Cell(22,3,'a. Tanah','LT');
	$pdf->Cell(13,3,number_format($row1['luas_tanah'],2,'.',','),'T',0,'R');
	$pdf->Cell(5,3,'m2','T');

	$pdf->Cell(5,3,'Rp.','LT');	
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?30:22),3,number_format($row1['nilai_pasar_tanah'],2,'.',','),'T',0,'R');

	$pdf->Cell(5,3,'Rp.','LT');	
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?26:21),3,number_format($row1['nilai_satuan_tanah'],2,'.',','),'T',0,'R');

	if($row1['jenis_perusahaan_penunjuk']=='2')
	{
		$pdf->Cell(5,3,'Rp.','LT');	
		$pdf->Cell(22,3,number_format($row1['nilai_safetymargin_tanah']),'T',0,'R');
	}

	$pdf->Cell(5,3,'Rp.','LT');
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?31:22),3,number_format($row1['nilai_likuidasi_tanah'],2,'.',','),'T',0,'R');

	$pdf->Cell(5,3,'Rp.','LT');
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?26:21),3,number_format($row1['nilai_satuan_likuidasi_tanah'],2,'.',','),'TR',0,'R');

	$pdf->Cell(0,3,'','R',1);


	$pdf->Cell(2,3,'','L');
	$pdf->Cell(8,3,'','L',0,'C');
	
	$pdf->Cell(22,3,'b. Bangunan','LT');
	$pdf->Cell(13,3,number_format($row1['luas_bangunan'],2,'.',','),'T',0,'R');
	$pdf->Cell(5,3,'m2','T');

	$pdf->Cell(5,3,'Rp.','LT');
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?30:22),3,number_format($row1['nilai_pasar_bangunan'],2,'.',','),'T',0,'R');

	$pdf->Cell(5,3,'Rp.','LT');
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?26:21),3,number_format($row1['nilai_satuan_bangunan'],2,'.',','),'T',0,'R');

	if($row1['jenis_perusahaan_penunjuk']=='2')
	{
		$pdf->Cell(5,3,'Rp.','LT');	
		$pdf->Cell(22,3,number_format($row1['nilai_safetymargin_bangunan']),'T',0,'R');
	}

	$pdf->Cell(5,3,'Rp.','LT');
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?31:22),3,number_format($row1['nilai_likuidasi_bangunan'],2,'.',','),'T',0,'R');

	$pdf->Cell(5,3,'Rp.','LT');
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?26:21),3,number_format($row1['nilai_satuan_likuidasi_bangunan'],2,'.',','),'TR',0,'R');

	$pdf->Cell(0,3,'','R',1);


	if($row1['jenis_perusahaan_penunjuk']=='1')
	{
		$pdf->Cell(2,3,'','L');
		$pdf->Cell(8,3,'','L',0,'C');		

		$pdf->Cell(40,3,'c. Sarana Pelengkap','LT');		

		$pdf->Cell(5,3,'Rp.','LT');
		$pdf->Cell(30,3,number_format($row1['nilai_pasar_sarana_pelengkap'],2,'.',','),'T',0,'R');
		$pdf->Cell(31,3,'','LTR');		
	

		$pdf->Cell(5,3,'Rp.','LT');
		$pdf->Cell(31,3,number_format($row1['nilai_likuidasi_sarana_pelengkap'],2,'.',','),'T',0,'R');

		$pdf->Cell(31,3,'','LTR');
		$pdf->Cell(0,3,'','R',1);
	}

	$pdf->SetFillColor(256,186,130);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(8,3,'','L',0,'C');
	$pdf->Cell(40,3,'Nilai Objek','LT');

	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(5,3,'Rp.','LT',0,'L',true);
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?30:22),3,number_format($row1['nilai_pasar_objek'],2,'.',','),'T',0,'R',true);
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?31:26),3,'','LTR');

	if($row1['jenis_perusahaan_penunjuk']=='2')
	{
		$pdf->Cell(5,3,'Rp.','LT',0,'L',true);
		$pdf->Cell(22,3,number_format($row1['nilai_safetymargin_objek'],2,'.',','),'T',0,'R',true);
	}

	$pdf->Cell(5,3,'Rp.','LT',0,'L',true);
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?31:22),3,number_format($row1['nilai_likuidasi_objek'],2,'.',','),'T',0,'R',true);
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?31:26),3,'','LTR');		
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->Cell(8,3,'','L',0,'C');
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(40,3,'Pembulatan','LT');
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(5,3,'Rp.','LT',0,'L',true);
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?30:22),3,number_format($row1['pembulatan_pasar_objek'],2,'.',','),'T',0,'R',true);
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?31:26),3,'','LTR');
	
	if($row1['jenis_perusahaan_penunjuk']=='2')
	{
		$pdf->Cell(5,3,'Rp.','LT',0,'L',true);
		$pdf->Cell(22,3,number_format($row1['pembulatan_safetymargin_objek'],2,'.',','),'T',0,'R',true);
	}

	$pdf->Cell(5,3,'Rp.','LT',0,'L',true);

	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?31:22),3,number_format($row1['pembulatan_likuidasi_objek'],2,'.',','),'T',0,'R',true);
	$pdf->Cell(($row1['jenis_perusahaan_penunjuk']=='1'?31:26),3,'','LTR');		
	$pdf->Cell(0,3,'','R',1);


	// $pdf->Cell(2,3,'','L');
	// $pdf->Cell(8,3,'','TL',0,'C');
	// $pdf->SetFont('Arial','',7);
	// $pdf->Cell(173,3,'Faktor yang dapat menambah nilai','LTR');		
	// $pdf->Cell(0,3,'','R',1);			

	// $pdf->Cell(2,3,'','L');
	// $pdf->SetFont('Arial','B',7);
	// $pdf->Cell(8,3,'B','L',0,'C');
	// $pdf->SetFont('Arial','',7);
	// $pdf->Cell(20,3,'Tanah','LT');
	// $pdf->Cell(153,3,': '.$row1['faktor_penambah_nilai_tanah'],'RT');
	// $pdf->Cell(0,3,'','R',1);

	// $pdf->Cell(2,3,'','L');		
	// $pdf->Cell(8,3,'','L',0,'C');		
	// $pdf->Cell(20,3,'Bangunan','LT');
	// $pdf->Cell(153,3,': '.$row1['faktor_penambah_nilai_bangunan'],'RT');
	// $pdf->Cell(0,3,'','R',1);


	// $pdf->Cell(2,3,'','L');
	// $pdf->Cell(8,3,'','TL',0,'C');
	// $pdf->SetFont('Arial','',7);
	// $pdf->Cell(173,3,'Faktor yang dapat mengurangi nilai','LTR');		
	// $pdf->Cell(0,3,'','R',1);			

	// $pdf->Cell(2,3,'','L');
	// $pdf->SetFont('Arial','B',7);
	// $pdf->Cell(8,3,'C','L',0,'C');
	// $pdf->SetFont('Arial','',7);
	// $pdf->Cell(20,3,'Tanah','LT');
	// $pdf->Cell(153,3,': '.$row1['faktor_pengurang_nilai_tanah'],'RT');
	// $pdf->Cell(0,3,'','R',1);

	// $pdf->Cell(2,3,'','L');		
	// $pdf->Cell(8,3,'','L',0,'C');		
	// $pdf->Cell(20,3,'Bangunan','LT');
	// $pdf->Cell(153,3,': '.$row1['faktor_pengurang_nilai_bangunan'],'RT');
	// $pdf->Cell(0,3,'','R',1);


	// $pdf->Cell(2,3,'','L');
	// $pdf->SetFont('Arial','B',7);
	// $pdf->Cell(8,3,'D','TL',0,'C');
	// $pdf->SetFont('Arial','',7);
	// $pdf->Cell(173,3,'Faktor yang dapat memenuhi nilai','LTR');		
	// $pdf->Cell(0,3,'','R',1);


	// $pdf->Cell(2,3,'','L');		
	// $pdf->Cell(8,3,'','LB',0,'C');		
	// $pdf->Cell(173,3,$row1['faktor_pemenuh_nilai'],'LTRB');		
	// $pdf->Cell(0,3,'','R',1);


	$pdf->Cell(2,4,'','L');		
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(181,4,'CATATAN & KESIMPULAN','LTR');
	$pdf->Cell(0,4,'','R',1);

	$pdf->SetFont('Arial','',7);
	$pdf->Cell(2,4,'','L');				
	$pdf->Cell(181,4,$row1['kesimpulan'],'LRB');
	$pdf->Cell(0,4,'','R',1);
	
	// end of Kesimpulan & Rekomendasi


	$pdf->Cell(2,4,'','L');
	$pdf->Cell(54,4,'REVIEWER I','LT',0,'C');
	$pdf->Cell(45,4,'REVIEWER II','LT',0,'C');
	$pdf->Cell(82,4,'PENILAI','LTR',0,'C');
	$pdf->Cell(0,4,'','R',1);

	$pdf->Cell(2,18,'','L');
	$pdf->Cell(54,18,'','LT');
	$pdf->Cell(45,18,'','LT');
	$pdf->Cell(82,18,'','LTR');
	$pdf->Cell(0,18,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(54,3,$row1['nama_reviewer1'],'L',0,'C');
	$pdf->Cell(45,3,$row1['nama_reviewer2'],'L',0,'C');
	$pdf->Cell(41,3,$row1['nama_penilai1'],'L',0,'C');
	$pdf->Cell(41,3,$row1['nama_penilai2'],'R',0,'C');
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(54,3,'Ijin Penilai Properti : '.$row1['ijin_reviewer1'],'L',0,'C');
	$pdf->Cell(45,3,'MAPPI : '.$row1['mappi_reviewer2'],'L',0,'C');
	$pdf->Cell(41,3,'MAPPI : '.$row1['mappi_penilai1'],'L',0,'C');
	$pdf->Cell(41,3,'MAPPI : '.$row1['mappi_penilai2'],'R',0,'C');
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,3,'','L');		
	$pdf->Cell(54,3,'MAPPI : '.$row1['mappi_reviewer1'],'L',0,'C');
	$pdf->Cell(45,3,'','L',0,'C');
	$pdf->Cell(82,3,'','LR',0,'C');		
	$pdf->Cell(0,3,'','R',1);

	$pdf->Cell(2,2,'','L');
	$pdf->Cell(54,2,'','LB');
	$pdf->Cell(45,2,'','LB');
	$pdf->Cell(82,2,'','LRB');
	$pdf->Cell(0,2,'','R',1);

	$pdf->Cell(2,2,'','LB');
	$pdf->Cell(0,2,'','RB');
?>