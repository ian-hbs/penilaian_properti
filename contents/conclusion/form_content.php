<?php
  if(!isset($_POST['change_act']))
  {    
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/DML.php";
    include_once "../../libraries/global_obj.php";    
    include_once "../../libraries/user_controller.php";
    include_once "../../helpers/mix_helper.php";
    include_once "../../helpers/date_helper.php";

    //instantiate objects
    $uc = new user_controller($db);

    $source = '1';
  }
  else
  {
    $source = '2';
  }

  $uc->check_access();  

  $id_penugasan = $_POST['id_penugasan'];
  $jenis_perusahaan_penunjuk = $_POST['jenis_perusahaan_penunjuk'];
  $menu_id = $_POST['menu_id'];
  $fn = $_POST['fn'];
  $kunci_pencarian = $_POST['kunci_pencarian'];

  $DML = new DML('kesimpulan_rekomendasi',$db);
  $global = new global_obj($db);  
  
  $base_need = get_base_need($id_penugasan);
  $act = $base_need[0];
  $n_conclusion = $base_need[1];
  $id_kesimpulan_rekomendasi = $base_need[2];  

  $_conclusion = $global->get_active_conclusion($id_penugasan);
  $ord_num = $_conclusion[0];

  $id_name = 'fk_penugasan';
  $id_value = ($act=='edit'?$id_penugasan:'');
  
  $arr_field = array('faktor_penambah_nilai_tanah','faktor_penambah_nilai_bangunan','faktor_pengurang_nilai_tanah',
                     'faktor_pengurang_nilai_bangunan','faktor_pemenuh_nilai','kesimpulan');
  
  $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);  
  
  if($act=='add')
    $sql = get_default_sql($jenis_perusahaan_penunjuk,$id_penugasan);  
  else
  {    
    if($source=='1')
      $sql = "SELECT * FROM kesimpulan_rekomendasi WHERE(fk_penugasan='".$id_penugasan."')";
    else
    {      
      $sql = "SELECT * FROM log_kesimpulan_rekomendasi WHERE(fk_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."') AND (no_urut='".$ord_num."')";
    }
  }

  $result = $db->Execute($sql);
  
  if(!$result)
      die($db->ErrorMsg());

  $n_main_row = $result->RecordCount();

  $luas_tanah = 0;
  $luas_bangunan = 0;
  $nilai_pasar_tanah = 0;
  $nilai_pasar_bangunan = 0;
  $nilai_pasar_sarana_pelengkap = 0;
  $nilai_safetymargin_tanah = 0;
  $nilai_safetymargin_bangunan = 0;
  $nilai_likuidasi_tanah = 0;
  $nilai_likuidasi_bangunan = 0;
  $nilai_likuidasi_sarana_pelengkap = 0;
  $nilai_satuan_tanah = 0;
  $nilai_satuan_likuidasi_tanah = 0;
  $nilai_satuan_bangunan = 0;
  $nilai_satuan_likuidasi_bangunan = 0;
  $nilai_pasar_objek = 0;
  $nilai_safetymargin_objek = 0;
  $nilai_likuidasi_objek = 0;
  $pembulatan_pasar_objek = 0;
  $pembulatan_safetymargin_objek = 0;
  $pembulatan_likuidasi_objek = 0;  

  if($n_main_row>0)
  {
      $main_row = $result->FetchRow();      

      $luas_tanah = $main_row['luas_tanah'];
      $luas_bangunan = $main_row['luas_bangunan'];
      $nilai_pasar_tanah = $main_row['nilai_pasar_tanah'];
      $nilai_pasar_bangunan = $main_row['nilai_pasar_bangunan'];
      $nilai_pasar_sarana_pelengkap = ($jenis_perusahaan_penunjuk=='1'?$main_row['nilai_pasar_sarana_pelengkap']:0);      

      $nilai_likuidasi_tanah = $main_row['nilai_likuidasi_tanah'];
      $nilai_likuidasi_bangunan = $main_row['nilai_likuidasi_bangunan'];
      $nilai_likuidasi_sarana_pelengkap = ($jenis_perusahaan_penunjuk=='1'?$main_row['nilai_likuidasi_sarana_pelengkap']:0);

      $nilai_satuan_tanah = ($main_row['luas_tanah']==0?0:$main_row['nilai_pasar_tanah']/$main_row['luas_tanah']);
      $nilai_satuan_likuidasi_tanah = ($main_row['luas_tanah']==0?0:$main_row['nilai_likuidasi_tanah']/$main_row['luas_tanah']);
      $nilai_satuan_bangunan = ($main_row['luas_bangunan']==0?0:$main_row['nilai_pasar_bangunan']/$main_row['luas_bangunan']);
      $nilai_satuan_likuidasi_bangunan = ($main_row['luas_bangunan']==0?0:$main_row['nilai_likuidasi_bangunan']/$main_row['luas_bangunan']);

      $nilai_pasar_objek = $nilai_pasar_tanah+$nilai_pasar_bangunan+$nilai_pasar_sarana_pelengkap;            
      $nilai_likuidasi_objek = $nilai_likuidasi_tanah+$nilai_likuidasi_bangunan+$nilai_likuidasi_sarana_pelengkap;

      $pembulatan_pasar_objek = round($nilai_pasar_objek,-6);      
      $pembulatan_likuidasi_objek = round($nilai_likuidasi_objek,-6);

      if($n_conclusion>0)
      {
        $nilai_safetymargin_tanah = ($jenis_perusahaan_penunjuk=='2'?$main_row['nilai_safetymargin_tanah']:0);
        $nilai_safetymargin_bangunan = ($jenis_perusahaan_penunjuk=='2'?$main_row['nilai_safetymargin_bangunan']:0);
        $nilai_safetymargin_objek = ($jenis_perusahaan_penunjuk=='2'?($main_row['nilai_safetymargin_tanah']+$main_row['nilai_safetymargin_bangunan']):0);
        $pembulatan_safetymargin_objek = round($nilai_safetymargin_objek,-6);
      }
  }  

  $form_id = 'conclusion-form';  

  function get_base_need($id_penugasan)
  {
    global $db;
    $sql = "SELECT a.id_kesimpulan_rekomendasi,b.no_urut,b.user_input FROM kesimpulan_rekomendasi as a 
            INNER JOIN (SELECT fk_kesimpulan_rekomendasi,no_urut,user_input FROM log_kesimpulan_rekomendasi WHERE(status='Y')) as b
            ON (a.id_kesimpulan_rekomendasi=b.fk_kesimpulan_rekomendasi) WHERE a.fk_penugasan='".$id_penugasan."'";

    $result = $db->Execute($sql);
    if(!$result)
      echo $db->ErrorMsg();

    $act = 'add';
    $n_result = $result->RecordCount();
    $id_kesimpulan_rekomendasi = '';

    if($n_result>0)
    {
      $row = $result->FetchRow();
      $act = ($row['user_input']=='-'?'add':'edit');
      $id_kesimpulan_rekomendasi = $row['id_kesimpulan_rekomendasi'];
    }

    return array(0=>$act,1=>$n_result,$id_kesimpulan_rekomendasi);
  }

  function get_default_sql($jenis_perusahaan_penunjuk,$id_penugasan)
  {    
    $sql_ext = "";      
    if($jenis_perusahaan_penunjuk=='2')
    {
      $sql_ext = "(SELECT nilai FROM nilai_safetymargin as x WHERE(x.fk_penugasan=a.fk_penugasan) AND (x.jenis_objek='tanah')) as nilai_safetymargin_tanah,
                  (SELECT nilai FROM nilai_safetymargin as x WHERE(x.fk_penugasan=a.fk_penugasan) AND (x.jenis_objek='bangunan')) as nilai_safetymargin_bangunan,";
    }

    $sql = "SELECT a.luas_tanah,(SELECT SUM(total) FROM luas_bangunan as x WHERE x.fk_penugasan=a.fk_penugasan) as luas_bangunan,
            (SELECT SUM(market_value) FROM perhitungan_bangunan as x WHERE (x.fk_penugasan=a.fk_penugasan) AND (x.type='building')) as nilai_pasar_bangunan,
            (SELECT SUM(liquidation_value) FROM perhitungan_bangunan as x WHERE (x.fk_penugasan=a.fk_penugasan) AND (x.type='building')) as nilai_likuidasi_bangunan,
            (SELECT SUM(market_value) FROM perhitungan_bangunan as x WHERE (x.fk_penugasan=a.fk_penugasan) AND (x.type='site improvement')) as nilai_pasar_sarana_pelengkap,
            (SELECT SUM(liquidation_value) FROM perhitungan_bangunan as x WHERE (x.fk_penugasan=a.fk_penugasan) AND (x.type='site improvement')) as nilai_likuidasi_sarana_pelengkap,
            ".$sql_ext."
            b.final_rounded as nilai_pasar_tanah,b.liquidation_value as nilai_likuidasi_tanah,
            c.fk_perusahaan_penunjuk,c.nama_reviewer1,c.ijin_reviewer1,c.mappi_reviewer1,
            d.nama_reviewer2,d.mappi_reviewer2,
            e.nama_penilai1,e.mappi_penilai1,
            f.nama_penilai2,f.mappi_penilai2
            FROM objek_tanah as a, perhitungan_tanah as b, 
            (SELECT x.id_penugasan,x.fk_perusahaan_penunjuk,y.nama as nama_reviewer1,y.ijin_penilai as ijin_reviewer1,y.no_mappi as mappi_reviewer1 FROM penugasan as x LEFT JOIN ref_penilai as y ON (x.reviewer1=y.id_penilai)) as c,
            (SELECT x.id_penugasan,y.nama as nama_reviewer2,y.no_mappi as mappi_reviewer2 FROM penugasan as x LEFT JOIN ref_penilai as y ON (x.reviewer2=y.id_penilai)) as d,
            (SELECT x.id_penugasan,y.nama as nama_penilai1,y.no_mappi as mappi_penilai1 FROM penugasan as x LEFT JOIN ref_penilai as y ON (x.penilai1=y.id_penilai)) as e,
            (SELECT x.id_penugasan,y.nama as nama_penilai2,y.no_mappi as mappi_penilai2 FROM penugasan as x LEFT JOIN ref_penilai as y ON (x.penilai2=y.id_penilai)) as f
            WHERE(a.fk_penugasan=b.fk_penugasan AND a.fk_penugasan=c.id_penugasan AND a.fk_penugasan=d.id_penugasan AND a.fk_penugasan=e.id_penugasan AND a.fk_penugasan=f.id_penugasan)
            AND (a.fk_penugasan='".$id_penugasan."')";
    return $sql;
  }
?>

<script type="text/javascript">
  var form_id = '<?php echo $form_id;?>';
  var $input_form = $('#'+form_id);
  var stat = $input_form.validate();
  var act_lbl = 'menambah';

  $input_form.submit(function(){
      if(stat.checkForm())
      {
          ajax_manipulate.reset_object();
          ajax_manipulate.set_plugin_datatable(true)
                       .set_content('#list-of-data')
                       .set_loading('#preloadAnimation')
                       .enable_pnotify()
                       .set_close_modal('#close-modal-form')
                       .set_form($input_form)
                       .submit_ajax(act_lbl);
          $('#close-modal-form').click();
          return false;
      }
  });

</script>

<style type="text/css">
table.tableForm{width:100%;border:3px solid #c5c5c5;}
table.tableForm tr{border-bottom:1px solid #c5c5c5;}
table.tableForm tbody > tr:last-child{border-bottom:none;}
table.tableForm td{border-right:1px solid #c5c5c5;padding:3px;}
table.tableForm td:last-child{border-right:none;}
table.tableForm tbody > tr:hover{background:#eaeaea;}
table.noBorder{border:none!important;}
table.noBorder tr{border:none!important;}
table.noBorder td{border:none!important;}
</style>

<?php

  $pd = $global->get_property_detail($id_penugasan);
  $pd['tgl_survei'] = indo_date_format($pd['tgl_survei'],'longDate');

  $global->print_property_detail($pd);

  if($n_main_row>0)
  {
      echo "
      <div class='form-horizontal'>
        <div class='form-group'>
          <div class='col-md-12' align='center'>
            Data             
            <select id='order_num' onchange=\"change_conclusion('".$id_penugasan."','".$id_kesimpulan_rekomendasi."',this.value,'".$jenis_perusahaan_penunjuk."','".$menu_id."','".$fn."','".$kunci_pencarian."');\">";

            $order_nums = array(0=>array(1,'-'));

            if($n_conclusion>0)
            {
              $sql = "SELECT no_urut,user_input FROM log_kesimpulan_rekomendasi WHERE(fk_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."')";  
              $result = $db->Execute($sql);
              if(!$result)
                echo $db->ErrorMsg();
              $i = 0;
              while($row=$result->FetchRow())              
              {
                $order_nums[$i] = array(0=>$row['no_urut'],1=>$row['user_input']);
                $i++;
              }
            }

            foreach($order_nums as $row)
            {
              $selected = ($row[0]==$ord_num?'selected':'');
              echo "<option value='".$row[0]."' ".$selected.">".NumToRomawi($row[0])." (".NumToWords($row[0]).")</option>";
            }

            echo "</select>&nbsp;&nbsp;|&nbsp;&nbsp;";

            $input_stat = ($act=='add'?"<font color='orange'>Belum diinput</font>":"<font color='green'>Sudah diinput</font>");
            echo $input_stat;

            if($n_conclusion>0)
            {
              echo "              
              &nbsp;&nbsp;|&nbsp;&nbsp;
              <button id='change_conclusion_btn' onclick=\"open_conclusion(this.id)\">
              <input type='hidden' id='ajax-req-dt' name='id_penugasan' value='".$id_penugasan."'/>
              <input type='hidden' id='ajax-req-dt' name='id_kesimpulan_rekomendasi' value='".$id_kesimpulan_rekomendasi."'/>
              <input type='hidden' id='ajax-req-dt' name='jenis_perusahaan_penunjuk' value='".$jenis_perusahaan_penunjuk."'/>
              <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
              <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
              <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>                
              <input type='hidden' id='ajax-req-dt' name='change_act' value='open'/>
              Buka Mode Perubahan
              </button>
              &nbsp;&nbsp;|&nbsp;&nbsp;
              <a href='javascript:;' id='delete_conclusion_btn' onclick=\"if(confirm('Anda yakin?')){delete_conclusion(this.id)};\" style='text-decoration:underline'>
              <input type='hidden' id='ajax-req-dt' name='id_penugasan' value='".$id_penugasan."'/>
              <input type='hidden' id='ajax-req-dt' name='id_kesimpulan_rekomendasi' value='".$id_kesimpulan_rekomendasi."'/>
              <input type='hidden' id='ajax-req-dt' name='no_urut' value='".$ord_num."'/>
              <input type='hidden' id='ajax-req-dt' name='jenis_perusahaan_penunjuk' value='".$jenis_perusahaan_penunjuk."'/>
              <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
              <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
              <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>                
              <input type='hidden' id='ajax-req-dt' name='change_act' value='delete'/>
              Hapus Data</a>";
            }
          echo "</div>
        </div>
      </div>


      <form class='form-horizontal' id='".$form_id."' method='POST' action='contents/".$fn."/manipulating1.php'>
      <input type='hidden' name='fk_penugasan' value='".$id_penugasan."'/>      
      <input type='hidden' name='menu_id' value='".$menu_id."'/>
      <input type='hidden' name='fn' value='".$fn."'/>
      <input type='hidden' name='id_kesimpulan_rekomendasi' value='".$id_kesimpulan_rekomendasi."'/>
      <input type='hidden' name='no_urut' value='".$ord_num."'/>
      <input type='hidden' name='kunci_pencarian' value='".$kunci_pencarian."'/>
          <div class='row'>
              <div class='col-md-12'>
                <table border=1 cellspacing=5 class='tableForm' cellpadding=5>
                  <thead>
                    <tr><td colspan='".($jenis_perusahaan_penunjuk=='1'?5:6)."'><b>Taksasi Nilai;</b></td></tr>
                    <tr><td colspan='".($jenis_perusahaan_penunjuk=='1'?5:6)."'><b>Objek Penilaian</b></td></tr>
                    <tr>
                      <td class='tableHead'>Objek</td><td class='tableHead'>Nilai Pasar</td>
                      <td class='tableHead'>Rata-Rata/m<sup>2</sup><br />(Rp.)</td>";
                      if($jenis_perusahaan_penunjuk=='2')                      
                        echo "<td class='tableHead'>Nilai Pasar<br />Setelah Safety Margin</td>";                      

                      echo "<td class='tableHead'>Indikasi Nilai Likuidasi</td>
                      <td class='tableHead'>Rata-Rata/m<sup>2</sup><br />(Rp.)</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <table class='noBorder' width='100%'>                        
                          <tr><td><b>a.</b></td><td><b>Tanah</b></td><td align='right'>".number_format($luas_tanah,2,'.',',')." m<sup>2</sup></td></tr>
                          <tr><td><b>b.</b></td><td><b>Bangunan</b></td><td align='right'>".number_format($luas_bangunan,2,'.',',')." m<sup>2</sup></td></tr>";
                          if($jenis_perusahaan_penunjuk=='1')
                            echo "<tr><td><b>c.</b></td><td><b>Sarana Pelengkap</b></td><td></td></tr>";
                        
                        echo "</table>
                      </td>
                      <td>
                        <table class='noBorder' width='100%'>
                          <tr><td>Rp.</td><td align='right'>".number_format($nilai_pasar_tanah)."</td></tr>
                          <tr><td>Rp.</td><td align='right'>".number_format($nilai_pasar_bangunan)."</td></tr>";
                          if($jenis_perusahaan_penunjuk=='1')
                            echo "<tr><td>Rp.</td><td align='right'>".number_format($nilai_pasar_sarana_pelengkap)."</td></tr>";
                        
                        echo "</table>
                      </td>
                      <td>
                        <table class='noBorder' width='100%'>
                          <tr><td>@Rp.</td><td align='right'>".number_format($nilai_satuan_tanah)."</td></tr>
                          <tr><td>@Rp.</td><td align='right'>".number_format($nilai_satuan_bangunan)."</td></tr>";
                          if($jenis_perusahaan_penunjuk=='1')
                            echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
                        
                        echo "</table>
                      </td>";
                      if($jenis_perusahaan_penunjuk=='2')
                      {
                        echo "
                        <td>
                          <table class='noBorder' width='100%'>
                            <tr><td>Rp.</td><td align='right'>".number_format($nilai_safetymargin_tanah)."</td></tr>
                            <tr><td>Rp.</td><td align='right'>".number_format($nilai_safetymargin_bangunan)."</td></tr>                            
                          </table>
                        </td>";
                      }
                      echo "
                      <td>
                        <table class='noBorder' width='100%'>
                          <tr><td>Rp.</td><td align='right'>".number_format($nilai_likuidasi_tanah)."</td></tr>
                          <tr><td>Rp.</td><td align='right'>".number_format($nilai_likuidasi_bangunan)."</td></tr>";
                          if($jenis_perusahaan_penunjuk=='1')
                            echo "<tr><td>Rp.</td><td align='right'>".number_format($nilai_likuidasi_sarana_pelengkap)."</td></tr>";
                        
                        echo "</table>
                      </td>
                      <td>
                        <table class='noBorder' width='100%'>
                          <tr><td>@Rp.</td><td align='right'>".number_format($nilai_satuan_likuidasi_tanah)."</td></tr>
                          <tr><td>@Rp.</td><td align='right'>".number_format($nilai_satuan_likuidasi_bangunan)."</td></tr>";
                          if($jenis_perusahaan_penunjuk=='1')
                            echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";

                        echo "</table>
                      </td>
                    </tr>
                    <tr>
                      <td align='right'><b>Nilai Obyek</b></td>
                      <td>
                        <table class='noBorder' width='100%'>
                           <tr><td><b>Rp.</b></td><td align='right'><b>".number_format($nilai_pasar_objek)."</b></td></tr>
                        </table>
                      </td>
                      <td></td>";
                      if($jenis_perusahaan_penunjuk=='2')
                      {
                        echo "
                        <td>
                          <table class='noBorder' width='100%'>
                            <tr><td><b>Rp.</b></td><td align='right'><b>".number_format($nilai_safetymargin_objek)."</b></td></tr>
                          </table>
                        </td>";
                      }
                      echo "
                      <td>
                        <table class='noBorder' width='100%'>
                          <tr><td><b>Rp.</b></td><td align='right'><b>".number_format($nilai_likuidasi_objek)."</b></td></tr>
                        </table>
                      </td>
                      <td></td>
                    </tr>
                    <tr>
                      <td align='right'><b>Pembulatan</b></td>
                      <td>
                        <table class='noBorder' width='100%'>
                          <tr><td><b>Rp.</b></td><td align='right'><b>".number_format($pembulatan_pasar_objek)."</b></td></tr>
                        </table>
                      </td>
                      <td></td>";
                      if($jenis_perusahaan_penunjuk=='2')
                      {
                        echo "
                        <td>
                          <table class='noBorder' width='100%'>
                            <tr><td><b>Rp.</b></td><td align='right'><b>".number_format($pembulatan_safetymargin_objek)."</b></td></tr>
                          </table>
                        </td>";
                      }
                      echo "<td>
                        <table class='noBorder' width='100%'>
                          <tr><td><b>Rp.</b></td><td align='right'><b>".number_format($pembulatan_likuidasi_objek)."</b></td></tr>
                        </table>
                      </td>
                      <td></td>
                    </tr>";
                    /*
                    echo "
                    <tr>
                      <td colspan='".($jenis_perusahaan_penunjuk=='2'?6:5)."'><b>Faktor yang dapat menambah nilai :</b></td>
                    </tr>
                    <tr>
                      <td colspan='".($jenis_perusahaan_penunjuk=='2'?6:5)."'>
                      <div class='row'>
                        <div class='col-md-1'>Tanah</div>
                        <div class='col-md-11'><input type='text' name='faktor_penambah_nilai_tanah' id='faktor_penambah_nilai_tanah' value=\"".$curr_data['faktor_penambah_nilai_tanah']."\" class='form-control'/></div>
                      </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan='".($jenis_perusahaan_penunjuk=='2'?6:5)."'>
                      <div class='row'>
                        <div class='col-md-1'>Bangunan</div>
                        <div class='col-md-11'><input type='text' name='faktor_penambah_nilai_bangunan' id='faktor_penambah_nilai_bangunan' value=\"".$curr_data['faktor_penambah_nilai_bangunan']."\" class='form-control'/></div>
                      </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan='".($jenis_perusahaan_penunjuk=='2'?6:5)."'><b>Faktor yang dapat mengurangi nilai :</b></td>
                    </tr>
                    <tr>
                      <td colspan='".($jenis_perusahaan_penunjuk=='2'?6:5)."'>
                      <div class='row'>
                        <div class='col-md-1'>Tanah</div>
                        <div class='col-md-11'><input type='text' name='faktor_pengurang_nilai_tanah' id='faktor_pengurang_nilai_tanah' value=\"".$curr_data['faktor_pengurang_nilai_tanah']."\" class='form-control'/></div>
                      </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan='".($jenis_perusahaan_penunjuk=='2'?6:5)."'>
                      <div class='row'>
                        <div class='col-md-1'>Bangunan</div>
                        <div class='col-md-11'><input type='text' name='faktor_pengurang_nilai_bangunan' id='faktor_pengurang_nilai_bangunan' value=\"".$curr_data['faktor_pengurang_nilai_bangunan']."\" class='form-control'/></div>
                      </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan='".($jenis_perusahaan_penunjuk=='2'?6:5)."'><b>Faktor yang dapat memenuhi nilai :</b></td>
                    </tr>
                    <tr>
                      <td colspan='".($jenis_perusahaan_penunjuk=='2'?6:5)."'>
                      <div class='row'>                      
                        <div class='col-md-12'><input type='text' name='faktor_pemenuh_nilai' id='faktor_pemenuh_nilai' class='form-control' value=\"".$curr_data['faktor_pemenuh_nilai']."\"/></div>
                      </div>
                      </td>
                    </tr>
                    ";
                    */

                    echo "
                    <tr>
                      <td colspan='".($jenis_perusahaan_penunjuk=='2'?6:5)."'><b>Catatan & Kesimpulan</b></td>
                    </tr>
                    <tr>
                      <td colspan='".($jenis_perusahaan_penunjuk=='2'?6:5)."'>
                      <div class='row'>                      
                        <div class='col-md-12'><input type='text' name='kesimpulan' id='kesimpulan' value=\"".$curr_data['kesimpulan']."\" class='form-control'/></div>
                      </div>
                      </td>
                    </tr>
                  </tbody>
                </table> 
                <table border=1 cellspacing=5 class='tableForm' cellpadding=5>
                  <thead>
                    <tr><td class='tableHead'>REVIEWER I</td><td class='tableHead'>REVIEWER II</td><td class='tableHead'>PENILAI</td>
                  </thead>
                  <tbody>
                    <tr>
                      <td align='center'><b>".$main_row['nama_reviewer1']."</b><br />
                      Ijin Penilai Properti : ".$main_row['ijin_reviewer1']."<br />
                      MAPPI : ".$main_row['mappi_reviewer1']."
                      </td>
                      <td align='center'><b>".$main_row['nama_reviewer2']."</b><br />                    
                      MAPPI : ".$main_row['mappi_reviewer2']."<br />&nbsp;
                      </td>
                      <td>
                        <table class='noBorder' width='100%'>
                          <tr>
                          <td align='center'>
                          <b>".$main_row['nama_penilai1']."</b><br />                    
                          MAPPI : ".$main_row['mappi_penilai1']."<br />&nbsp;
                          </td>
                          <td align='center'>
                          <b>".$main_row['nama_penilai2']."</b><br />
                          MAPPI : ".$main_row['mappi_penilai2']."<br />&nbsp;
                          </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <input type='hidden' name='luas_tanah' value='".$luas_tanah."'/>
                <input type='hidden' name='luas_bangunan' value='".$luas_bangunan."'/>
                <input type='hidden' name='nilai_pasar_tanah' value='".$nilai_pasar_tanah."'/>
                <input type='hidden' name='nilai_pasar_bangunan' value='".$nilai_pasar_bangunan."'/>
                <input type='hidden' name='nilai_pasar_sarana_pelengkap' value='".$nilai_pasar_sarana_pelengkap."'/>
                <input type='hidden' name='nilai_satuan_tanah' value='".$nilai_satuan_tanah."'/>
                <input type='hidden' name='nilai_satuan_bangunan' value='".$nilai_satuan_bangunan."'/>
                <input type='hidden' name='nilai_safetymargin_tanah' value='".$nilai_safetymargin_tanah."'/>
                <input type='hidden' name='nilai_safetymargin_bangunan' value='".$nilai_safetymargin_bangunan."'/>
                <input type='hidden' name='nilai_likuidasi_tanah' value='".$nilai_likuidasi_tanah."'/>
                <input type='hidden' name='nilai_likuidasi_bangunan' value='".$nilai_likuidasi_bangunan."'/>
                <input type='hidden' name='nilai_likuidasi_sarana_pelengkap' value='".$nilai_likuidasi_sarana_pelengkap."'/>
                <input type='hidden' name='nilai_satuan_likuidasi_tanah' value='".$nilai_satuan_likuidasi_tanah."'/>
                <input type='hidden' name='nilai_satuan_likuidasi_bangunan' value='".$nilai_satuan_likuidasi_bangunan."'/>
                <input type='hidden' name='nilai_pasar_objek' value='".$nilai_pasar_objek."'/>                
                <input type='hidden' name='nilai_safetymargin_objek' value='".$nilai_safetymargin_objek."'/>
                <input type='hidden' name='nilai_likuidasi_objek' value='".$nilai_likuidasi_objek."'/>
                <input type='hidden' name='pembulatan_pasar_objek' value='".$pembulatan_pasar_objek."'/>
                <input type='hidden' name='pembulatan_safetymargin_objek' value='".$pembulatan_safetymargin_objek."'/>
                <input type='hidden' name='pembulatan_likuidasi_objek' value='".$pembulatan_likuidasi_objek."'/>
                <input type='hidden' name='nama_reviewer1' value='".$main_row['nama_reviewer1']."'/>
                <input type='hidden' name='ijin_reviewer1' value='".$main_row['ijin_reviewer1']."'/>
                <input type='hidden' name='mappi_reviewer1' value='".$main_row['mappi_reviewer1']."'/>
                <input type='hidden' name='nama_reviewer2' value='".$main_row['nama_reviewer2']."'/>
                <input type='hidden' name='mappi_reviewer2' value='".$main_row['mappi_reviewer2']."'/>
                <input type='hidden' name='nama_penilai1' value='".$main_row['nama_penilai1']."'/>
                <input type='hidden' name='mappi_penilai1' value='".$main_row['mappi_penilai1']."'/>
                <input type='hidden' name='nama_penilai2' value='".$main_row['nama_penilai2']."'/>
                <input type='hidden' name='mappi_penilai2' value='".$main_row['mappi_penilai2']."'/>
              </div>
          </div>

          <div class='ln_solid'></div><br />
          <div class='form-group'>
              <div class='col-md-12 col-sm-12 col-xs-12' align='center'>
                <button type='button' class='btn btn-danger' id='close-modal-form' data-dismiss='modal'>Batal</button>
                <button type='submit' class='btn btn-success' ".($act=='edit'?'disabled':'').">Simpan</button>
              </div>              
          </div>
      </form>";
  }
  else
  {
      $checked = array();
      for($i=3;$i<=15;$i++)
        $checked[] = $i;

      $need_entry = $global->get_need_entry($id_penugasan,$checked);

      $err_msg = "Data pada form ";
      $s = false;
      foreach($need_entry[1] as $val)
      {
        $err_msg .= ($s?", ":"")."(".$val.")";
        $s = true;
      }
      
      $err_msg .= ' belum diinput.';

      echo "<div class='alert alert-warning' role='alert'>
              <button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
              ".$err_msg."<br />
              Silahkan lengkapi terlebih dahulu!
            </div>";
  }
    
?>
