<?php
	session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../config/app_param.php";
    include_once "../../libraries/cipher.php";
    include_once "../../libraries/global_obj.php";
    include_once "../../helpers/date_helper.php";
    
    //instance object
    $cipher = new cipher(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $global = new global_obj($db);

    $dec_key = "+^?:^&%*S!3!c!12!31T";
    $id_penugasan = urldecode($_GET['id']);
    $id_penugasan_dec = $cipher->decrypt($id_penugasan,$dec_key);    

    $sql = "SELECT COUNT(1) n_penugasan FROM penugasan WHERE id_penugasan='".$id_penugasan_dec."'";
    $n_penugasan = $db->GetOne($sql);    

    $_BASE_PARAMS = $_APP_PARAM['base'];
    $_SYSTEM_PARAMS = $_APP_PARAM['system_params'];
?>
<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title><?php echo $_BASE_PARAMS['sys_name_full'];?> - Hal. 3 Laporan Penilaian</title>
    	<link rel="stylesheet" type="text/css" href="../../assets/dist/css/report-style.css"/>    	
      <style type="text/css">
        .border{border:1px solid #000;}
        table{width:100%;}
        table,ol{margin:0;padding:0;}
        table.main,p,ol{font-size:0.6em;}      
        table td{padding:0;}
        .point{margin:5px 0 0 0!important;}        
      </style>
  	</head>
  	<body>  		
      <?php
      if($n_penugasan>0)
      {        
        $checked = array(4,5,6);
        $need_entry = $global->get_need_entry($id_penugasan_dec,$checked);

        if($need_entry[0])
        {
          $sql = "SELECT a.perusahaan_penilai,a.no_penugasan,a.tgl_penugasan,a.no_laporan,a.tgl_laporan,
              a.tgl_survei,a.keperluan_penugasan,a.nama_pengorder1,a.jabatan_pengorder1,a.nama_pengorder2,a.jabatan_pengorder2,
              b.nama as nama_debitur,
              c.alamat,c.kelurahan,c.kecamatan,c.kota,c.provinsi,c.kd_pos,c.jenis_objek,
              d.tgl_pemeriksaan,d.klien_pendamping_lokasi,d.depan,d.belakang,d.kanan,d.kiri,d.status_objek,d.dihuni_oleh,
              e.perusahaan_penunjuk,
              f.nama as reviewer1, f.no_mappi as mappi_reviewer1, f.ijin_penilai as ijin_penilai_reviewer1,
              g.*,h.*,i.*,j.*
              FROM penugasan as a, debitur as b,
              (SELECT x.fk_penugasan,x.alamat,x.kelurahan,x.kecamatan,x.kota,x.provinsi,x.kd_pos,y.jenis_objek FROM properti as x LEFT JOIN ref_jenis_objek as y ON (x.fk_jenis_objek=y.id_jenis_objek)) as c, 
              pemeriksaan as d, ref_perusahaan_penunjuk as e, ref_penilai as f,
              marketabilitas as g, pertumbuhan_agunan as h, daya_tarik_agunan as i, 
              (SELECT x.*,y.jenis_sertifikat FROM objek_tanah as x LEFT JOIN ref_jenis_sertifikat as y ON (x.fk_jenis_sertifikat=y.id_jenis_sertifikat)) as j
              WHERE(a.id_penugasan=b.fk_penugasan AND a.id_penugasan=c.fk_penugasan AND a.id_penugasan=d.fk_penugasan 
              AND a.fk_perusahaan_penunjuk=e.id_perusahaan_penunjuk AND a.reviewer1=f.id_penilai AND a.id_penugasan=g.fk_penugasan AND a.id_penugasan=h.fk_penugasan
              AND a.id_penugasan=i.fk_penugasan AND a.id_penugasan=j.fk_penugasan)
              AND (a.id_penugasan='".$id_penugasan_dec."')";

          $result = $db->Execute($sql);
          if(!$result)
            die($db->ErrorMsg());
          
          $row = $result->FetchRow();
          
          echo "
          <div class='header'>
            <img src='../../uploads/logo/01.jpg' width='36px'/>
          </div>
          <div style='border:1px solid #000;margin-top:1cm;padding:2px'>
      			<div style='border:1px solid #000;padding:5px'>
              <table class='main'>
                <tr>
                  <td width='70%'>&nbsp;</td>
                  <td align='center' class='border'><b>LAPORAN PENILAIAN</b></td>              
                </tr>            
              </table>
              <p class='point'>I. PETUNJUK PENGISIAN FORMULIR</p>
              <div style='border:2px solid #000;'>
                <ol type='1' style='margin:2px 0 2px 12px;'>
                  <li>Bagian II diisi oleh ".$row['perusahaan_penunjuk']."</li>
                  <li>Bagian III diisi oleh Penilaian/Appraiser yang ditunjuk ".$row['perusahaan_penunjuk']."</li>
                </ol>
              </div>
              <p class='point'>II. PETUNJUK/PENUGASAN PENILAIAN : DIISI OLEH ".$row['perusahaan_penunjuk']."</p>
              <div style='border:2px solid #000;border-bottom:none;padding:2px 5px 2px 5px'>
                <table class='main' cellpadding=0 cellspacing=0>
                  <tr><td width='25%'>PERUSAHAAN JASA PENILAI</td><td width='1%'>:</td><td><b>".$_SYSTEM_PARAMS['nama_instansi']."</b></td>
                  <td align='right'><b>Ijin Usaha Jasa Penilain Publik No. ".$_SYSTEM_PARAMS['no_ijin_usaha']."</td></tr>
                  <tr><td>ALAMAT</td><td>:</td><td colspan='2'><b>".$_SYSTEM_PARAMS['alamat_instansi']."</b><br />
                  Tlp. ".$_SYSTEM_PARAMS['tlp_instansi']." Fax. ".$_SYSTEM_PARAMS['fax_instansi']."</td></tr>
                </table>
              </div>
              <div style='border:2px solid #000;padding:2px 5px 2px 5px;'>
                <p>Dengan ini diminta untuk segera melakukan pemeriksaan, penelitian dan penilaian (appraisal) atas obyek kredit sebagai berikut :</p>
                <table class='main' cellpadding=0 cellspacing=0>
                  <tr>
                    <td width='18%'>Jenis Obyek</td><td width='1%'>:</td><td>".$row['jenis_objek']."</td>
                    <td width='40%'>Calon Debitur&nbsp;&nbsp;&nbsp;: <b>".$row['nama_debitur']."</b></td>
                  </tr>
                  <tr><td>Alamat Obyek</td><td>:</td><td>".$row['alamat'].", Kelurahan ".$row['kelurahan'].", Kecamatan ".$row['kecamatan'].", 
                  ".$row['kota'].", Provinsi ".$row['provinsi']."</td><td></td></tr>
                </table>
              </div>
              <p class='point'>Hasil penilaian agar dilaporkan dalam jangka waktu selambat-lambatnya 5 (lima) hari setelah tanggal pengisian Bagian III formulir ini.</p>
              <table class='main' style='border:2px solid #000;' cellpadding=0 cellspacing=0>
                <tr>
                  <td valign='top' style='border-right:2px solid #000;padding:2px 5px 2px 5px;' width='40%'>
                    <table style='font-size:1.0em!important;'>
                      <tr><td>PENUGASAN</td><td width='1%'>:</td><td><b>PENILAIAN</b></td></tr>
                      <tr><td>No.</td><td>:</td><td><b>".$row['no_penugasan']."</b></td></tr>
                      <tr><td>Tanggal</td><td>:</td><td><b>".indo_date_format($row['tgl_penugasan'],'longDate')."</b></td></tr>
                      <tr>
                        <td colspan='3'>
                        UNTUK KEPERLUAN<br />";
                        $checked1 = ($row['keperluan_penugasan']=='KPR'?'X':'');
                        $checked2 = ($row['keperluan_penugasan']=='KP RUKO'?'X':'');
                        $checked3 = ($row['keperluan_penugasan']=='AGUNAN'?'X':'');
                        echo "
                        <table style='font-size:1.0em!important;'>
                        <tr>
                          <td width='10%'>KPR</td><td style='border:2px solid #000;' align='center' width='4%'>".$checked1."</td>
                          <td align='right' width='20%'>KP RUKO</td><td style='border:2px solid #000;' align='center' width='4%'>".$checked2."</td>
                          <td align='right' width='20%'>AGUNAN</td><td style='border:2px solid #000;' align='center' width='4%'>".$checked3."</td>
                          <td>&nbsp;</td>
                        </tr>
                        </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td valign='top' style='padding:2px 5px 2px 5px'>
                    <label>PENUNJUKAN ATAS NAMA ".$row['perusahaan_penunjuk']."</label>
                    <table style='font-size:1.0em!important;'>
                      <tr>
                        <td width='10%'>NAMA</td><td width='1%'>:</td><td width='30%'>".$row['nama_pengorder1']."</td><td width='10%'>NAMA</td><td width='1%'>:</td><td>".$row['nama_pengorder2']."</td></tr>
                        <td>JABATAN</td><td>:</td><td>".$row['jabatan_pengorder1']."</td><td>JABATAN</td><td>:</td><td>".$row['jabatan_pengorder2']."</td></tr>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <p class='point'>III. LAPORAN HASIL PENILAIAN, DIISI OLEH PENILAI/APPRAISER YANG DITUNJUK ".$row['perusahaan_penunjuk']."</p>
              <table class='main' style='border:2px solid #000' cellpadding=0 cellspacing=0>
                <tr>
                  <td style='border-right:2px solid #000;font-size:1.6em' width='5%' align='center'>
                    <b>O<br />B<br />Y<br />E<br />K</b>
                  </td>                
                  <td style='padding:2px 5px 2px 5px;'>
                    <table width='100%' style='font-size:1.0em!important;'>
                      <tr><td width='15%'>ALAMAT OBYEK</td><td width='1%'>:</td><td width='15%'>JL/GG/BLOK</td><td width='1%'>:</td><td colspan='4'>".$row['alamat']."</td></tr>
                      <tr><td colspan='2'>&nbsp;</td><td>KELURAHAN</td><td>:</td><td width='20%'>".$row['kelurahan']."</td><td width='15%'>KECAMATAN</td><td width='1%'>:</td><td>".$row['kecamatan']."</td></tr>
                      <tr><td colspan='2'>&nbsp;</td><td>KABUPATEN</td><td>:</td><td>".$row['kota']."</td><td>KODE POS</td><td width='1%'>:</td><td>".$row['kd_pos']."</td></tr>
                      <tr><td colspan='8'><br /></td></tr>
                      <tr><td>PEMERIKSAAN TGL.</td><td>:</td><td colspan='3'>".indo_date_format($row['tgl_pemeriksaan'],'longDate')."</td>
                      <td>YANG DIJUMPAI</td><td>:</td><td>".$row['klien_pendamping_lokasi']."</td></tr>
                      <tr><td>BATAS-BATAS</td><td>:</td><td>DEPAN</td><td>:</td><td>".$row['depan']."</td><td>BELAKANG</td><td>:</td><td>".$row['belakang']."</td></tr>
                      <tr><td>&nbsp;</td><td>:</td><td>SEBELAH KIRI</td><td>:</td><td>".$row['kiri']."</td><td>SEBELAH KANAN</td><td>:</td><td>".$row['kanan']."</td></tr>
                      <tr>
                        <td>STATUS OBYEK</td>
                        <td>:</td>
                        <td colspan='3'>";
                        $checked1 = ($row['status_objek']=='Dihuni'?'X':'&nbsp;');
                        $checked2 = ($row['status_objek']=='Kosong'?'X':'&nbsp;');
                        echo "

                        <table cellpadding=0 cellspacing=0 style='font-size:1.0em!important;'>
                        <tr>
                          <td width='15%'>KOSONG</td><td style='border:2px solid #000;' align='center' width='4%'>".$checked1."</td>
                          <td align='right' width='15%'>DIHUNI</td><td style='border:2px solid #000;' align='center' width='4%'>".$checked2."</td>
                          <td>&nbsp;</td>
                        </tr>
                        </table>                    
                        </td>
                        <td>OLEH</td><td>:</td><td>".$row['dihuni_oleh']."</td></tr>
                    </table>
                  </td>
                </tr>
              </table>
              <p class='point'><b>Marketabilitas</b></p>
              <table>
                <tr>
                  <td width='50%' valign='top'>
                    <table class='main' style='border:1px solid #000;' cellpadding=0 cellspacing=0>";
                      $arr_opt = array('Dalam kota','Dekat kota','Jauh dari kota');
                      $label = "Lokasi Perumahan";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style = "border:1px solid #000;border-top:none;";
                        if($i==(count($arr_opt)-1))
                          $style .= "border-bottom:none";

                        $checked = ($row['lokasi_perumahan']==$arr_opt[$i]?'X':'&nbsp;');

                        echo "<tr>
                          <td>".($i==0?$label:'&nbsp;')."</td>
                          <td width='6%' style='".$style."' align='center'><b>".$checked."</b></td>
                          <td colspan='3'>".$arr_opt[$i]."</td>
                        </tr>";
                      } 
                      
                      $arr_opt = array('Di hook dan atau taman','Tidak di hook dan atau depan taman','Tusuk sate');
                      $label = "Lokasi Agunan";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;border-bottom:none";

                        $checked = ($row['lokasi_agunan']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('< 2 Km','2 Km s/d 5 Km','5 Km s/d 7 Km','7 Km s/d 10 Km','> 10 Km');
                      $label = "Jarak Fasum Fasos";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;border-bottom:none";

                        $checked = ($row['jarak_fasum_fasos']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Lengkap (Pasar, Sekolah, RS, Tempat ibadah)','Rata-rata (Pasar, Sekolah, Puskesmas dan Tempat Ibadah)',
                                        'Minimal (Pasar, Sekolah, Klinik dan Tempat Ibadah)');
                      $label = "Fasilitas Jenis Fasum Fasos";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;border-bottom:none";

                        $checked = ($row['fasilitas_jenis_fasum_fasos']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Tidak Macet','Relatif macet','Sering Macet');
                      $label = "Kondisi Jalan ke Kota";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;border-bottom:none";

                        $checked = ($row['kondisi_jalan_ke_kota']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Jauh dari kota','Sering macet','Tidak macet');
                      $label = "Kondisi Jalan Lingkungan";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;border-bottom:none";

                        $checked = ($row['kondisi_jalan_lingkungan']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }
                      
                    echo "</table>
                  </td>
                  <td valign='top'>
                    <table class='main' style='border:1px solid #000;' cellpadding=0 cellspacing=0>";
                      $arr_opt = array('Jauh dari tempat maksiat','Cukup jauh dari tempat maksiat','Dekat dengan tempat maksiat');
                      $label = "Kenyamanan";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style = "border:1px solid #000;border-top:none;";
                        if($i==(count($arr_opt)-1))
                          $style .= "border-bottom:none";

                        $checked = ($row['kenyamanan']==$arr_opt[$i]?'X':'&nbsp;');

                        echo "<tr>
                          <td>".($i==0?$label:'&nbsp;')."</td>
                          <td width='6%' style='".$style."' align='center'><b>".$checked."</b></td>
                          <td colspan='3'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Aspal','Beton balok','Tanah dan sejenisnya');
                      $label = "Jenis Jalan Lingkungan";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;border-bottom:none";

                        $checked = ($row['jenis_jalan_lingkungan']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('< 2 Km','2 Km s/d 5 Km','5 Km s/d 7 Km','7 Km s/d 10 Km','> 10 Km');
                      $label = "Aksesbilitas Jarak ke Jalan Propinsi";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;border-bottom:none";

                        $checked = ($row['aksesbilitas_jarak_ke_jalan_propinsi']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Tidak ada','Kadang-kadang','Sering');
                      $label = "Resiko Bencana Banjir";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;border-bottom:none";

                        $checked = ($row['resiko_bencana_banjir']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }
                  echo "
                        <tr>
                          <td style='border-top:1px solid #000'>Perumahan</td>
                          <td style='border:1px solid #000' align='center'>".$row['persen_perumahan']."%</td>
                          <td style='border-top:1px solid #000'>Pertokoan</td>
                          <td width='6%' style='border:1px solid #000' align='center'>".$row['persen_pertokoan']."%</td>
                          <td style='border-top:1px solid #000'>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>Industri</td>
                          <td style='border:1px solid #000;border-top:none' align='center'>".$row['persen_industri']."%</td>
                          <td>Taman</td>
                          <td style='border:1px solid #000;border-top:none' align='center'>".$row['persen_taman']."%</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>Perkantoran</td>
                          <td style='border:1px solid #000;border-top:none;border-bottom:none' align='center'>".$row['persen_perkantoran']."%</td>
                          <td>Kosong</td>
                          <td style='border:1px solid #000;border-top:none;border-bottom:none' align='center'>".$row['persen_kosong']."%</td>
                          <td>&nbsp;</td>
                        </tr>
                  </table>
                  </td>
                </tr>
              </table>
              <p class='point'><b>Pertumbuhan Agunan</b></p>
              <table>
                <tr>
                  <td width='50%' valign='top'>
                    <table class='main' style='border:1px solid #000;' cellpadding=0 cellspacing=0>";
                      $arr_opt = array('Sangat tinggi','Rata-rata','Tidak ada pertumbuhan','Penurunan nilai');
                      $label = "Kecepatan Pertambahan Nilai";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style = "border:1px solid #000;border-top:none;";
                        if($i==(count($arr_opt)-1))
                          $style .= "border-bottom:none";

                        $checked = ($row['kecepatan_pertambahan_nilai']==$arr_opt[$i]?'X':'&nbsp;');

                        echo "<tr>
                          <td>".($i==0?$label:'&nbsp;')."</td>
                          <td width='6%' style='".$style."' align='center'><b>".$checked."</b></td>
                          <td colspan='3'>".$arr_opt[$i]."</td>
                        </tr>";
                      }
                    echo "
                    </table>
                  </td>
                  <td valign='top'>
                    <table class='main' style='border:1px solid #000;' cellpadding=0 cellspacing=0>";
                      $arr_opt = array('Sedang berkembang','Akan berkembang dalam jangka pendek','Mapan',
                                             'Tidak berkembang','Terpencil');
                      $label = "Kondisi Wilayah Agunan";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style = "border:1px solid #000;border-top:none;";
                        if($i==(count($arr_opt)-1))
                          $style .= "border-bottom:none";

                        $checked = ($row['kondisi_wilayah_agunan']==$arr_opt[$i]?'X':'&nbsp;');

                        echo "<tr>
                          <td>".($i==0?$label:'&nbsp;')."</td>
                          <td width='6%' style='".$style."' align='center'><b>".$checked."</b></td>
                          <td colspan='3'>".$arr_opt[$i]."</td>
                        </tr>";
                      }
                    echo "
                    </table>
                  </td>
                </tr>
              </table>
              <p class='point'><b>Daya Tarik Agunan</b></p>
              <table>
                <tr>
                  <td width='50%' valign='top'>
                    <table class='main' style='border:1px solid #000;' cellpadding=0 cellspacing=0>";
                      $arr_opt = array('Ada','Tidak ada');
                      $label = "Sarana Listrik";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style = "border:1px solid #000;border-top:none;";
                        if($i==(count($arr_opt)-1))
                          $style .= "border-bottom:none";

                        $checked = ($row['sarana_listrik']==$arr_opt[$i]?'X':'&nbsp;');

                        echo "<tr>
                          <td>".($i==0?$label:'&nbsp;')."</td>
                          <td width='6%' style='".$style."' align='center'><b>".$checked."</b></td>
                          <td colspan='3'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Ada','Tidak ada');
                      $label = "Sarana Telepon";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;border-bottom:none";

                        $checked = ($row['sarana_telepon']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Lengkap (Semacam Sport Center/Indoor Sport)','Sederhana (Outdoor bulu tangkis)','Tidak ada');
                      $label = "Sarana untuk Olahraga";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;border-bottom:none";

                        $checked = ($row['sarana_untuk_olahraga']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Aspal','Makadam/Pengerasan','Tanah dan sejenisnya');
                      $label = "Sarana Jalan Lingkungan Perumahan";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;border-bottom:none";

                        $checked = ($row['sarana_jalan_lingkungan_perumahan']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }
                      
                      $arr_opt = array('Fasilitas kesehatan (Poliklinik)','Pasar','Rumah ibadah','Sarana hiburan/rekreasi','Sarana pendidikan');
                      $label = "Sarana Fasos Fasum";
                      $x_sff = explode('_',$row['sarana_fasos_fasum']);

                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;border-bottom:none";

                        $checked = (in_array($arr_opt[$i],$x_sff)?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                    echo "</table>
                  </td>
                  <td valign='top'>
                    <table class='main' style='border:1px solid #000;' cellpadding=0 cellspacing=0>";
                      $arr_opt = array('Air Tanah','PDAM');
                      $label = "Sarana Air";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style = "border:1px solid #000;border-top:none;";
                        if($i==(count($arr_opt)-1))
                          $style .= "border-bottom:none";

                        $checked = ($row['sarana_air']==$arr_opt[$i]?'X':'&nbsp;');

                        echo "<tr>
                          <td>".($i==0?$label:'&nbsp;')."</td>
                          <td width='6%' style='".$style."' align='center'><b>".$checked."</b></td>
                          <td colspan='3'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Ada','Tidak ada');
                      $label = "Sarana Taman Lingkungan";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;border-bottom:none";

                        $checked = ($row['sarana_taman_lingkungan']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Keamanan & kebersihan baik','Keamanan & kebersihan minim','Tidak ada');
                      $label = "Sarana Pengelolaan Lingkungan";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;border-bottom:none";

                        $checked = ($row['sarana_pengelolaan_lingkungan']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Hanya 1 akses jalan','Lebih dari 1 akses jalan','Lebih dari 3 akses jalan');
                      $label = "Sarana Jumlah Akses Jalan ke Perumahan";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;border-bottom:none";

                        $checked = ($row['sarana_jumlah_akses_jalan_ke_perumahan']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Beraturan','Tidak beraturan','Trapesium','Letter L');
                      $label = "Bentuk Tanah";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;border-bottom:none";

                        $checked = ($row['bentuk_tanah']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }
                    echo "
                    </table>
                  </td>
                </tr>
              </table>
              
              <p class='point'><b>Data Tanah</b></p>
              <div style='border:1px solid #000;padding:2px 5px 2px 5px'>
                <table class='main'>
                  <tr>
                    <td>Jenis Sertipikat</td><td width='1%'>:</td>
                    <td>".$row['jenis_sertifikat']."</td>
                    <td>Luas Tanah</td><td width='1%'>:</td>
                    <td>".number_format($row['luas_tanah'],2,'.',',')." m<sup>2</sup></td>
                  </tr>
                  <tr>
                    <td>No. Sertipikat</td><td width='1%'>:</td>
                    <td>".$row['no_sertifikat']."</td>
                    <td>Prosentase Bangunan</td><td width='1%'>:</td>
                    <td>".number_format($row['prosentase_bangunan'],0,'.',',')." %</td>
                  </tr>
                  <tr>  
                    <td>Tanggal Terbit</td><td width='1%'>:</td>
                    <td>".indo_date_format($row['tgl_terbit_sertifikat'],'longDate')."</td>
                    <td>Tinggi Halaman Terhadap Jalan</td><td width='1%'>:</td>
                    <td>±".$row['tinggi_halaman_thd_jalan']." cm</td>
                  </tr>
                  <tr>
                    <td>Jatuh Tempo Sertipikat</td><td width='1%'>:</td>
                    <td>".indo_date_format($row['tgl_jatuh_tempo_sertifikat'],'longDate')."</td>
                    <td>Tinggi Halaman Terhadap Lantai</td><td width='1%'>:</td>
                    <td>±".$row['tinggi_halaman_thd_lantai']." cm</td>
                  </tr>
                  <tr>
                    <td>No. GS/SU</td><td width='1%'>:</td>
                    <td>".$row['no_gs_su']."</td>
                    <td>Keadaan Halaman</td><td width='1%'>:</td>
                    <td>±".$row['keadaan_halaman']." cm</td>
                  </tr>
                  <tr>
                    <td>Tanggal GS/SU</td><td width='1%'>:</td>
                    <td colspan='2'>".indo_date_format($row['tgl_gs_su'],'longDate')."</td>
                  </tr>
                  <tr>
                    <td>Atas Nama</td><td width='1%'>:</td>
                    <td colspan='2'>".$row['atas_nama']."</td>
                  </tr>
                  <tr>
                    <td>Hubungan dengan Calon Nasabah</td><td width='1%'>:</td>
                    <td colspan='2'>".$row['hubungan_dengan_calon_nasabah']."</td>
                  </tr>
                </table>
              </div>

              <table class='main'>
                <tr>
                  <td valign='top'><i>Catatan</i>&nbsp;:</td>
                  <td valign='top' width='1%'><i>1.</i></td>
                  <td valign='top'>
                  <i>
                    Berdasarkan surat penugasan No. ".$row['no_penugasan']." alamat properti berada di ".$row['alamat']." Kelurahan ".$row['kelurahan'].", 
                    Kecamatan ".$row['kecamatan']." ".$row['kota'].". Pada saat inspeksi lapangan, alamat lengkap properti berada di ".$row['alamat']." Kelurahan ".$row['kelurahan'].", 
                    Kecamatan ".$row['kecamatan'].", Kota ".$row['kota'].", Provinsi ".$row['provinsi']."
                  </i>
                  </td>
                </tr>
              </table>
            </div>
      		</div>";
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
        echo "<br />
              <center>           
                Data tidak ditemukan!
              </center>";
      }
      ?>
  	</body>
</html>
