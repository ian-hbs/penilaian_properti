<?php
	session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../config/app_param.php";
    include_once "../../libraries/cipher.php";        
    include_once "../../libraries/fpdf/MC_TABLE.php";
    include_once "../../libraries/global_obj.php";
    include_once "../../helpers/date_helper.php";
    include_once "../../helpers/mix_helper.php";
    
    //instance object
    // $cipher = new cipher(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $global = new global_obj($db);

    // $dec_key = "+^?:^&%*S!3!c!12!31T";
    // $id_penugasan = urldecode($_GET['id']);
    // $id_penugasan_dec = $cipher->decrypt($id_penugasan,$dec_key);

    $id_penugasan_dec = $_GET['id'];

    $sql = "SELECT COUNT(1) n_penugasan FROM penugasan WHERE id_penugasan='".$id_penugasan_dec."'";
    $n_penugasan = $db->GetOne($sql);
    
  	$_BASE_PARAMS = $_APP_PARAM['base'];
    $_SYSTEM_PARAMS = $_APP_PARAM['system_params'];

	class PDF extends MC_TABLE
	{
		// Page footer
		function Footer()
		{
			// Position at 1.5 cm from bottom
			$this->SetY(-15);
			// Arial italic 8
			$this->SetFont('Arial','I',8);
			// Page number
			$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		}

        function subWrite($h, $txt, $link='', $subFontSize=12, $subOffset=0)
        {
            // resize font
            $subFontSizeold = $this->FontSizePt;
            $this->SetFontSize($subFontSize);
            
            // reposition y
            $subOffset = ((($subFontSize - $subFontSizeold) / $this->k) * 0.3) + ($subOffset / $this->k);
            $subX        = $this->x;
            $subY        = $this->y;
            $this->SetXY($subX, $subY - $subOffset);

            //Output text
            $this->Write($h, $txt, $link);

            // restore y position
            $subX        = $this->x;
            $subY        = $this->y;
            $this->SetXY($subX,  $subY + $subOffset);

            // restore font size
            $this->SetFontSize($subFontSizeold);
        }

	}

	if($n_penugasan>0)
	{
        $row = $result->FetchRow();

        $checked = array();
        for($i=3;$i<=17;$i++)
            $checked[] = $i;

        $need_entry = $global->get_need_entry($id_penugasan_dec,$checked);        
        
        if($need_entry[0])
        {
            $sql = "SELECT 
                a.no_laporan,b.nama as nama_debitur,a.fk_perusahaan_penunjuk,c.alamat,c.kelurahan,c.kecamatan,c.kota,c.provinsi,
                a.perusahaan_penilai,a.no_penugasan,a.tgl_penugasan,a.tgl_laporan,a.tgl_survei,d.tgl_pemeriksaan,
                e.kantor_cabang,c.perancang_foto_properti,c.perancang_peta_lokasi,c.skala_foto_properti,c.skala_peta_lokasi,
                e.jenis as jenis_perusahaan_penunjuk,e.perusahaan_penunjuk,e.alamat as alamat_perusahaan_penunjuk,e.kota as kota_perusahaan_penunjuk,e.kode_pos as kode_pos_perusahaan_penunjuk,
                e.no_kerjasama as no_kerjasama_perusahaan_penunjuk,e.tgl_kerjasama as tgl_kerjasama_perusahaan_penunjuk,
                f.nama as reviewer1, f.no_mappi as mappi_reviewer1, f.ijin_penilai as ijin_penilai_reviewer1,
                a.keperluan_penugasan,a.nama_pengorder1,a.jabatan_pengorder1,a.nama_pengorder2,a.jabatan_pengorder2,c.kd_pos,c.jenis_objek,
                d.klien_pendamping_lokasi,d.depan,d.belakang,d.kanan,d.kiri,d.status_objek,d.dihuni_oleh,
                g.*,h.*,i.*,j.*,k.*,l.*,m.*,n.*,
                p.land_area,p.land_title,p.building_area,p.condition,p.frontage,p.wide_road_access,p.land_shape,p.location,p.position,p.time,p.topography,p.security_facility,
                p.total_land_value as _nilai_pasar_tanah,p.liquidation_weight as _prosentase_likuidasi_tanah,
                p.liquidation_value as _nilai_likuidasi_tanah,
                p.indicated_property_value,p.indicated_building_market_value_sqm,p.indicated_building_market_value,p.indicated_land_value,p.indicated_property_value_land,p.indicated_land_value_sqm,
                p.indicated_land_value_weighted,p.indicated_land_value_amount,p.first_rounded,
                (SELECT SUM(crn) FROM perhitungan_bangunan as x WHERE(x.fk_penugasan=a.id_penugasan) AND (type='building')) as _nilai_biaya_pengganti_bangunan,
                (SELECT SUM(market_value) FROM perhitungan_bangunan as x WHERE(x.fk_penugasan=a.id_penugasan) AND (type='building')) as _nilai_pasar_bangunan,
                (SELECT SUM(liquidation_value) FROM perhitungan_bangunan as x WHERE(x.fk_penugasan=a.id_penugasan) AND (type='building')) as _nilai_likuidasi_bangunan,
                (SELECT SUM(cost_sqm1) FROM perhitungan_bangunan as x WHERE(x.fk_penugasan=a.id_penugasan) AND (type='building')) as _harga_bangunan_baru,
                (SELECT remain_year FROM perhitungan_bangunan as x WHERE(x.fk_penugasan=a.id_penugasan) AND (type='building') LIMIT 0,1) as remain_year
                FROM penugasan as a,
                debitur as b, 
                    (SELECT x.fk_penugasan,x.alamat,x.kelurahan,x.kecamatan,x.kota,x.provinsi,x.kd_pos,y.jenis_objek,x.perancang_foto_properti,x.perancang_peta_lokasi,x.skala_foto_properti,x.skala_peta_lokasi 
                    FROM properti as x LEFT JOIN ref_jenis_objek as y ON (x.fk_jenis_objek=y.id_jenis_objek)) as c, 
                pemeriksaan as d, 
                ref_perusahaan_penunjuk as e, 
                ref_penilai as f,
                marketabilitas as g, 
                pertumbuhan_agunan as h, 
                daya_tarik_agunan as i, 
                (SELECT x.*,y.jenis_sertifikat FROM objek_tanah as x LEFT JOIN ref_jenis_sertifikat as y ON (x.fk_jenis_sertifikat=y.id_jenis_sertifikat)) as j,
                spesifikasi_bangunan as k, 
                sarana_bangunan as l, 
                perijinan_bangunan as m, 
                kesimpulan_rekomendasi as n,                
                perhitungan_tanah as p
                WHERE(a.id_penugasan=b.fk_penugasan AND a.id_penugasan=c.fk_penugasan AND a.id_penugasan=d.fk_penugasan 
                AND a.fk_perusahaan_penunjuk=e.id_perusahaan_penunjuk AND a.reviewer1=f.id_penilai AND a.id_penugasan=g.fk_penugasan AND a.id_penugasan=h.fk_penugasan
                AND a.id_penugasan=i.fk_penugasan AND a.id_penugasan=j.fk_penugasan AND a.id_penugasan=k.fk_penugasan AND a.id_penugasan=l.fk_penugasan
                AND a.id_penugasan=m.fk_penugasan AND a.id_penugasan=n.fk_penugasan AND a.id_penugasan=p.fk_penugasan)
                AND (a.id_penugasan='".$id_penugasan_dec."')";                      
            
            $result = $db->Execute($sql);
            if(!$result)
                die($db->ErrorMsg());
            $n_row1 = $result->RecordCount();
            
            if($n_row1>0)
              $row1 = $result->FetchRow();

            $sql = "SELECT * FROM kesimpulan_rekomendasi WHERE(fk_penugasan='".$id_penugasan_dec."')";
            $result = $db->Execute($sql);
            if(!$result)
              die($db->ErroMsg());

            $n_row2 = $result->RecordCount();
            if($n_row2)
              $row2 = $result->FetchRow();

            $sql = "SELECT jenis,file_foto,keterangan FROM peta_lokasi WHERE(fk_penugasan='".$id_penugasan_dec."')";
            $result = $db->Execute($sql);
            if(!$result)
              echo $db->ErrorMsg();
            
            $maps = array();
            while($row3 = $result->FetchRow())
            {
              $maps[$row3['jenis']] = $row3['file_foto'];
            }

    		$pdf = new PDF('P','mm','A4');
    		$pdf->AliasNbPages();    		

            $pdf->SetMargins(25,15,20);
            
    		//cover (1st page)
            include_once "page1_pdf.php";

            //cover letter (2nd page)
            include_once "page2_pdf.php";

            //terms and conditions (3rd page)
            include_once "page3_pdf.php";

            //page3 (4th page)
            include_once "page4_pdf.php";

            //page4 (5th page)
            include_once "page5_pdf.php";

            //land_valuation (6th page)
            include_once "page6_pdf.php";

            //property photo (7th page)
            include_once "page7_pdf.php";

            // comparison2 (8th page)
            include_once "page8_pdf.php";

            //comparison1 (9th page)
            include_once "page9_pdf.php";

            //location_map (10th page)
            include_once "page10_pdf.php";

            //summary1 (11th page)
            include_once "page11_pdf.php";

            //summary2 (12th page)
            include_once "page12_pdf.php"; 

            //building_valuation (13th page)
            include_once "page13_pdf.php"; 

    		$pdf->Output();
        }
        else
        {
            $err_msg = "Data pada form ";
            $s = false;
            foreach($need_entry[1] as $val)
            {
                $err_msg .= ($s?", ":"")."(".$val.")";
                $s = true;
            }
            $err_msg .= ' belum diinput.';
            echo "<br />
            <center>".$err_msg."<br />
            Silahkan lengkapi terlebih dahulu!";
        }

	}
	else
	{
		echo "<br /><center>Data tidak ditemukan!</center>";
	}
?>