<?php	
	$pdf->AddPage();

	$pdf->Image('../../uploads/logo/01.jpg',16,6,12);

	$pdf->SetFont('Arial','B',6);
	$pdf->Cell(0,2,'','LTR',1);
	$pdf->Cell(140,3,'','LR');
	$pdf->Cell(43,3,'LAPORAN PENILAIAN','TRB',0,'C');
	$pdf->Cell(0,3,'','R',1);

	
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(2,3,'','L');
	$pdf->Cell(0,3,'I. PETUNJUK PENGISIAN FORMULIR','R',1);

	$pdf->Cell(2,1,'','L');
	$pdf->Cell(181,1,'','LTR');
	$pdf->Cell(0,1,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(181,2.6,'1. Bagian II diisi oleh '.$row1['perusahaan_penunjuk'],'LR');
	$pdf->Cell(0,2.6,'','R',1);
	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(181,2.6,'2. Bagian III diisi oleh Penilai/Appraiser yang ditunjuk '.$row1['perusahaan_penunjuk'],'LR');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,1,'','L');
	$pdf->Cell(181,1,'','LBR');
	$pdf->Cell(0,1,'','R',1);


	$pdf->Cell(0,1,'','LR',1);
	$pdf->Cell(2,3,'','L');
	$pdf->Cell(0,3,'II. PETUNJUK/PENUGASAN PENILAIAN : DIISI OLEH '.$row1['perusahaan_penunjuk'],'R',1);

	$pdf->Cell(2,1,'','L');
	$pdf->Cell(181,1,'','LTR');
	$pdf->Cell(0,1,'','R',1);
	
	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(40,2.6,'PERUSAHAAN JASA PENILAI','L');
	$pdf->Cell(3,2.6,':',0,0,'C');
	
	$pdf->SetFont('Arial','B',6);

	$pdf->Cell(70,2.6,$_SYSTEM_PARAMS['nama_instansi']);
	$pdf->Cell(68,2.6,"Ijin Usaha Jasa Penilain Publik No. ".$_SYSTEM_PARAMS['no_ijin_usaha'],'R',0,'R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->SetFont('Arial','',6);
	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(40,2.6,'ALAMAT','L');
	$pdf->Cell(3,2.6,':',0,0,'C');
	$pdf->SetFont('Arial','B',6);
	$pdf->Cell(138,2.6,$_SYSTEM_PARAMS['alamat_instansi'],'R');			
	$pdf->Cell(0,2.6,'','R',1);		

	$pdf->SetFont('Arial','',6);
	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(43,2.6,'','L');			
	$pdf->SetFont('Arial','B',6);
	$pdf->Cell(138,2.6,$_SYSTEM_PARAMS['tlp_instansi']." Fax. ".$_SYSTEM_PARAMS['fax_instansi'],'R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,1,'','L');
	$pdf->Cell(181,1,'','LBR');
	$pdf->Cell(0,1,'','R',1);

	$pdf->Cell(2,1,'','L');
	$pdf->Cell(181,1,'','LTR');
	$pdf->Cell(0,1,'','R',1);

	$pdf->SetFont('Arial','',6);
	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(181,2.6,'Dengan ini diminta untuk segera melakukan pemeriksaan, penelitian dan penilaian (appraisal) atas obyek kredit sebagai berikut :','LR');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(30,2.6,'Jenis Obyek','L');
	$pdf->Cell(3,2.6,':',0,0,'C');
	$pdf->Cell(80,2.6,$row1['jenis_objek']);
	$pdf->Cell(68,2.6,"Calon Debitur  : ".$row1['nama_debitur'],'R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(30,2.6,'Alamat Obyek','L');
	$pdf->Cell(3,2.6,':',0,0,'C');
	$pdf->Cell(148,2.6,$row1['alamat'].", Kelurahan ".$row1['kelurahan'].", Kecamatan ".$row1['kecamatan'],'R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(33,2.6,'','L');			
	$pdf->Cell(148,2.6,$row1['kota'].", Provinsi ".$row1['provinsi'],'R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,1,'','L');
	$pdf->Cell(181,1,'','LBR');
	$pdf->Cell(0,1,'','R',1);


	$pdf->Cell(0,1,'','LR',1);
	$pdf->Cell(2,3,'','L');
	$pdf->Cell(0,3,'Hasil penilaian agar dilaporkan dalam jangka waktu selambat-lambatnya 5 (lima) hari setelah tanggal pengisian Bagian III formulir ini.','R',1);

	$pdf->Cell(2,1,'','L');
	$pdf->Cell(68,1,'','LTR');
	$pdf->Cell(113,1,'','TR');
	$pdf->Cell(0,1,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(25,2.6,'PENUGASAN','L');
	$pdf->Cell(3,2.6,':',0,0,'C');
	$pdf->SetFont('Arial','B',6);
	$pdf->Cell(40,2.6,'PENILAIAN','R');
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(113,2.6,'PENUNJUKAN ATAS NAMA '.$row1['perusahaan_penunjuk'],'R');
	$pdf->Cell(0,2.6,'','R',1);			

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(25,2.6,'NO.','L');
	$pdf->Cell(3,2.6,':',0,0,'C');
	$pdf->SetFont('Arial','B',6);
	$pdf->Cell(40,2.6,$row1['no_penugasan'],'R');
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(15,2.6,'NAMA');
	$pdf->Cell(25,2.6,': '.$row1['nama_pengorder1']);
	$pdf->Cell(15,2.6,'NAMA');
	$pdf->Cell(58,2.6,': '.$row1['nama_pengorder2'],'R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(25,2.6,'TANGGAL','L');
	$pdf->Cell(3,2.6,':',0,0,'C');
	$pdf->SetFont('Arial','B',6);
	$pdf->Cell(40,2.6,indo_date_format($row1['tgl_penugasan'],'longDate'),'R');
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(15,2.6,'JABATAN');
	$pdf->Cell(25,2.6,': '.$row1['jabatan_pengorder1']);
	$pdf->Cell(15,2.6,'JABATAN');
	$pdf->Cell(58,2.6,': '.$row1['jabatan_pengorder2'],'R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(68,2.6,'UNTUK KEPERLUAN','LR');
	
	$pdf->Cell(113,2.6,'','R');			
	$pdf->Cell(0,2.6,'','R',1);

	$checked1 = ($row1['keperluan_penugasan']=='KPR'?'X':'');
    $checked2 = ($row1['keperluan_penugasan']=='KP RUKO'?'X':'');
    $checked3 = ($row1['keperluan_penugasan']=='AGUNAN'?'X':'');

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(7,2.6,'KPR','L');
	$pdf->Cell(2,2.6,$checked1,1,0,'C');
	$pdf->Cell(15,2.6,'KP RUKO',0,0,'R');
	$pdf->Cell(2,2.6,$checked2,1,0,'C');
	$pdf->Cell(15,2.6,'AGUNAN',0,0,'R');
	$pdf->Cell(2,2.6,$checked3,1,0,'C');
	$pdf->Cell(25,2.6,'','R');
	
	$pdf->Cell(113,2.6,'','R');
	$pdf->Cell(0,2.6,'','R',1);
	
	$pdf->Cell(2,1,'','L');
	$pdf->Cell(68,1,'','LBR');
	$pdf->Cell(113,1,'','BR');
	$pdf->Cell(0,1,'','R',1);
	
	$pdf->Cell(0,1,'','LR',1);
	$pdf->Cell(2,3,'','L');
	$pdf->Cell(0,3,'III. LAPORAN HASIL PENILAIAN, DIISI OLEH PENILAI/APPRAISER YANG DITUNJUK '.$row1['perusahaan_penunjuk'],'R',1);
	
	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(12,2.6,'','LTR');
	$pdf->Cell(169,2.6,'','TR');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(12,2.6,'O','LR',0,'C');
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(25,2.6,'ALAMAT OBJEK');
	$pdf->Cell(3,2.6,':');
	$pdf->Cell(20,2.6,'JL/GG/BLOK');
	$pdf->Cell(3,2.6,':');
	$pdf->Cell(118,2.6,$row1['alamat'],'R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(12,2.6,'','LR',0,'C');
	$pdf->Cell(28,2.6,'');			
	$pdf->Cell(20,2.6,'KELURAHAN');
	$pdf->Cell(3,2.6,':');
	$pdf->Cell(35,2.6,$row1['kelurahan']);
	$pdf->Cell(20,2.6,'KECAMATAN');
	$pdf->Cell(3,2.6,':');
	$pdf->Cell(60,2.6,$row1['kecamatan'],'R');			
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(12,2.6,'B','LR',0,'C');
	$pdf->Cell(28,2.6,'');
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(20,2.6,'KABUPATEN');
	$pdf->Cell(3,2.6,':');
	$pdf->Cell(35,2.6,$row1['kota']);
	$pdf->Cell(20,2.6,'KODE POS');
	$pdf->Cell(3,2.6,':');
	$pdf->Cell(60,2.6,$row1['kd_pos'],'R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(12,2.6,'','LR',0,'C');
	$pdf->Cell(169,2.6,'','R');			
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(12,2.6,'Y','LR',0,'C');
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(25,2.6,'PEMERIKSAAN TGL.');
	$pdf->Cell(3,2.6,':');
	$pdf->Cell(58,2.6,indo_date_format($row1['tgl_pemeriksaan'],'longDate'));
	$pdf->Cell(20,2.6,'YANG DIJUMPAI');
	$pdf->Cell(3,2.6,':');
	$pdf->Cell(60,2.6,$row1['klien_pendamping_lokasi'],'R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');			
	$pdf->Cell(12,2.6,'','LR',0,'C');
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(25,2.6,'BATAS-BATAS');
	$pdf->Cell(3,2.6,':');
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(20,2.6,'DEPAN');
	$pdf->Cell(3,2.6,':');
	$pdf->Cell(35,2.6,$row1['depan']);
	$pdf->Cell(20,2.6,'BELAKANG');
	$pdf->Cell(3,2.6,':');
	$pdf->Cell(60,2.6,$row1['belakang'],'R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');			
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(12,2.6,'E','LR',0,'C');			
	$pdf->Cell(28,2.6,'');
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(20,2.6,'SEBELAH KIRI');
	$pdf->Cell(3,2.6,':');
	$pdf->Cell(35,2.6,$row1['kiri']);
	$pdf->Cell(20,2.6,'SEBELAH KANAN');
	$pdf->Cell(3,2.6,':');
	$pdf->Cell(60,2.6,$row1['kanan'],'R');
	$pdf->Cell(0,2.6,'','R',1);
	
	$checked1 = ($row1['status_objek']=='Dihuni'?'X':'');
    $checked2 = ($row1['status_objek']=='Kosong'?'X':'');

	$pdf->Cell(2,2.6,'','L');			
	$pdf->Cell(12,2.6,'','LR',0,'C');			
	$pdf->Cell(25,2.6,'STATUS OBJEK');
	$pdf->Cell(3,2.6,':');			
	$pdf->Cell(12,2.6,'KOSONG');
	$pdf->Cell(2,2.6,$checked1,1,0,'C');
	$pdf->Cell(12,2.6,'DIHUNI',0,0,'R');
	$pdf->Cell(2,2.6,$checked2,1,0,'C');
	$pdf->Cell(30,2.6,'');
	$pdf->Cell(20,2.6,'OLEH');
	$pdf->Cell(3,2.6,':');
	$pdf->Cell(60,2.6,$row1['dihuni_oleh'],'R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(12,2.6,'K','LR',0,'C');
	$pdf->Cell(169,2.6,'','R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,1,'','L');			
	$pdf->Cell(12,1,'','LBR',0,'C');
	$pdf->Cell(169,1,'','RB');
	$pdf->Cell(0,1,'','R',1);

	$pdf->SetFont('Arial','',6);
	$pdf->Cell(0,1,'','LR',1);
	$pdf->Cell(2,3,'','L');
	$pdf->Cell(0,3,'Marketabilitas','R',1);

	$label1 = "Lokasi Perumahan";
	$label2 = "Kenyamanan";
	$arr_opt1 = array('Dalam kota','Dekat kota','Jauh dari kota');
	$arr_opt2 = array('Jauh dari tempat maksiat','Cukup jauh dari tempat maksiat','Dekat dengan tempat maksiat');

	for($i=0;$i<count($arr_opt1);$i++)
	{
		$t = ($i==0?'T':'');
		$lbl1 = ($i==0?$label1:'');
		$lbl2 = ($i==0?$label2:'');
		$checked1 = ($row1['lokasi_perumahan']==$arr_opt1[$i]?'X':'');
		$checked2 = ($row1['kenyamanan']==$arr_opt2[$i]?'X':'');

		$pdf->Cell(2,2.6,'','L');
		$pdf->Cell(30,2.6,$lbl1,'LR'.$t);
		$pdf->Cell(5,2.6,$checked1,'TR',0,'C');
		$pdf->Cell(55,2.6,$arr_opt1[$i],'R'.$t);
		$pdf->Cell(1,2.6,'');
		$pdf->Cell(40,2.6,$lbl2,'LR'.$t);
		$pdf->Cell(5,2.6,$checked2,'RT',0,'C');
		$pdf->Cell(45,2.6,$arr_opt2[$i],'R'.$t);
		$pdf->Cell(0,2.6,'','R',1);
	}

	$label1 = "Lokasi Agunan";
	$label2 = "Jenis Jalan Lingkungan";

	$arr_opt1 = array('Di hook dan atau taman','Tidak di hook dan atau depan taman','Tusuk sate');
	$arr_opt2 = array('Aspal','Beton balok','Tanah dan sejenisnya');

	for($i=0;$i<count($arr_opt1);$i++)
	{
		$t = ($i==0?'T':'');
		$lbl1 = ($i==0?$label1:'');
		$lbl2 = ($i==0?$label2:'');
		$checked1 = ($row1['lokasi_agunan']==$arr_opt1[$i]?'X':'');
		$checked2 = ($row1['jenis_jalan_lingkungan']==$arr_opt2[$i]?'X':'');

		$pdf->Cell(2,2.6,'','L');
		$pdf->Cell(30,2.6,$lbl1,'LR'.$t);
		$pdf->Cell(5,2.6,$checked1,'TR',0,'C');
		$pdf->Cell(55,2.6,$arr_opt1[$i],'R'.$t);
		$pdf->Cell(1,2.6,'');
		$pdf->Cell(40,2.6,$lbl2,'LR'.$t);
		$pdf->Cell(5,2.6,$checked2,'RT',0,'C');
		$pdf->Cell(45,2.6,$arr_opt2[$i],'R'.$t);
		$pdf->Cell(0,2.6,'','R',1);
	}

	$label1 = "Jarak Fasum Fasos";
	$label2 = "Aksesbilitas Jarak ke Jalan Propinsi";

	$arr_opt1 = array('< 2 Km','2 Km s/d 5 Km','5 Km s/d 7 Km','7 Km s/d 10 Km','> 10 Km');
	$arr_opt2 = array('< 2 Km','2 Km s/d 5 Km','5 Km s/d 7 Km','7 Km s/d 10 Km','> 10 Km');

	for($i=0;$i<count($arr_opt1);$i++)
	{
		$t = ($i==0?'T':'');
		$lbl1 = ($i==0?$label1:'');
		$lbl2 = ($i==0?$label2:'');
		$checked1 = ($row1['jarak_fasum_fasos']==$arr_opt1[$i]?'X':'');
		$checked2 = ($row1['aksesbilitas_jarak_ke_jalan_propinsi']==$arr_opt2[$i]?'X':'');

		$pdf->Cell(2,2.6,'','L');
		$pdf->Cell(30,2.6,$lbl1,'LR'.$t);
		$pdf->Cell(5,2.6,$checked1,'TR',0,'C');
		$pdf->Cell(55,2.6,$arr_opt1[$i],'R'.$t);
		$pdf->Cell(1,2.6,'');
		$pdf->Cell(40,2.6,$lbl2,'LR'.$t);
		$pdf->Cell(5,2.6,$checked2,'RT',0,'C');
		$pdf->Cell(45,2.6,$arr_opt2[$i],'R'.$t);
		$pdf->Cell(0,2.6,'','R',1);
	}

	$label1 = "Fasilitas Jenis Fasum Fasos";
	$label2 = "Resiko Bencana Banjir";

	$arr_opt1 = array('Lengkap (Pasar, Sekolah, RS, Tempat ibadah)','Rata-rata (Psr, Sklh, Puskesmas dan Tempat Ibadah)',
                       'Minimal (Pasar, Sekolah, Klinik dan Tempat Ibadah)');
	$arr_opt2 = array('Tidak ada','Kadang-kadang','Sering');

	for($i=0;$i<count($arr_opt1);$i++)
	{
		$t = ($i==0?'T':'');
		$lbl1 = ($i==0?$label1:'');
		$lbl2 = ($i==0?$label2:'');
		$checked1 = ($row1['fasilitas_jenis_fasum_fasos']==$arr_opt1[$i]?'X':'');
		$checked2 = ($row1['resiko_bencana_banjir']==$arr_opt2[$i]?'X':'');				

		$pdf->Cell(2,2.6,'','L');
		$pdf->Cell(30,2.6,$lbl1,'LR'.$t);
		$pdf->Cell(5,2.6,$checked1,'TR',0,'C');
		$pdf->Cell(55,2.6,$arr_opt1[$i],'R'.$t);
		$pdf->Cell(1,2.6,'');
		$pdf->Cell(40,2.6,$lbl2,'LR'.$t);
		$pdf->Cell(5,2.6,$checked2,'RT',0,'C');
		$pdf->Cell(45,2.6,$arr_opt2[$i],'R'.$t);
		$pdf->Cell(0,2.6,'','R',1);
	}

	$label1 = "Kondisi Jalan ke Kota";
	$label2 = array(array('perumahan','Perumahan'),array('industri','Industri'),array('perkantoran','Perkantoran'));
	$label3 = array(array('perkantoran','Perkantoran'),array('taman','Taman'),array('kosong','Kosong'));

	$arr_opt1 = array('Tidak Macet','Relatif macet','Sering Macet');
	
	for($i=0;$i<count($arr_opt1);$i++)
	{
		$t = ($i==0?'T':'');
		$b = ($i==(count($arr_opt1)-1)?'B':'');
		$lbl1 = ($i==0?$label1:'');				
		$checked1 = ($row1['kondisi_jalan_ke_kota']==$arr_opt1[$i]?'X':'');

		$pdf->Cell(2,2.6,'','L');
		$pdf->Cell(30,2.6,$lbl1,'LR'.$t);
		$pdf->Cell(5,2.6,$checked1,'TR',0,'C');
		$pdf->Cell(55,2.6,$arr_opt1[$i],'R'.$t);
		$pdf->Cell(1,2.6,'');
		$pdf->Cell(40,2.6,$label2[$i][1],'LR'.$t.$b);
		$pdf->Cell(5,2.6,$row1['persen_'.$label2[$i][0]].'%','RT'.$b,0,'C');	
		$pdf->Cell(37,2.6,$label3[$i][1],'R'.$t.$b);
		$pdf->Cell(5,2.6,$row1['persen_'.$label3[$i][0]].'%','TR'.$b,0,'C');
		$pdf->Cell(3,2.6,'','R'.$t.$b);
		$pdf->Cell(0,2.6,'','R',1);
	}				

	$label1 = "Kondisi Jalan Lingkungan";
	$arr_opt1 = array('Jauh dari kota','Sering macet','Tidak macet');			

	for($i=0;$i<count($arr_opt1);$i++)
	{
		$t = ($i==0?'T':'');
		$b = ($i==(count($arr_opt1)-1)?'B':'');
		$lbl1 = ($i==0?$label1:'');				
		$checked1 = ($row1['kondisi_jalan_lingkungan']==$arr_opt1[$i]?'X':'');

		$pdf->Cell(2,3,'','L');
		$pdf->Cell(30,3,$lbl1,'LR'.$t.$b);
		$pdf->Cell(5,3,$checked1,'TR'.$b,0,'C');
		$pdf->Cell(55,3,$arr_opt1[$i],'R'.$t.$b);				
		$pdf->Cell(0,3,'','R',1);
	}

	$pdf->Cell(0,1,'','LR',1);
	$pdf->Cell(2,3,'','L');
	$pdf->Cell(0,3,'Pertumbuhan Agunan','R',1);

	$label1 = "Kecepatan Pertambahan Nilai";
	$label2 = "Kondisi Wilayah Agunan";

	$arr_opt1 = array('Sangat tinggi','Rata-rata','Tidak ada pertumbuhan','Penurunan nilai');
	$arr_opt2 = array('Sedang berkembang','Akan berkembang dalam jangka pendek','Mapan',
                      'Tidak berkembang','Terpencil');
	$j = count($arr_opt2)-1;
	for($i=0;$i<=$j;$i++)
	{
		$t = ($i==0?'T':'');
		
		$lbl1 = ($i==0?$label1:'');
		$lbl2 = ($i==0?$label2:'');
		
		if($i<$j)
			$checked1 = ($row1['kecepatan_pertambahan_nilai']==$arr_opt1[$i]?'X':'');

		$checked2 = ($row1['kondisi_wilayah_agunan']==$arr_opt2[$i]?'X':'');

		$pdf->Cell(2,2.6,'','L');
		if($i<$j)
		{
			$b = ($i==($j-1)?'B':'');
			$pdf->Cell(40,2.6,$lbl1,'LR'.$t.$b);
			$pdf->Cell(5,2.6,$checked1,'TR'.$b,0,'C');
			$pdf->Cell(45,2.6,$arr_opt1[$i],'R'.$t.$b);
			$pdf->Cell(1,2.6,'');
		}
		else
			$pdf->Cell(91,2.6,'');
		
		$b = ($i==$j?'B':'');
		$pdf->Cell(30,2.6,$lbl2,'LR'.$t.$b);
		$pdf->Cell(5,2.6,$checked2,'RT'.$b,0,'C');
		$pdf->Cell(55,2.6,$arr_opt2[$i],'R'.$b.$t);
		$pdf->Cell(0,2.6,'','R',1);
	}						

	$pdf->Cell(0,1,'','LR',1);
	$pdf->Cell(2,3,'','L');
	$pdf->Cell(0,3,'Daya Tarik Agunan','R',1);

	$label1 = "Sarana Listrik";
	$label2 = "Sarana Air";
	$arr_opt1 = array('Ada','Tidak ada');
	$arr_opt2 = array('Air Tanah','PDAM');

	for($i=0;$i<count($arr_opt1);$i++)
	{
		$t = ($i==0?'T':'');
		$lbl1 = ($i==0?$label1:'');
		$lbl2 = ($i==0?$label2:'');
		$checked1 = ($row1['sarana_listrik']==$arr_opt1[$i]?'X':'');
		$checked2 = ($row1['sarana_air']==$arr_opt2[$i]?'X':'');

		$pdf->Cell(2,2.6,'','L');
		$pdf->Cell(35,2.6,$lbl1,'LR'.$t);
		$pdf->Cell(5,2.6,$checked1,'TR',0,'C');
		$pdf->Cell(50,2.6,$arr_opt1[$i],'R'.$t);
		$pdf->Cell(1,2.6,'');
		$pdf->Cell(45,2.6,$lbl2,'LR'.$t);
		$pdf->Cell(5,2.6,$checked2,'RT',0,'C');
		$pdf->Cell(40,2.6,$arr_opt2[$i],'R'.$t);
		$pdf->Cell(0,2.6,'','R',1);
	}

	$label1 = "Sarana Telepon";
	$label2 = "Sarana Taman Lingkungan";
	$arr_opt1 = array('Ada','Tidak ada');
	$arr_opt2 = array('Ada','Tidak ada');

	for($i=0;$i<count($arr_opt1);$i++)
	{
		$t = ($i==0?'T':'');
		$lbl1 = ($i==0?$label1:'');
		$lbl2 = ($i==0?$label2:'');
		$checked1 = ($row1['sarana_telepon']==$arr_opt1[$i]?'X':'');
		$checked2 = ($row1['sarana_taman_lingkungan']==$arr_opt2[$i]?'X':'');

		$pdf->Cell(2,2.6,'','L');
		$pdf->Cell(35,2.6,$lbl1,'LR'.$t);
		$pdf->Cell(5,2.6,$checked1,'TR',0,'C');
		$pdf->Cell(50,2.6,$arr_opt1[$i],'R'.$t);
		$pdf->Cell(1,2.6,'');
		$pdf->Cell(45,2.6,$lbl2,'LR'.$t);
		$pdf->Cell(5,2.6,$checked2,'RT',0,'C');
		$pdf->Cell(40,2.6,$arr_opt2[$i],'R'.$t);
		$pdf->Cell(0,2.6,'','R',1);
	}

	$label1 = "Sarana Untuk Olahraga";
	$label2 = "Sarana Pengelolaan Lingkungan";
	$arr_opt1 = array('Lengkap (Semacam Sport Center/Indoor Sport)','Sederhana (Outdoor bulu tangkis)','Tidak ada');
	$arr_opt2 = array('Keamanan & kebersihan baik','Keamanan & kebersihan minim','Tidak ada');

	for($i=0;$i<count($arr_opt1);$i++)
	{
		$t = ($i==0?'T':'');
		$lbl1 = ($i==0?$label1:'');
		$lbl2 = ($i==0?$label2:'');
		$checked1 = ($row1['sarana_untuk_olahraga']==$arr_opt1[$i]?'X':'');
		$checked2 = ($row1['sarana_pengelolaan_lingkungan']==$arr_opt2[$i]?'X':'');

		$pdf->Cell(2,2.6,'','L');
		$pdf->Cell(35,2.6,$lbl1,'LR'.$t);
		$pdf->Cell(5,2.6,$checked1,'TR',0,'C');
		$pdf->Cell(50,2.6,$arr_opt1[$i],'R'.$t);
		$pdf->Cell(1,2.6,'');
		$pdf->Cell(45,2.6,$lbl2,'LR'.$t);
		$pdf->Cell(5,2.6,$checked2,'RT',0,'C');
		$pdf->Cell(40,2.6,$arr_opt2[$i],'R'.$t);
		$pdf->Cell(0,2.6,'','R',1);
	}			

	$label1 = "Sarana Jln Lingkungan Perumahan";
	$label2 = "Sarana Jumlah Akses Jalan ke Perumahan";
	$arr_opt1 = array('Aspal','Makadam/Pengerasan','Tanah dan sejenisnya');
	$arr_opt2 = array('Hanya 1 akses jalan','Lebih dari 1 akses jalan','Lebih dari 3 akses jalan');

	for($i=0;$i<count($arr_opt1);$i++)
	{
		$t = ($i==0?'T':'');
		$lbl1 = ($i==0?$label1:'');
		$lbl2 = ($i==0?$label2:'');
		$checked1 = ($row1['sarana_jalan_lingkungan_perumahan']==$arr_opt1[$i]?'X':'');
		$checked2 = ($row1['sarana_jumlah_akses_jalan_ke_perumahan']==$arr_opt2[$i]?'X':'');

		$pdf->Cell(2,2.6,'','L');
		$pdf->Cell(35,2.6,$lbl1,'LR'.$t);
		$pdf->Cell(5,2.6,$checked1,'TR',0,'C');
		$pdf->Cell(50,2.6,$arr_opt1[$i],'R'.$t);
		$pdf->Cell(1,2.6,'');
		$pdf->Cell(45,2.6,$lbl2,'LR'.$t);
		$pdf->Cell(5,2.6,$checked2,'RT',0,'C');
		$pdf->Cell(40,2.6,$arr_opt2[$i],'R'.$t);
		$pdf->Cell(0,2.6,'','R',1);
	}

	$label1 = "Sarana Fasos Fasum";
	$label2 = "Bentuk Tanah";
	$arr_opt1 = array('Fasilitas kesehatan (Poliklinik)','Pasar','Rumah ibadah','Sarana hiburan/rekreasi','Sarana pendidikan');
	$arr_opt2 = array('Beraturan','Tidak beraturan','Trapesium','Letter L');
	
	$x_sff = explode('_',$row1['sarana_fasos_fasum']);

	$j = count($arr_opt1)-1;
	for($i=0;$i<=$j;$i++)
	{
		$t = ($i==0?'T':'');
		$lbl1 = ($i==0?$label1:'');
		$lbl2 = ($i==0?$label2:'');
		$checked1 = (in_array($arr_opt1[$i],$x_sff)?'X':'');

		if($i<$j)
			$checked2 = ($row1['bentuk_tanah']==$arr_opt2[$i]?'X':'');
		
		$b = ($i==$j?'B':'');				
		$pdf->Cell(2,2.6,'','L');
		$pdf->Cell(35,2.6,$lbl1,'LR'.$t.$b);
		$pdf->Cell(5,2.6,$checked1,'TR'.$b,0,'C');
		$pdf->Cell(50,2.6,$arr_opt1[$i],'R'.$t.$b);
		
		if($i<$j)
		{
			$b = ($i==($j-1)?'B':'');
			$pdf->Cell(1,2.6,'');
			$pdf->Cell(45,2.6,$lbl2,'LR'.$t.$b);
			$pdf->Cell(5,2.6,$checked2,'RT'.$b,0,'C');
			$pdf->Cell(40,2.6,$arr_opt2[$i],'R'.$t.$b);
		}
		$pdf->Cell(0,2.6,'','R',1);
	}			
	
	$pdf->Cell(0,1,'','LR',1);
	$pdf->Cell(2,3,'','L');
	$pdf->Cell(0,3,'Data Tanah','R',1);

	$pdf->Cell(2,2,'','L');
	$pdf->Cell(181,2,'','LTR');
	$pdf->Cell(0,2,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(55,2.6,'Jenis Sertipikat','L');
	$pdf->Cell(37,2.6,':   '.$row1['jenis_sertifikat']);
	$pdf->Cell(55,2.6,'Luas Tanah');
	$pdf->Cell(34,2.6,':   '.number_format($row1['luas_tanah'],2,'.',',').' m2','R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(55,2.6,'No. Sertifikat','L');
	$pdf->Cell(37,2.6,':   '.$row1['no_sertifikat']);
	$pdf->Cell(55,2.6,'Prosentase Bangunan');
	$pdf->Cell(34,2.6,':   '.number_format($row1['prosentase_bangunan'],0,'.',',').' %','R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(55,2.6,'Tanggal Terbit','L');
	$pdf->Cell(37,2.6,':   '.indo_date_format($row1['tgl_terbit_sertifikat'],'longDate'));
	$pdf->Cell(55,2.6,'Tinggi Halaman Terhadap Jalan');
	$pdf->Cell(34,2.6,':   ±'.$row1['tinggi_halaman_thd_jalan'].' cm','R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(55,2.6,'Jatuh Tempo Sertipikat','L');
	$pdf->Cell(37,2.6,':   '.indo_date_format($row1['tgl_jatuh_tempo_sertifikat'],'longDate'));
	$pdf->Cell(55,2.6,'Tinggi Halaman Terhadap Lantai');
	$pdf->Cell(34,2.6,':   ±'.$row1['tinggi_halaman_thd_lantai'].' cm','R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(55,2.6,'No. GS/SU','L');
	$pdf->Cell(37,2.6,':   '.$row1['no_gs_su']);
	$pdf->Cell(55,2.6,'Keadaan Halaman');
	$pdf->Cell(34,2.6,':   '.$row1['keadaan_halaman'],'R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(55,2.6,'Tanggal GS/SU','L');
	$pdf->Cell(37,2.6,':   '.indo_date_format($row1['tgl_gs_su'],'longDate'));
	$pdf->Cell(89,2.6,'','R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(55,2.6,'Atas Nama','L');
	$pdf->Cell(37,2.6,':   '.$row1['atas_nama']);
	$pdf->Cell(89,2.6,'','R');
	$pdf->Cell(0,2.6,'','R',1);

	$pdf->Cell(2,2.6,'','L');
	$pdf->Cell(55,2.6,'Hubungan dengan Calon Nasabah','LB');
	$pdf->Cell(37,2.6,':   '.$row1['hubungan_dengan_calon_nasabah'],'B');
	$pdf->Cell(89,2.6,'','RB');
	$pdf->Cell(0,2.6,'','R',1);

    
    $w=2;
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->MultiCell($w,10,'','L');
    
    $pdf->SetXY($x+$w,$y);
    
    $w=15;
	$x=$pdf->GetX();
    $y=$pdf->GetY();
	$pdf->MultiCell($w,2.6,'Catatan : 1.',1,'R');
	
	$pdf->SetXY($x+$w,$y);
	
	$w=166;
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->MultiCell($w,2.6,"Berdasarkan surat penugasan No. ".$row1['no_penugasan']." alamat properti berada di ".$row1['alamat']." Kelurahan ".$row1['kelurahan'].", "
					."Kecamatan ".$row1['kecamatan'].", ".$row1['kota'].". Pada saat inspeksi lapangan, alamat lengkap properti berada di ".$row1['alamat']." Kelurahan ".$row1['kelurahan'].", "
    				."Kecamatan ".$row1['kecamatan'].", Kota ".$row1['kota'].", Provinsi ".$row1['provinsi'].".",1);
	
	$pdf->SetXY($x+$w,$y);
	
	$w=0;
    $x=$pdf->GetX();
    $y=$pdf->GetY();
	$pdf->MultiCell($w,10,'','R');
	
	$pdf->Cell(2,2,'','LB');
	$pdf->Cell(183,2,'','B');
	$pdf->Cell(0,2,'','R');
	
	
	
?>