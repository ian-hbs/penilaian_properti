<?php
    
    $pdf->SetMargins(15,15,10);

	$pdf->AddPage();

	$pdf->Image('../../uploads/logo/01.jpg',16,6,12);

	$pdf->ln(8);
	
    $pdf->Cell(0,1,'','LTR',1);

    $pdf->SetFont('Arial','B',8);

    $pdf->Cell(0,3,'PERHITUNGAN NILAI PASAR TANAH','LR',1);
    $pdf->Cell(0,3,'DENGAN METODE PENDEKATAN PERBANDINGAN DATA PASAR','LR',1);

    $pdf->Cell(0,1,'','LR',1);

    $pdf->SetFont('Arial','',7);

    $pdf->Cell(5,3,'','L');
    $pdf->Cell(38,3,'Nama Penilai');
    $pdf->Cell(3,3,':');
    $pdf->Cell(0,3,$row1['nama_penilai1'],'R',1);

    $pdf->Cell(5,3,'','L');
    $pdf->Cell(38,3,'Tanggal');
    $pdf->Cell(3,3,':');
    $pdf->Cell(0,3,indo_date_format($row1['tgl_survei'],'longDate'),'R',1);

    $pdf->Cell(5,3,'','L');
    $pdf->Cell(38,3,'Nama Calon Debitur');
    $pdf->Cell(3,3,':');
    $pdf->Cell(0,3,$row1['nama_debitur'],'R',1);

    $pdf->Cell(5,3,'','L');
    $pdf->Cell(38,3,'Lokasi');
    $pdf->Cell(3,3,':');
    $pdf->Cell(0,3,$row1['alamat'].",",'R',1);

    $pdf->Cell(46,3,'','L');    
    $pdf->Cell(0,3,'Kelurahan '.ucwords(strtolower($row1['kelurahan'])).', Kecamatan '.ucwords(strtolower($row1['kecamatan'])).', '.ucwords(strtolower($row1['kota'])).', ','R',1);
    $pdf->Cell(46,3,'','L');    
    $pdf->Cell(0,3,'Provinsi '.ucwords(strtolower($row1['provinsi'])),'R',1);
    $pdf->Cell(46,3,'','L');    
    $pdf->Cell(0,3,$row1['kd_pos'],'R',1);

    $pdf->Cell(0,0.5,'','LR',1);

    $pdf->SetFont('Arial','B',7);

    $pdf->Cell(47,3.5,'URAIAN','LT',0,'C');

    $pdf->Cell(32,3.5,'OBYEK PENILAIAN','LT',0,'C');

    $tw = 106;

    $comparisons = array();

    $sql = "SELECT a.land_area,a.land_title,a.building_area,a.condition,a.construction,a.frontage,
            a.wide_road_access,a.land_shape,a.location,a.position,a.offering_price,a.transaction_price,a.time,a.discount,
            a.indicated_property_value,a.indicated_building_market_value,a.indicated_building_market_value_sqm,a.indicated_building_market_value,
            a.indicated_land_value,a.indicated_property_value_land,a.indicated_land_value_sqm,a.topography,a.security_facility,a.weighted_percent,a.weighted_amount,
            b.*,c.cost_sqm1 as _harga_bangunan_baru,
            d.*
            FROM perhitungan_tanah_pembanding as a 
            LEFT JOIN (SELECT x.id_objek_pembanding,x.no_urut,x.alamat,x.pemberi_data,x.status,x.no_tlp,x.jarak_dari_properti,
            y.jenis_objek FROM objek_pembanding as x LEFT JOIN ref_jenis_objek as y ON (x.fk_jenis_objek=y.id_jenis_objek)) as b 
            ON (a.fk_objek_pembanding=b.id_objek_pembanding)
            LEFT JOIN (SELECT fk_objek_pembanding,cost_sqm1 FROM perhitungan_bangunan_pembanding) as c ON (a.fk_objek_pembanding=c.fk_objek_pembanding)
            LEFT JOIN (SELECT fk_perhitungan_tanah_pembanding,time as adj_time,land_title as adj_land_title,land_area as adj_land_area,land_use as adj_land_use,land_shape as adj_land_shape,position as adj_position,
            frontage as adj_frontage,location as adj_location,wide_road as adj_wide_road,elevasi as adj_elevasi,development_environment as adj_development_environment,economic_factor as adj_economic_factor,
            security_facility as adj_security_facility FROM adjustment_tanah_pembanding) as d ON (a.id_perhitungan_tanah_pembanding=d.fk_perhitungan_tanah_pembanding)
            WHERE (fk_penugasan='".$id_penugasan_dec."')";
    $result = $db->Execute($sql);
    if(!$result)
        echo $db->ErrorMsg();

    while($row = $result->FetchRow())
    {
        $comparisons[] = $row;
    }

    $w = (count($comparisons)>0?$tw/count($comparisons):$tw);

    $widths = array(7,40,32);

    $x = 0;
    foreach($comparisons as $_row)
    {
        $x++;
        $r = ($x==count($comparisons)?'R':'');
        $ln = ($x==count($comparisons)?1:0);
        $pdf->Cell($w,3.5,'DATA '.$_row['no_urut'],'LT'.$r,$ln,'C');
        $widths[] = $w;
    }

    $pdf->Cell(0,0.5,'','LTR',1);

    $fields = array(
                    array('jenis_objek','Jenis Properti'),
                    array('alamat','Alamat'),
                    array('jarak_dari_properti','Jarak dengan Properti'),
                    array('pemberi_data','Sumber Data'),                    
                    array('no_tlp','Telepon'),
                    array('status','Keterangan'),
                    array('offering_price','Penawaran/Transaksi'),
                    array('time','Waktu Penawaran/Transaksi'),
                    array('discount','Discount'),
                    array('location','Lokasi'),
                    array('land_title','Dokumen Tanah'),                    
                    array('land_area','Luas Tanah (m2)'),
                    array('land_shape','Bentuk Tanah'),
                    array('frontage','Lebar Depan, Frontage (m)'),
                    array('wide_road_access','Lebar Jalan (m)'),
                    array('position','Letak Tanah'),
                    array('condition','Kondisi Tanah'),
                    array('jenis_objek','Peruntukan'),
                    array('topography','Kontur Tanah/Topografi'),
                    array('building_area','Luas Bangunan (m2)'),
                    array('security_facility','Fasilitas (Keamanan)'),
                    array('indicated_property_value','Indikasi Nilai Properti'),
                    array('_harga_bangunan_baru','Harga Bangunan Baru/m2'),
                    array('indicated_building_market_value_sqm','Indikasi Nilai Pasar Bang./m2'),
                    array('indicated_building_market_value','Indikasi Nilai Pasar Bangunan'),
                    array('indicated_land_value','Indikasi Nilai Tanah'),
                    );

    $no=0;
    $_no = '';
    $pdf->SetWidths($widths);

    $s = 0;
    $e = 5;

    $pdf->SetFont('Arial','',7);

    for($i=$s;$i<=$e;$i++)
    {
        $_row1 = $fields[$i];

        $no++;
        $_no = ($no<10?'0'.$no:$no);

        $columns = array(array($_no,'C',3.5),array($_row1[1],'L',3.5));

        if($_row1[0]=='jenis_objek')
            $columns[count($columns)] = array($row1[$_row1[0]],'C',3.5);
        else if($_row1[0]=='alamat')
        {
            $columns[count($columns)] = array($row1[$_row1[0]].', Kelurahan '.ucwords(strtolower($row1['kelurahan'])).', Kecamatan '.ucwords(strtolower($row1['kecamatan'])).', '.ucwords(strtolower($row1['kota'])).', Provinsi '.ucwords(strtolower($row1['provinsi'])),'C',3.5);
        }
        else
            $columns[count($columns)] = array('','L',3.5);


        foreach($comparisons as $_row2)
        {            
            $columns[count($columns)] = array($_row2[$_row1[0]],'C',3.5);
        }
        
        $pdf->Row($columns);
    }

    $s = 6;
    $e = 8;

    for($i=$s;$i<=$e;$i++)
    {
        $_row1 = $fields[$i];

        $no++;
        $_no = ($no<10?'0'.$no:$no);

        $pdf->Cell(7,3.5,$_no,'LB',0,'C');
        $pdf->Cell(40,3.5,$_row1[1],'LB',0);
        $pdf->Cell(32,3.5,'','LB',0);

        $j=0;        
        foreach($comparisons as $_row2)
        {    
            $j++;
            $ln = ($j<count($comparisons)?0:1);
            $r = ($j<count($comparisons)?'':'R');

            if($_row1[0]=='offering_price')
            {                
                $w1 = $w*18/100;
                $w2 = $w-$w1;
                $pdf->Cell($w1,3.5,'Rp.','LB',0);
                $pdf->Cell($w2,3.5,number_format($_row2[$_row1[0]]),'B'.$r,$ln,'R');
            }
            else
            {   
                $val = ($_row1[0]=='time'?indo_date_format($_row2[$_row1[0]],'longDate'):$_row2[$_row1[0]].'%');
                $pdf->Cell($w,3.5,$val,'LB'.$r,$ln,'C');
            }
        }
    }

    $pdf->SetFont('Arial','B',7);

    $pdf->Cell(0,4,'SPESIFIKASI DATA','LBR',1);

    $pdf->SetFont('Arial','',7);

    $s = 9;
    $e = 25;
    $no = 0;
    for($i=$s;$i<=$e;$i++)
    {
        $_row1 = $fields[$i];

        $no++;

        $_no = ($no<10?'0'.$no:$no);      

        $pdf->Cell(7,3.5,$_no,'LB',0,'C');
        $pdf->Cell(40,3.5,$_row1[1],'LB',0);

        if($i<21)
        {
            $pdf->Cell(32,3.5,$row1[$_row1[0]],'LB',0,'C');
        }
        else
        {
            $pdf->Cell(5,3.5,'Rp.','LB',0);
            $pdf->Cell(27,3.5,number_format($row1[$_row1[0]]),'B',0,'R');
        }

        $j=0;

        foreach($comparisons as $_row2)
        {
            $j++;
            $ln = ($j<count($comparisons)?0:1);
            $r = ($j<count($comparisons)?'':'R');

            if($i<21)
            {
                $pdf->Cell($w,3.5,$_row2[$_row1[0]],'LB'.$r,$ln,'C');
            }
            else
            {
                $w1 = $w*18/100;
                $w2 = $w-$w1;
                $pdf->Cell($w1,3.5,'Rp.','LB',0);
                $pdf->Cell($w2,3.5,number_format($_row2[$_row1[0]]),'B'.$r,$ln,'R');
            }
        }

    }

    $pdf->SetFont('Arial','B',7);

    $pdf->Cell(47,3.5,'Indikasi Nilai Properti/Tanah','L',0,'C');  

    // $indikasi_nilai_properti = $row1['pembulatan_pasar_objek']/$row1['land_area'];
    $indikasi_nilai_properti = $row1['indicated_property_value_land'];

    $pdf->Cell(5,3.5,'Rp.','L',0);    
    $pdf->Cell(27,3.5,number_format($indikasi_nilai_properti),0,0,'R');
    
    $j=0;
    foreach($comparisons as $_row2)
    {
        $j++;
        
        $ln = ($j<count($comparisons)?0:1);
        $r = ($j<count($comparisons)?'':'R');

        $w1 = $w*18/100;
        $w2 = $w-$w1;
        $pdf->Cell($w1,3.5,'Rp.','L',0);
        
        // $indikasi_nilai_properti = $_row2['indicated_property_value']/$_row2['land_area'];
        $indikasi_nilai_properti = $_row2['indicated_property_value_land'];

        $pdf->Cell($w2,3.5,number_format($indikasi_nilai_properti),$r,$ln,'R');

    }

    $pdf->Cell(47,3.5,'Indikasi Nilai Tanah/m2','LT',0,'C');  

    // $indikasi_nilai_tanah = $row1['nilai_pasar_tanah']/$row1['land_area'];
    $indikasi_nilai_tanah = $row1['indicated_land_value_sqm'];

    $pdf->Cell(5,3.5,'Rp.','LT',0);    
    $pdf->Cell(27,3.5,number_format($indikasi_nilai_tanah),'T',0,'R');
    
    $j=0;
    foreach($comparisons as $_row2)
    {
        $j++;
        
        $ln = ($j<count($comparisons)?0:1);
        $r = ($j<count($comparisons)?'':'R');

        $w1 = $w*18/100;
        $w2 = $w-$w1;
        $pdf->Cell($w1,3.5,'Rp.','LT',0);
        
        // $indikasi_nilai_tanah = $_row2['nilai_pasar_tanah']/$_row2['land_area'];
        $indikasi_nilai_tanah = $_row2['indicated_land_value_sqm'];

        $pdf->Cell($w2,3.5,number_format($indikasi_nilai_tanah),'T'.$r,$ln,'R');
    }

    $pdf->Cell(0,0.5,'','LTR',1);

    $fields = array(
                    array('adj_location','Lokasi'),
                    array('adj_land_title','Dokumen Tanah'),
                    array('adj_land_area','Luas Tanah'),
                    array('adj_land_shape','Bentuk Tanah'),
                    array('adj_frontage','Lebar Depan, Frontage (m)'),
                    array('adj_wide_road','Lebar & Kondisi Jalan'),
                    array('adj_position','Letak Tanah'),
                    array('adj_elevasi','Elevasi'),
                    array('adj_development_environment','Perkembangan Lingkungan'),
                    array('adj_economic_factor','Faktor Ekenomis'),
                    array('adj_land_use','Peruntukan'),
                    array('adj_security_facility','Fasilitas (Keamanan)'),
                );

    $pdf->Cell(0,4,'PENYESUAIAN','LTR',1);

    $no=0;

    $pdf->SetFont('Arial','',7);
    $tot_adjustments = array();

    foreach($fields as $_row1)
    {
        $no++;

        $_no = ($no<10?'0'.$no:$no);

        $pdf->Cell(7,3.5,$_no,'LT',0,'C');
        $pdf->Cell(72,3.5,$_row1[1],'LT',0);

        $j=0;        
        foreach($comparisons as $_row2)
        {
            $val = $_row2[$_row1[0]];

            $tot_adjustments[$j] = (isset($tot_adjustments[$j])?$tot_adjustments[$j]+$val:$val);

            $j++;
            $ln = ($j<count($comparisons)?0:1);
            $r = ($j<count($comparisons)?'':'R');
                        
            $pdf->Cell($w,3.5,$val.'%','LT'.$r,$ln,'C');
        }
    }

    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(79,3.5,'Total Penyesuaian','LT',0,'C');

    $j=0;
    foreach($comparisons as $_row2)
    {        
        $j++;
        $ln = ($j<count($comparisons)?0:1);
        $r = ($j<count($comparisons)?'':'R');
        $pdf->Cell($w,3.5,$tot_adjustments[$j-1].'%','LT'.$r,$ln,'C');
    }

    $pdf->Cell(79,3.5,'Nilai Indikasi','LT',0,'C');

    $j=0;
    foreach($comparisons as $_row2)
    {        
        $j++;
        $ln = ($j<count($comparisons)?0:1);
        $r = ($j<count($comparisons)?'':'R');

        $w1 = $w*18/100;
        $w2 = $w-$w1;
        $pdf->Cell($w1,3.5,'Rp.','LT',0);

        $a = $_row2['indicated_land_value_sqm'];
        $b = $tot_adjustments[$j-1];
        $c = ($b*$a)/100;

        $nilai_indikasi = $a+$c;
        $pdf->Cell($w2,3.5,number_format($nilai_indikasi),'T'.$r,$ln,'R');
    }

    $pdf->Cell(0,0.5,'','LTR',1);

    $pdf->SetFont('Arial','B',7);

    $pdf->Cell(114.4,4,'PEMBEBANAN','LTR',0);  

    $pdf->SetFont('Arial','',7);

    $pdf->Cell(0,3.5,'Penilai','TR',1,'C');

    $pdf->Cell(40,3.5,'Data Pembanding','LT',0,'C');
    $pdf->Cell(34.4,3.5,'Bobot','LT',0,'C');
    $pdf->Cell(40,3.5,'Indikasi Nilai Tanah/m2','LT',0,'C');

    $pdf->Cell(0,3.5,'','LR',1);

    foreach($comparisons as $_row2)
    {
        $pdf->Cell(40,3.5,'Data #'.$_row2['no_urut'],'LT',0,'C');
        $pdf->Cell(34.4,3.5,$_row2['weighted_percent'].'%','LT',0,'C');
        $pdf->Cell(7,3.5,'Rp','LT',0);
        $pdf->Cell(33,3.5,number_format($_row2['weighted_amount']),'T',0,'R');
        
        $pdf->Cell(0,3.5,'','LR',1);        
    }

    $pdf->SetFont('Arial','B',7);

    $pdf->Cell(40,3.5,'Nilai Indikasi','LT',0,'C');
    $pdf->Cell(34.4,3.5,$row1['indicated_land_value_weighted'].'%','LT',0,'C');
    $pdf->Cell(7,3.5,'Rp','LT',0);
    $pdf->Cell(33,3.5,number_format($row1['indicated_land_value_amount']),'T',0,'R');
    
    $pdf->SetFont('Arial','',7);

    $pdf->Cell(0,3.5,$row1['nama_penilai1'],'LR',1,'C');

    $pdf->SetFont('Arial','B',7);
    
    $pdf->Cell(74.4,3.5,'Dibulatkan','LTB',0,'C');
    $pdf->Cell(7,3.5,'Rp','LTB',0);
    $pdf->Cell(33,3.5,number_format($row1['first_rounded']),'TB',0,'R');

    $pdf->Cell(0,3.5,'','LBR',1,'C');
?>