<?php
	session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../config/app_param.php";
    include_once "../../libraries/cipher.php";
    include_once "../../libraries/global_obj.php";
    include_once "../../helpers/date_helper.php";
    include_once "../../helpers/mix_helper.php";

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
    	<title><?php echo $_BASE_PARAMS['sys_name_full'];?> - Hal. 4 Laporan Penilaian</title>
    	<link rel="stylesheet" type="text/css" href="../../assets/dist/css/report-style.css"/>    	
      <style type="text/css">
        .border{border:1px solid #000;}
        table{width:100%;}
        table,ol{margin:0;padding:0;}
        table.main,p,ol{font-size:0.7em;}        
        table td{padding:0;}
        .point{margin:5px 0 0 0!important;}
      </style>
  	</head>
  	<body>  	
      <?php
      if($n_penugasan>0)
      {
        $checked = array(3,7,8,9,10,14,15,17);
        $need_entry = $global->get_need_entry($id_penugasan_dec,$checked);

        if($need_entry[0])
        {
          $sql = "SELECT 
            a.perusahaan_penilai,a.no_penugasan,a.tgl_penugasan,a.no_laporan,a.tgl_laporan,a.tgl_survei,a.keperluan_penugasan,
            a.nama_pengorder1,a.jabatan_pengorder1,a.nama_pengorder2,a.jabatan_pengorder2,
            b.nama as nama_debitur,
            c.alamat,c.kelurahan,c.kecamatan,c.kota,c.provinsi,c.kd_pos,c.jenis_objek,
            d.tgl_pemeriksaan,d.klien_pendamping_lokasi,d.depan,d.belakang,d.kanan,d.kiri,d.status_objek,d.dihuni_oleh,
            e.perusahaan_penunjuk,e.jenis as jenis_perusahaan_penunjuk,
            f.nama as reviewer1, f.no_mappi as mappi_reviewer1, f.ijin_penilai as ijin_penilai_reviewer1,
            g.*,h.*,i.*,j.*,
            k.total_land_value as _nilai_pasar_tanah,k.liquidation_weight as _prosentase_likuidasi_tanah,k.liquidation_value as _nilai_likuidasi_tanah,
            (SELECT SUM(crn) FROM perhitungan_bangunan as x WHERE(x.fk_penugasan=a.id_penugasan) AND (type='building')) as _nilai_biaya_pengganti_bangunan,
            (SELECT SUM(market_value) FROM perhitungan_bangunan as x WHERE(x.fk_penugasan=a.id_penugasan) AND (type='building')) as _nilai_pasar_bangunan,
            (SELECT SUM(liquidation_value) FROM perhitungan_bangunan as x WHERE(x.fk_penugasan=a.id_penugasan) AND (type='building')) as _nilai_likuidasi_bangunan
            FROM penugasan as a, 
            debitur as b, 
            (SELECT x.fk_penugasan,x.alamat,x.kelurahan,x.kecamatan,x.kota,x.provinsi,x.kd_pos,y.jenis_objek FROM properti as x LEFT JOIN ref_jenis_objek as y ON (x.fk_jenis_objek=y.id_jenis_objek)) as c, 
            pemeriksaan as d, 
            ref_perusahaan_penunjuk as e, 
            ref_penilai as f,
            spesifikasi_bangunan as g, 
            sarana_bangunan as h, 
            perijinan_bangunan as i, 
            kesimpulan_rekomendasi as j,
            perhitungan_tanah as k
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
              <table>
                <tr>
                  <td width='50%' valign='top' style='padding-right:5px'>
                    <p class='point'><b>Spesifikasi Bangunan</b></p>
                    <table class='main' style='border:1px solid #000;' cellpadding=0 cellspacing=0>";
                      $arr_opt = array('Mini pile','Beton bertulang','Batu kali','Rolaag Bata','Batako');
                      $label = "Pondasi";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style = "border:1px solid #000;border-top:none;";
                        if($i==(count($arr_opt)-1))
                          $style .= "border-bottom:none";

                        $checked = ($row['pondasi']==$arr_opt[$i]?'X':'&nbsp;');

                        echo "<tr>
                          <td>".($i==0?$label:'&nbsp;')."</td>
                          <td width='6%' style='".$style."' align='center'><b>".$checked."</b></td>
                          <td colspan='3'>".$arr_opt[$i]."</td>
                        </tr>";
                      } 
                      
                      $arr_opt = array('Bata ringan aerasi diplester','Batubata diplester','Batako diplester','Bata tidak diplester','Batako tidak diplester','Papan/kayu/triplek');
                      $label = "Dinding";
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

                        $checked = ($row['dinding']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Marmer','Granit','Keramik 30 x 30','Tegel','Ubin Teraso','Semen/tajur');
                      $label = "Lantai";
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

                        $checked = ($row['lantai']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Cat halus','Cat sedang','Cat kasar');
                      $label = "Dinding Dalam";
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

                        $checked = ($row['dinding_dalam']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Tanpa cat','Cat halus','Cat sedang','Cat kasar','Tanpa cat');
                      $label = "Dinding Luar";
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

                        $checked = ($row['dinding_luar']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Alumunium','Pitur','Cat halus','Cat sedang','Cat kasar','Kayu meranti');
                      $label = "Kusen";
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

                        $checked = ($row['kusen']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }
                      
                      $arr_opt = array('Genteng keramik','Genteng beton','Dak beton','Asbes','Seng','Lainnya');
                      $label = "Atap";
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

                        $checked = ($row['atap']==$arr_opt[$i]?'X':'&nbsp;');

                        echo "<tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td width='6%' style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Keliling','Depan saja','Samping','Tanpa pagar');
                      $label = "Pagar";
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

                        $checked = ($row['pagar']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                    echo "</table>
                  </td>
                  <td valign='top' style='padding-left:5px'>
                    <p class='point'><b>Sarana Bangunan</b></p>
                    <table class='main' style='border:1px solid #000;' cellpadding=0 cellspacing=0>";
                      $arr_opt = array('Ada','Tidak ada');
                      $label = "Listrik";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style = "border:1px solid #000;border-top:none;";
                        if($i==(count($arr_opt)-1))
                          $style .= "border-bottom:none";

                        $checked = ($row['listrik']==$arr_opt[$i]?'X':'&nbsp;');

                        echo "<tr>
                          <td>".($i==0?$label:'&nbsp;')."</td>
                          <td width='6%' style='".$style."' align='center'><b>".$checked."</b></td>
                          <td colspan='3'>".$arr_opt[$i]."</td>
                        </tr>";
                      } 
                      
                      $arr_opt = array('900 Watt','1300 Watt','2200 Watt','3300 Watt','4400 Watt','>5500 Watt');
                      $label = "Daya Listrik";
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

                        $checked = ($row['daya_listrik']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }
                      
                      $arr_opt = array('PDAM','Jetpump','Sumur pantek','Sumur gali');
                      $label = "Air Bersih";
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

                        $checked = ($row['air_bersih']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      $arr_opt = array('Ada','Tidak ada');
                      $label = "Bak Sampah";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;";

                        $checked = ($row['bak_sampah']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      echo "
                        <tr>
                          <td>Dikelola oleh</td><td align='center'>:</td><td>".$row['bak_sampah_dikelola_oleh']."</td>
                        </tr>";

                      $arr_opt = array('Ada','Tidak ada');
                      $label = "Telepon";
                      for($i=0;$i<count($arr_opt);$i++)
                      {
                        $style1="";
                        if($i==0)
                          $style1 = "border-top:1px solid #000";

                        $style2 = "border:1px solid #000;";                    
                        if($i>0 and $i<(count($arr_opt)-1))
                          $style2 .= "border-top:none";
                        else if($i>0 and $i==(count($arr_opt)-1))
                          $style2 .= "border-top:none;";

                        $checked = ($row['telepon']==$arr_opt[$i]?'X':'&nbsp;');
                        echo "
                        <tr>
                          <td style='".$style1."'>".($i==0?$label:'&nbsp;')."</td>
                          <td style='".$style2."' align='center'><b>".$checked."</b></td>
                          <td colspan='3' style='".$style1."'>".$arr_opt[$i]."</td>
                        </tr>";
                      }

                      echo "
                        <tr>
                          <td>No. Telepon</td><td align='center'>:</td><td>".$row['no_telepon']."</td>
                        </tr>";
                    echo "
                    </table>
                    <p class='point'><b>Perijinan Bangunan</b></p>
                    <div style='border:1px solid #000;padding:2px'>
                      <table class='main' cellpadding=0 cellspacing=0>
                        <tr>
                          <td>No. IMB</td><td width='1%'>:</td>
                          <td colspan='3'>".$row['no_imb']."</td>
                        </tr>
                        <tr>
                          <td>Tanggal IMB</td><td>:</td>
                          <td colspan='3'>".indo_date_format($row['tanggal_imb'],'longDate')."</td>
                        </tr>
                        <tr>
                          <td>Arsitek Bangunan</td><td>:</td>
                          <td colspan='3'>".$row['arsitek_bangunan']."</td>
                        </tr>
                        <tr>
                          <td>Tahun Pembuatan</td><td>:</td>
                          <td>".$row['tahun_pembuatan']."</td>
                          <td>Renovasi&nbsp;&nbsp;:</td><td>".$row['tahun_renovasi']."</td>
                        </tr>
                        <tr>
                          <td>Penggunaan</td><td>:</td>
                          <td>".$row['penggunaan']."</td>
                          <td>Luas IMB&nbsp;&nbsp;:</td><td>".($row['luas_imb']>0?number_format($row['luas_imb'],2,'.',','):'-')." m<sup>2</sup></td>
                        </tr>                    
                      </table>
                    </div>
                    <div style='border:1px solid #000;border-top:none;padding:2px'>
                      <table class='main' cellpadding=0 cellspacing=0>
                          <tr>
                            <td>Ket.&nbsp;&nbsp;:</td>
                            <td><i>Copy IMB yang kami terima merupakan IMB induk untuk mendirikan
                            perumahan dan bangunan properti yang dinilai berada di IMB induk.</td>
                          </tr>
                        </table>
                    </div>
                    <p class='point'><b>Luas bangunan</b></p>";
                    $sql = "SELECT * FROM luas_bangunan WHERE fk_penugasan='".$id_penugasan_dec."'";
                    $result = $db->Execute($sql);
                    if(!$result)
                      echo $db->ErrorMsg();
                    $building_areas = array();
                    while($row2 = $result->FetchRow())
                    {
                      $building_areas[] = $row2;
                    }

                    echo "<table class='main' cellpadding=0 cellspacing=0 style='border:1px solid #000'>
                      <tr>
                        <td colspan='2' style='border-right:1px solid #000;'>&nbsp;</td>";
                        $i=0;
                        foreach($building_areas as $row2)
                        {
                          $i++;
                          $br = "border-right:".($i<count($building_areas)?"1px solid #000":"none");
                          echo "<td colspan='2' style='".$br."' align='center'><b><u>Lantai ".NumToRomawi($row2['tingkat_lantai'])."</u></b></td>";
                        }
                      echo "</tr>";

                      $rooms = array('teras'=>'Teras/Balkon','ruang_tamu'=>'Ruang Tamu','ruang_keluarga'=>'Ruang Keluarga','ruang_tidur1'=>'Ruang Tidur 1',
                                        'ruang_tidur2'=>'Ruang Tidur 2','ruang_dapur'=>'Ruang Dapur','kamar_mandi'=>'Kamar Mandi/WC','lain_lain'=>'Lain-lain');
                      foreach($rooms as $key1=>$val1)
                      {
                        echo "<tr>
                          <td style='padding-left:2px'>".$val1."</td><td width='3%' style='border-right:1px solid #000;' align='center'>:</td>";
                          $i=0;
                          foreach($building_areas as $key2=>$val2)
                          {
                            $i++;
                            $br = "border-right:".($i<count($building_areas)?"1px solid #000":"none");
                            echo "<td align='right'>".number_format($val2[$key1],2,'.',',')."</td><td width='5%' style='".$br."' align='center'>m<sup>2</sup></td>";
                          }
                        echo "</tr>";
                      }
                    echo "
                      <tr>
                        <td style='padding-left:2px'>Jumlah</td><td style='border-right:1px solid #000;' align='center'>:</td>";
                        $grand_total = 0;
                        $i=0;
                        foreach($building_areas as $key=>$val)
                        {
                          $i++;
                          $br = "border-right:".($i<count($building_areas)?"1px solid #000":"none");
                          echo "<td align='right' style='border-top:1px solid #000;'>".number_format($val['total'],2,'.',',')."</td><td width='5%' style='".$br.";border-top:1px solid #000;' align='center'>m<sup>2</sup></td>";
                          $grand_total += $val['total'];
                        }
                      echo "</tr>
                      <tr>";
                        $colspan = count($building_areas)*2;
                        echo "
                        <td style='border-top:1px solid #000'><b>Total Luas Bangunan</b></td>
                        <td style='border-top:1px solid #000' align='center'><b>:</b></td>
                        <td style='border-top:1px solid #000' colspan='".$colspan."' align='center'><b>".number_format($grand_total,2,'.',',')." m<sup>2</sup></b></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>";

              if($row['jenis_perusahaan_penunjuk']=='2')
              {

                echo "
                <table>
                  <tr>
                    <td width='50%' valign='top' style='padding-right:5px'>                      
                      <p class='point'><b>Kesimpulan Nilai Pasar Tanah</b></p>
                      <div style='border:1px solid #000;padding:5px;'>
                        <table style='font-size:0.7em!important;'>
                          <tr>
                            <td width='3%' align='center'>-</td>
                            <td colspan='3'>Nilai Pasar</td>
                            <td width='3%'>&nbsp;</td>
                            <td>:</td>
                            <td><b>Rp.</b></td>
                            <td align='right'><b>".number_format($row['_nilai_pasar_tanah'])."</b></td>
                          </tr>
                          <tr>
                            <td colspan='8'>&nbsp;</td>
                          </tr>";

                          $sql = "SELECT id_nilai_safetymargin,total_score,prosentase,nilai FROM nilai_safetymargin WHERE(fk_penugasan='".$id_penugasan_dec."') AND (jenis_objek='tanah')";
                          $result = $db->Execute($sql);
                          if(!$result)
                            echo $db->ErrorMsg();

                          $n_row2 = $result->RecordCount();

                          if($n_row2>0)
                          {
                            $row2 = $result->FetchRow();
                            $sql = "SELECT a.faktor,b.deskripsi as param_sm,c.deskripsi as faktor_sm FROM faktor_safetymargin as a 
                                    LEFT JOIN ref_param_safetymargin as b ON (a.fk_param_safetymargin=b.id_param_safetymargin)
                                    LEFT JOIN ref_faktor_safetymargin as c ON (a.fk_param_safetymargin=c.fk_param_safetymargin) AND (a.faktor=c.nilai_faktor)
                                    WHERE(fk_nilai_safetymargin='".$row2['id_nilai_safetymargin']."') ORDER BY a.fk_param_safetymargin ASC";
                            $result = $db->Execute($sql);
                            if(!$result)
                              echo $db->ErrorMsg();
                            $asc = 97;
                            while($row3 = $result->FetchRow())
                            {
                              $alp = chr($asc);
                              echo "
                              <tr>
                                <td></td>
                                <td colspan='2'>".$alp.". ".$row3['param_sm']."</td>
                                
                                <td align='center' style='border:1px solid #000'>".$row3['faktor']."</td>
                                <td></td>
                                <td colspan='3'>".$row3['faktor_sm']."</td>                                 
                              </tr>";
                              $asc++;
                            }
                            echo "
                              <tr>
                                <td colspan='8'>&nbsp;</td>
                              </tr>";

                            echo "
                            <tr>                              
                              <td colspan='3' align='right'><b>Total&nbsp;&nbsp;</b></td>
                              <td align='center' style='border:1px solid #000'><b>".$row2['total_score']."</b></td>
                              <td colspan='4'></td>
                            </tr>
                            <tr>                              
                              <td colspan='3' align='right'><b>Safety Margin&nbsp;&nbsp;</b></td>
                              <td align='center' style='border:1px solid #000'><b>".$row2['prosentase']."%</b></td>
                              <td colspan='4'></td>
                            </tr>
                            <tr>
                              <td align='center'>-</td>
                              <td colspan='4'>Nilai Pasar Setelah Safety Margin</td>
                              <td align='center'>:</td>
                              <td><b>Rp.</b></td>
                              <td align='right'><b>".number_format($row2['nilai'])."</b></td>
                            </tr>
                            <tr>
                              <td align='center'>-</td>
                              <td colspan='2'>Indikasi Nilai Likuidasi</td>
                              <td align='center' style='border:1px solid #000'><b>".$row['_prosentase_likuidasi_tanah']."%</b></td>
                              <td>&nbsp;</td>
                              <td align='center'>:</td>
                              <td><b>Rp.</b></td>";
                              $likuidasi_tanah = ($row['_nilai_pasar_tanah'] * $row['_prosentase_likuidasi_tanah'])/100;
                              echo "
                              <td align='right'><b>".number_format($likuidasi_tanah)."</b></td>
                            </tr>";
                          }
                        echo "</table>
                      </div>
                    </td>
                    <td>
                      <p class='point'><b>Kesimpulan Nilai Pasar Bangunan</b></p>
                      <div style='border:1px solid #000;padding:5px;'>
                        <table style='font-size:0.7em!important;'>
                          <tr>
                            <td width='3%' align='center'>-</td>
                            <td colspan='3'>Nilai Biaya Pengganti</td>
                            <td width='3%'>&nbsp;</td>
                            <td>:</td>
                            <td><b>Rp.</b></td>
                            <td align='right'><b>".number_format($row['_nilai_biaya_pengganti_bangunan'])."</b></td>
                          </tr>
                          <tr>
                            <td align='center'>-</td>
                            <td colspan='3'>Nilai Pasar</td>
                            <td>&nbsp;</td>
                            <td>:</td>
                            <td><b>Rp.</b></td>
                            <td align='right'><b>".number_format($row['_nilai_pasar_bangunan'])."</b></td>
                          </tr>";

                          $sql = "SELECT id_nilai_safetymargin,total_score,prosentase,nilai FROM nilai_safetymargin WHERE(fk_penugasan='".$id_penugasan_dec."') AND (jenis_objek='bangunan')";
                          $result = $db->Execute($sql);
                          if(!$result)
                            echo $db->ErrorMsg();

                          $n_row2 = $result->RecordCount();

                          if($n_row2>0)
                          {
                            $row2 = $result->FetchRow();
                            $sql = "SELECT a.faktor,b.deskripsi as param_sm,c.deskripsi as faktor_sm FROM faktor_safetymargin as a 
                                    LEFT JOIN ref_param_safetymargin as b ON (a.fk_param_safetymargin=b.id_param_safetymargin)
                                    LEFT JOIN ref_faktor_safetymargin as c ON (a.fk_param_safetymargin=c.fk_param_safetymargin) AND (a.faktor=c.nilai_faktor)
                                    WHERE(fk_nilai_safetymargin='".$row2['id_nilai_safetymargin']."') ORDER BY a.fk_param_safetymargin ASC";
                            $result = $db->Execute($sql);
                            if(!$result)
                              echo $db->ErrorMsg();
                            $asc = 97;
                            while($row3 = $result->FetchRow())
                            {
                              $alp = chr($asc);
                              echo "
                              <tr>
                                <td></td>
                                <td colspan='2'>".$alp.". ".$row3['param_sm']."</td>
                                
                                <td align='center' style='border:1px solid #000'>".$row3['faktor']."</td>
                                <td></td>
                                <td colspan='3'>".$row3['faktor_sm']."</td>                                 
                              </tr>";
                              $asc++;
                            }

                            echo "
                            <tr>                              
                              <td colspan='3' align='right'><b>Total&nbsp;&nbsp;</b></td>
                              <td align='center' style='border:1px solid #000'><b>".$row2['total_score']."</b></td>
                              <td colspan='4'></td>
                            </tr>
                            <tr>                              
                              <td colspan='3' align='right'><b>Safety Margin&nbsp;&nbsp;</b></td>
                              <td align='center' style='border:1px solid #000'><b>".$row2['prosentase']."%</b></td>
                              <td colspan='4'></td>
                            </tr>
                            <tr>
                              <td align='center'>-</td>
                              <td colspan='4'>Nilai Pasar Setelah Safety Margin</td>
                              <td align='center'>:</td>
                              <td><b>Rp.</b></td>
                              <td align='right'><b>".number_format($row2['nilai'])."</b></td>
                            </tr>
                            <tr>
                              <td align='center'>-</td>
                              <td colspan='2'>Indikasi Nilai Likuidasi</td>
                              <td></td>
                              <td>&nbsp;</td>
                              <td align='center'>:</td>
                              <td><b>Rp.</b></td>";
                              $likuidasi_bangunan = $row['_nilai_likuidasi_bangunan'];
                              echo "
                              <td align='right'><b>".number_format($likuidasi_bangunan)."</b></td>
                            </tr>
                            ";
                          }

                          
                        echo "</table>
                      </div>
                    </td>
                  </tr>
                </table>";
              }
              $colspan1 = ($row['jenis_perusahaan_penunjuk']=='1'?12:14);
              $colspan2 = ($row['jenis_perusahaan_penunjuk']=='1'?11:13);

              echo "<p class='point'><b>Kesimpulan & Rekomendasi</b></p>
              <table class='report main' cellpadding=0 cellspacing=0>
                <tr>
                  <td rowspan='".($row['jenis_perusahaan_penunjuk']=='1'?8:7)."' align='center' valign='middle'><b>A</b></td>
                  <td colspan='".$colspan1."'><b>Taksasi Nilai;</b></td>
                </tr>
                <tr>
                  <td colspan='".$colspan1."'><b>Objek Penilaian</b></td>
                </tr>
                <tr>
                  <td colspan='4' align='center' valign='middle' style='font-weight:bold;background:#e5e5e5;'>OBJEK</td>
                  <td colspan='2' align='center' valign='middle' style='font-weight:bold;background:#e5e5e5;'>NILAI PASAR</td>
                  <td colspan='2' align='center' valign='middle' style='font-weight:bold;background:#e5e5e5;'>RATA-RATA/M<sup>2</sup><br />(Rp.)</td>";
                  if($row['jenis_perusahaan_penunjuk']=='2')                  
                    echo "<td colspan='2' align='center' valign='middle' style='font-weight:bold;background:#e5e5e5;'>NILAI PASAR<br />SETELAH SAFETY MARGIN</td>";
                  
                  echo "<td colspan='2' align='center' valign='middle' style='font-weight:bold;background:#e5e5e5;'>INDIKASI NILAI LIKUIDASI</td>
                  <td colspan='2' align='center' valign='middle' style='font-weight:bold;background:#e5e5e5;'>RATA-RATA/M<sup>2</sup><br />(Rp.)</td>
                </tr>
                <tr>
                  <td colspan='2'><b>a.&nbsp;Tanah</b></td>
                  <td align='right' style='border-left:none'>".number_format($row['luas_tanah'],2,'.',',')."</td>
                  <td align='center' style='border-left:none'>m<sup>2</sup></td>
                  <td>Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_pasar_tanah'],2,'.',',')."</td>
                  <td>@Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_satuan_tanah'],2,'.',',')."</td>";
                  if($row['jenis_perusahaan_penunjuk']=='2')                  
                    echo "<td>Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_safetymargin_tanah'],2,'.',',')."</td>";
                  
                  echo "<td>Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_likuidasi_tanah'],2,'.',',')."</td>
                  <td>@Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_satuan_likuidasi_tanah'],2,'.',',')."</td>
                </tr>
                <tr>
                  <td colspan='2'><b>b.&nbsp;Bangunan</b></td>
                  <td align='right' style='border-left:none'>".number_format($row['luas_bangunan'],2,'.',',')."</td>
                  <td align='center' style='border-left:none'>m<sup>2</sup></td>
                  <td>Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_pasar_bangunan'],2,'.',',')."</td>
                  <td>@Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_satuan_bangunan'],2,'.',',')."</td>";
                  if($row['jenis_perusahaan_penunjuk']=='2')                  
                    echo "<td>Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_safetymargin_bangunan'],2,'.',',')."</td>";                  
                  echo "
                  <td>Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_likuidasi_bangunan'],2,'.',',')."</td>
                  <td>@Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_satuan_likuidasi_bangunan'],2,'.',',')."</td>
                </tr>";

                if($row['jenis_perusahaan_penunjuk']=='1')
                {
                  echo "
                  <tr>
                    <td colspan='2'><b>c.&nbsp;Sarana Pelengkap</b></td>
                    <td style='border-left:none'></td>
                    <td style='border-left:none'></td>
                    <td>Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_pasar_sarana_pelengkap'],2,'.',',')."</td>
                    <td></td><td style='border-left:none'></td>
                    <td>Rp.</td><td style='border-left:none' align='right'>".number_format($row['nilai_likuidasi_sarana_pelengkap'],2,'.',',')."</td>
                    <td></td><td style='border-left:none'></td>
                  </tr>";
                }

                echo "<tr>
                  <td colspan='4'><b>Nilai Objek</b></td>
                  <td style='background:#d1d1d1;'><b>Rp.</b></td><td style='border-left:none;background:#d1d1d1;' align='right'><b>".number_format($row['nilai_pasar_objek'],2,'.',',')."</b></td>
                  <td></td><td style='border-left:none'></td>";
                  if($row['jenis_perusahaan_penunjuk']=='2')
                    echo "<td style='background:#d1d1d1;'><b>Rp.</b></td><td style='border-left:none;background:#d1d1d1;' align='right'><b>".number_format($row['nilai_safetymargin_objek'],2,'.',',')."</b></td>";

                  echo "<td style='background:#d1d1d1;'><b>Rp.</b></td><td style='border-left:none;background:#d1d1d1;' align='right'><b>".number_format($row['nilai_likuidasi_objek'],2,'.',',')."</b></td>
                  <td></td><td style='border-left:none'></td>
                </tr>
                <tr>
                  <td colspan='4'><b>Pembulatan</b></td>
                  <td style='background:#d1d1d1;'><b>Rp.</b></td><td style='border-left:none;background:#d1d1d1;' align='right'><b>".number_format($row['pembulatan_pasar_objek'],2,'.',',')."</b></td>
                  <td></td><td style='border-left:none'></td>";
                  if($row['jenis_perusahaan_penunjuk']=='2')
                    echo "<td style='background:#d1d1d1;'><b>Rp.</b></td><td style='border-left:none;background:#d1d1d1;' align='right'><b>".number_format($row['pembulatan_safetymargin_objek'],2,'.',',')."</b></td>";
                  
                  echo "<td style='background:#d1d1d1;'><b>Rp.</b></td><td style='border-left:none;background:#d1d1d1;' align='right'><b>".number_format($row['pembulatan_likuidasi_objek'],2,'.',',')."</b></td>
                  <td></td><td style='border-left:none'></td>
                </tr>
                <tr>
                  <td rowspan='3' align='center' valign='middle'><b>B</b></td>
                  <td colspan='".$colspan1."'><b>Faktor yang dapat menambah nilai :</td>
                </tr>
                <tr>
                  <td><b>Tanah</b></td><td colspan='".$colspan2."' style='border-left:none'>: ".$row['faktor_penambah_nilai_tanah']."</td>
                </tr>
                <tr>
                  <td><b>Bangunan</b></td><td colspan='".$colspan2."' style='border-left:none'>: ".$row['faktor_penambah_nilai_bangunan']."</td>
                </tr>
                <tr>
                  <td rowspan='3' align='center' valign='middle'><b>C</b></td>
                  <td colspan='".$colspan1."'><b>Faktor yang dapat mengurangi nilai :</td>
                </tr>
                <tr>
                  <td><b>Tanah</b></td><td colspan='".$colspan2."' style='border-left:none'>: ".$row['faktor_pengurang_nilai_tanah']."</td>
                </tr>
                <tr>
                  <td><b>Bangunan</b></td><td colspan='".$colspan2."' style='border-left:none'>: ".$row['faktor_pengurang_nilai_bangunan']."</td>
                </tr>
                <tr>
                  <td rowspan='2' align='center' valign='middle'><b>D</b></td>
                  <td colspan='".$colspan1."'><b>Faktor yang dapat memenuhi nilai :</td>
                </tr>
                <tr>
                  <td colspan='".$colspan1."'>".$row['faktor_pemenuh_nilai']."</td>
                </tr>
                <tr>
                  <td colspan='".($row['jenis_perusahaan_penunjuk']=='1'?13:15)."'>
                  <b>CATATAN & KESIMPULAN</b><br />
                  ".$row['kesimpulan']."
                  </td>
                </tr>
              </table>
              <table class='report main' cellpadding=0 cellspacing=0 style='margin-top:5px'>
                <tr>
                  <td align='center' width='25%'><b>REVIEWER I</b></td>
                  <td align='center' width='25%'><b>REVIEWER II</b></td>
                  <td align='center' colspan='2'><b>PENILAI</b></td>                
                </tr>
                <tr>
                  <td style='padding-top:65px' align='center' valign='top'>
                    <b>".$row['nama_reviewer1']."</b><br />
                    Ijin Penilai Properti : ".$row['ijin_reviewer1']."<br />
                    MAPPI : ".$row['mappi_reviewer1']."
                  </td>
                  <td style='padding-top:65px' align='center' valign='top'>
                    <b>".$row['nama_reviewer2']."</b><br />                  
                    MAPPI : ".$row['mappi_reviewer2']."
                  </td>
                  <td style='padding-top:65px' align='center' valign='top'>
                    <b>".$row['nama_penilai1']."</b><br />
                    MAPPI : ".$row['mappi_penilai1']."
                  </td>
                  <td style='border-left:none;padding-top:65px' align='center' valign='top'>
                    <b>".$row['nama_penilai2']."</b><br />
                    MAPPI : ".$row['mappi_penilai2']."
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
