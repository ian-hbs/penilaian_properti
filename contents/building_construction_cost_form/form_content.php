<?php
  
  $act = ($bct==''?'add':'edit');

  $sql = "SELECT * FROM ref_kelompok_komponen_bangunan";
  $result = $db->Execute($sql);
  if(!$result)
    echo $db->ErrorMsg();

  $building_component_groups = array();
  while($row = $result->FetchRow())
  {
    $building_component_groups[] = array('id'=>$row['id_kelompok_komponen_bangunan'],'nm'=>$row['kelompok_komponen_bangunan'],
                                         'dk'=>$row['diklasifikasi'],'mi'=>$row['maks_inputan']);
  }

  $id_perhitunganbkb_master = '';

  if($act=='edit')
  {
    include_once "../../libraries/global_obj.php";
    include_once "../../helpers/date_helper.php";

    $global = new global_obj($db);
    
    $editAccess = $uc->check_priviledge('update',$menu_id2);
    $checkAccess = $editAccess;

    $curr_data = array();
    $components = array();

    $sql = "SELECT a.*,b.* FROM perhitunganbkb_master as a LEFT JOIN perhitunganbkb_hasil as b ON (a.id_perhitunganbkb_master=b.fk_perhitunganbkb_master) WHERE no_bct='".$bct."'";
    $result1 = $db->Execute($sql);
    if(!$result1)
      echo $db->ErrorMsg();    

    if($n_data>0)
    {
      $row1 = $result1->FetchRow();

      $id_perhitunganbkb_master = $row1['id_perhitunganbkb_master'];

      foreach($row1 as $key=>$val)
      {
        $curr_data[$key] = $val;
      }
      
      $province_id = $global->get_province_id($curr_data['provinsi']);
      $regency_id = $global->get_regency_id($curr_data['kota'],$province_id);
      $district_id = $global->get_district_id($curr_data['kecamatan'],$regency_id);
      $village_id = $global->get_village_id($curr_data['kelurahan'],$district_id);
      
      $sql = "SELECT * FROM perhitunganbkb_komponen WHERE fk_perhitunganbkb_master='".$row1['id_perhitunganbkb_master']."'";
      $result2 = $db->Execute($sql);
      if(!$result2)
        echo $db->ErrorMsg();
      
      while($row2=$result2->FetchRow())
      {
        $components[$row2['fk_kelompok_komponen_bangunan']][$row2['material_ke']] = array('id'=>$row2['id_perhitunganbkb_komponen'],'jkb'=>$row2['fk_jenis_komponen_bangunan'],
                                                                                         'vol'=>$row2['volume'],'hgs'=>$row2['harga_satuan']);
      }
    }

  }
  else
  {    
    $checkAccess = $addAccess;    
  }


?>
<script type="text/javascript">
    var form_id = '<?php echo $form_id;?>';
    var $form = $('#'+form_id);
    var stat = $form.validate();    
    var fn = "<?php echo $fn; ?>";
    var act_label = "<?php echo ($act=='add'?'menambah':'merubah'); ?>";

    $form.submit(function(){
        if(stat.checkForm())
        {
            ajax_manipulate.reset_object();
            ajax_manipulate.set_plugin_datatable(true)
                          <?php
                            if($act=='add')                              
                              echo ".set_content('#form-content')";
                          ?>
                           .set_loading('#preloadAnimation')                           
                           .set_form($form)
                           .submit_ajax(act_label);
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
</style>

<?php
  
  $DML1 = new DML('ref_penilai',$db);  
  $DML2 = new DML('ref_jenis_objek',$db);
  $DML3 = new DML('ref_klasifikasi_bangunan',$db);
  $DML4 = new DML('ref_provinces',$db);
  $DML5 = new DML('ref_fee_kontraktor',$db);
  
  $SYSTEM_PARAMS = $_APP_PARAM['system_params'];

  if($checkAccess)
  {
    if($n_data>0)
    {
      echo "  
      <div class='box'>          
        <form name='valuation_form' class='form-horizontal' id='".$form_id."' method='POST' action='contents/".$fn."/manipulating.php'>
        <input type='hidden' name='fn' value='".$fn."'/>
        <input type='hidden' name='menu_id' value='".$menu_id1."'/>
        <input type='hidden' name='act' value='".$act."'/>
        <input type='hidden' name='id_perhitunganbkb_master' value='".$id_perhitunganbkb_master."'/>
        <div class='box-header with-border'>
            <h3 class='box-title'><a href='#' onclick=\"fill_dummy_data()\">Penilai & Identitas Properti</a></h3>
            <div class='box-tools pull-right'>
            </div>
          </div><!-- /.box-header -->
        <div class='box-body'>          
          <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Penilai 1 <font color='red'>*</font></label>
                  <div class='col-sm-9'>
                    <select name='penilai1' id='penilai1' class='form-control' required>
                      <option value='' selected></option>";
                      $opts = $DML1->FetchAllData();
                      foreach($opts as $row)
                      {
                        $selected = ($act=='edit'?($curr_data['penilai1']==$row['nama']?'selected':''):'');
                        echo "<option value='".$row['nama']."' ".$selected.">".$row['nama']."</option>";
                      }
                    echo "</select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Penilai 2</label>
                  <div class='col-sm-9'>
                    <select name='penilai2' id='penilai2' class='form-control'>
                      <option value='' selected></option>";
                      $opts = $DML1->FetchAllData();
                      foreach($opts as $row)
                      {
                        $selected = ($act=='edit'?($curr_data['penilai2']==$row['nama']?'selected':''):'');
                        echo "<option value='".$row['nama']."' ".$selected.">".$row['nama']."</option>";
                      }
                    echo "</select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Penilai 3</label>
                  <div class='col-sm-9'>
                    <select name='penilai3' id='penilai3' class='form-control'>
                      <option value='' selected></option>";
                      $opts = $DML1->FetchAllData();
                      foreach($opts as $row)
                      {
                        $selected = ($act=='edit'?($curr_data['penilai3']==$row['nama']?'selected':''):'');
                        echo "<option value='".$row['nama']."' ".$selected.">".$row['nama']."</option>";
                      }
                    echo "</select>
                  </div>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Properti <font color='red'>*</font></label>
                  <div class='col-sm-9'>
                    <select name='fk_jenis_objek' id='fk_jenis_objek' onchange=\"get_building_component_value(this.value,document.getElementById('fk_klasifikasi_bangunan').value);\" onblur=\"mix_function1();\" class='form-control' required>
                      <option value='' selected></option>";
                      $opts = $DML2->FetchAllData();                      
                      foreach($opts as $row)
                      {                        
                        $selected = ($act=='edit'?($curr_data['fk_jenis_objek']==$row['id_jenis_objek']?'selected':''):'');
                        echo "<option value='".$row['id_jenis_objek']."' ".$selected.">".$row['jenis_objek']."</option>";
                      }
                    echo "</select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Klasifikasi</label>
                  <div class='col-sm-9'>
                    <select name='fk_klasifikasi_bangunan' id='fk_klasifikasi_bangunan' onchange=\"get_building_component_value(document.getElementById('fk_jenis_objek').value,this.value);\" onblur=\"mix_function1();\" class='form-control' required>
                      <option value='' selected></option>";
                      $opts = $DML3->FetchAllData();                      
                      foreach($opts as $row)
                      {
                        $selected = ($act=='edit'?($curr_data['fk_klasifikasi_bangunan']==$row['id_klasifikasi_bangunan']?'selected':''):'');
                        echo "<option value='".$row['id_klasifikasi_bangunan']."' ".$selected.">".$row['klasifikasi_bangunan']."</option>";
                      }
                    echo "</select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Tgl. Penilaian</label>
                  <div class='col-sm-9'>";
                    $tgl_penilaian = '';
                    if($act == 'edit')
                    {
                      $tgl_penilaian = ($curr_data['tgl_penilaian']!=''?indo_date_format($curr_data['tgl_penilaian'],'shortDate'):'');
                    }
                    echo "<input type='text' name='tgl_penilaian' id='tgl_penilaian' class='form-control datepicker' value='".$tgl_penilaian."' required/>
                  </div>
                </div>
              </div>
          </div>
        </div>
        
        <div class='box-header with-border'>
          <h3 class='box-title'>Lokasi Properti</h3>
          <div class='box-tools pull-right'>        
          </div>
        </div><!-- /.box-header -->
        
        <div class='box-body'>
          <div class='row'>
            <div class='col-md-6'>
              <div class='form-group'>
                <label class='col-sm-3 control-label'>Provinsi (IKK) <font color='red'>*</font></label>
                <div class='col-sm-9'>
                  <select name='provinsi' id='provinsi' class='form-control' onchange=\"get_regencies_list(this.value);mix_function1();\" required>
                    <option value='' selected></option>";
                    $sql = "SELECT a.*,b.indeks FROM ref_provinces as a LEFT JOIN ref_indeks_kemahalan_konstruksi as b ON (a.id=b.fk_propinsi)";
                    $opts = $DML4->fetchData($sql);
                    foreach($opts as $row)
                    {
                      $selected = ($act=='edit'?($curr_data['provinsi']==$row['name']?'selected':''):'');
                      echo "<option value='".$row['id']."_".$row['indeks']."' ".$selected.">".$row['name']." (".$row['indeks'].")</option>";
                    }
                  echo "</select>
                </div>
              </div>
              <div class='form-group'>                  
                <label class='col-sm-3 control-label'>Kota/Kabupaten <font color='red'>*</font></label>
                <div class='col-sm-9'>";
                                
                  echo "<select name='kota' id='kota' class='form-control' onchange=\"get_districts_list(this.value)\" required>";
                    if($act=='add')
                    {
                      echo "<option value=''>- Pilih Provinsi Terlebih Dahulu -</option>";
                    }
                    else
                    {

                      $DML6 = new DML('ref_regencies',$db);
                      $sql = "SELECT * FROM ref_regencies WHERE province_id='".$province_id."'";
                      $opts = $DML6->fetchData($sql);
                      
                      echo "<option value=''></option>";
                      foreach($opts as $row)
                      {
                        $selected = ($curr_data['kota']==$row['name']?'selected':'');
                        echo "<option value='".$row['id']."' ".$selected.">".$row['name']."</option>";
                      }
                    }
                  echo "</select>
                </div>
              </div>
              <div class='form-group'>
                <label class='col-sm-3 control-label'>Kecamatan <font color='red'>*</font></label>
                <div class='col-sm-9'>
                  <select name='kecamatan' id='kecamatan' class='form-control' onchange=\"get_villages_list(this.value)\" required>";
                    if($act=='add')
                    {
                      echo "<option value=''>- Pilih Kota/Kabupaten Terlebih Dahulu -</option>";
                    }
                    else
                    {
                      $DML6 = new DML('ref_districts',$db);
                      $sql = "SELECT * FROM ref_districts WHERE regency_id='".$regency_id."'";
                      $opts = $DML6->fetchData($sql);

                      echo "<option value=''></option>";
                      foreach($opts as $row)
                      {
                        $selected = ($curr_data['kecamatan']==$row['name']?'selected':'');
                        echo "<option value='".$row['id']."' ".$selected.">".$row['name']."</option>";
                      }
                    }
                  echo "</select>
                </div>
              </div>
              <div class='form-group'>
                <label class='col-sm-3 control-label'>Kelurahan <font color='red'>*</font></label>
                <div class='col-sm-9'>
                  <select name='kelurahan' id='kelurahan' class='form-control' required>";
                    if($act=='add')
                    {
                      echo "<option value=''>- Pilih Kecamatan Terlebih Dahulu -</option>";
                    }
                    else
                    {
                      $DML6 = new DML('ref_districts',$db);
                      $sql = "SELECT * FROM ref_villages WHERE district_id='".$district_id."'";
                      $opts = $DML6->fetchData($sql);

                      echo "<option value=''></option>";
                      foreach($opts as $row)
                      {
                        $selected = ($curr_data['kelurahan']==$row['name']?'selected':'');
                        echo "<option value='".$row['id']."' ".$selected.">".$row['name']."</option>";
                      }
                    }
                  echo "
                  </select>
                </div>
              </div>
              <div class='form-group'>
                <label class='col-sm-3 control-label'>Alamat <font color='red'>*</font></label>
                <div class='col-sm-9'>";                  
                  echo "<textarea name='alamat' id='alamat' class='form-control' required>".($act=='edit'?$curr_data['alamat']:'')."</textarea>
                </div>
              </div>
              <div class='form-group'>
                <label class='col-sm-3 control-label'>Lokasi Properti <font color='red'>*</font></label>
                <div class='col-sm-9'>
                  <input type='text' name='nm_perumahan' id='nm_perumahan' class='form-control' value='".($act=='edit'?$curr_data['nm_perumahan']:'')."' required/>
                </div>
              </div>
            </div><!-- /.col -->
            <div class='col-md-6'>                  
              <div class='form-group'>
                <label class='col-sm-3 control-label'>Nama Bang. <font color='red'>*</font></label>
                <div class='col-sm-9'>
                  <input type='text' name='nm_bangunan' id='nm_bangunan' class='form-control' value='".($act=='edit'?$curr_data['nm_bangunan']:'')."' required/>
                </div>
              </div>
              <div class='form-group'>
                <label class='col-sm-3 control-label'>Luas Bang. <font color='red'>*</font></label>
                <div class='col-sm-9'>
                  <input type='text' name='luas_bangunan' id='luas_bangunan' class='form-control' onkeypress=\"return only_number(event,this)\" value='".($act=='edit'?$curr_data['luas_bangunan']:'')."' required/>
                </div>
              </div>            
              <div class='form-group'>
                <label class='col-sm-3 control-label'>Jumlah Lantai <font color='red'>*</font></label>
                <div class='col-sm-9'>
                  <select name='jumlah_lantai' id='jumlah_lantai' class='form-control' onchange=\"mix_function1();\" required>
                    <option value=''></option>";
                    $opts = array('1','2','3','4','>4');
                    foreach($opts as $val)
                    {
                      $selected = ($act=='edit'?($curr_data['jumlah_lantai']==$val?'selected':''):'');
                      echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select>
                </div>
              </div>
              <div class='form-group'>
                <label class='col-sm-3 control-label'>Thn Dibangun <font color='red'>*</font></label>
                <div class='col-sm-9'>
                  <input type='text' name='thn_bangun' id='thn_bangun' class='form-control' onkeypress=\"return only_number(event,this)\" value='".($act=='edit'?$curr_data['thn_bangun']:'')."' required/>
                </div>
              </div>
              <div class='form-group'>
                <label class='col-sm-3 control-label'>Thn Renov <font color='red'>*</font></label>
                <div class='col-sm-9'>
                  <input type='text' name='thn_renov' id='thn_renov' class='form-control' onkeypress=\"return only_number(event,this)\" value='".($act=='edit'?$curr_data['thn_renov']:'')."' required/>
                </div>
              </div>
            </div>
          </div>
        </div>
          
        <div class='box-header with-border'>
          <h3 class='box-title'>Pembiayaan</h3>
          <div class='box-tools pull-right'>            
          </div>
        </div><!-- /.box-header -->
        
          <div class='box-body' style='overflow:auto;'>
            <div class='row'>
              <div class='col-md-6'>
                <table border=1 cellspacing=5 class='tableForm' cellpadding=5>
                  <thead>
                    <tr>
                      <td class='tableHead'>Uraian Pekerjaan</td>
                      <td class='tableHead'>Material</td>
                      <td class='tableHead'>Vol. (%)</td>
                      <td class='tableHead'>Harga Satuan (Rp.000/m<sup>2</sup>)</td>
                    </tr>
                  </thead>
                  <tbody>";
                    $sql = "SELECT * FROM ref_kelompok_komponen_bangunan";
                    $result1 = $db->Execute($sql);
                    if(!$result1)
                      echo $db->ErrorMsg();
                    
                    $no = 0;
                    $n = 0;

                    foreach($building_component_groups as $key => $val)
                    {
                      $no++;
                      
                      echo "<tr>
                      <td rowspan='".$val['mi']."'>
                        ".$val['nm']."
                      </td>";

                      for($i=1;$i<=$val['mi'];$i++)
                      {
                        $n++;

                        $sql = "SELECT * FROM ref_jenis_komponen_bangunan WHERE fk_kelompok_komponen_bangunan='".$val['id']."'";

                        $result2 = $db->Execute($sql);
                        if(!$result2)
                          echo $db->ErrorMsg();

                        $index = $no.$i;

                        if($i>1)
                          echo "<tr>";
                        
                        echo "                      
                        <td>                        
                          <select name='fk_jenis_komponen_bangunan".$index."' id='fk_jenis_komponen_bangunan".$index."' onchange=\"mix_function2('".$index."')\" style='width:100%'>";
                            while($row2 = $result2->FetchRow())
                            {
                              $selected = ($row2['jenis_komponen_bangunan']=='-'?'selected':'');
                              if($act=='edit')
                              {
                                $selected = ($components[$val['id']][$i]['jkb']==$row2['id_jenis_komponen_bangunan']?'selected':'');
                              }
                              echo "<option value='".$row2['id_jenis_komponen_bangunan']."' ".$selected.">".$row2['jenis_komponen_bangunan']."</option>";
                            }
                          echo "
                          </select>
                        </td>
                        <td align='center'>
                          <input type='text' name='volume".$index."' id='volume".$index."' value='".($act=='edit'?$components[$val['id']][$i]['vol']:'100')."' onkeypress=\"return only_number(event,this);\" onkeyup=\"mix_function2('".$index."');\" value='100' size='5' style='text-align:right'/>
                        </td>
                        <td align='center'>
                          <input type='text' name='harga_satuan".$index."' id='harga_satuan".$index."' value='".($act=='edit'?$components[$val['id']][$i]['hgs']:'0.00')."' class='autofill-bg' style='text-align:right'/>
                        </td>
                        
                        <input type='hidden' name='id_perhitunganbkb_komponen".$index."' value='".$components[$val['id']][$i]['id']."'/>
                        <input type='hidden' name='material_ke".$index."' value='".$i."'/>
                        <input type='hidden' name='fk_kelompok_komponen_bangunan".$index."' value='".$val['id']."'/>
                        <input type='hidden' id='index".$n."' name='index".$n."' value='".$index."'/>";

                        if($i==1)
                          echo "</tr>";
                      }
                    }
                  echo "
                      <tr>
                        <td colspan='3' align='right'><b>Total</b></td>
                        <td align='center'>
                          <input type='hidden' id='n_index' name='n_index' value='".$n."'/>
                          <input type='text' name='total_nilai_komponen' id='total_nilai_komponen' class='autofill-bg' value='".($act=='edit'?number_format($curr_data['total_biaya_komponen'],2,'.',','):'0.00')."' style='text-align:right'/>
                        </td>
                      </tr>
                    </tbody>
                </table>  
              </div>
              <div class='col-md-6'>
                <table border=1 cellspacing=5 class='tableForm' cellpadding=5>                
                  <tbody>
                    <tr>
                      <td>Overhead <br />(1.00%-5.00%)</td>
                      <td align='center'>
                        <input type='text' name='overhead_persen' id='overhead_persen' value='".($act=='edit'?$curr_data['overhead_persen']:'0.00')."' onkeypress=\"return only_number(event,this);\" onblur=\"mix_function3();\" style='text-align:right'/>
                        <input type='hidden' id='overhead_persen2' value='".($act=='edit'?$curr_data['overhead_persen']:'0.00')."'/>
                        <input type='hidden' id='min_overhead_persen' value='1'/>
                        <input type='hidden' id='max_overhead_persen' value='5'/>
                      </td>
                      <td align='center'>
                        <input type='text' name='overhead_nilai' id='overhead_nilai' value='".($act=='edit'?number_format($curr_data['overhead_nilai'],2,'.',','):'0.00')."' style='text-align:right' class='autofill-bg'/>
                      </td>
                    </tr>
                    <tr>
                      <td>Fee Kontraktor</td>
                      <td>
                        <select name='fee_kontraktor_persen' id='fee_kontraktor_persen' onchange=\"mix_function4();\" style='width:100%'>";
                          $opts = $DML5->FetchAllData();
                          $i=0;
                          foreach($opts as $row)
                          {
                            $i++;
                            if($act=='add')
                              $selected = ($i==1?'selected':'');
                            else
                              $selected = ($curr_data['fee_kontraktor_persen']==$row['fee']?'selected':'');

                            echo "<option value='".$row['fee']."' ".$selected.">".$row['fee']."</option>";
                          }
                        echo "</select>
                      </td>
                      <td align='center'><input type='text' name='fee_kontraktor_nilai' id='fee_kontraktor_nilai' value='".($act=='edit'?number_format($curr_data['fee_kontraktor_nilai'],2,'.',','):'0.00')."' style='text-align:right' class='autofill-bg'/></td>
                    </tr>
                    <tr>
                      <td>Fee Konsultan <br />(1.5%-10%)</td>
                      <td align='center'>
                        <input type='text' name='fee_konsultan_persen' id='fee_konsultan_persen' value='".($act=='edit'?$curr_data['fee_konsultan_persen']:'0.00')."' onkeypress=\"return only_number(event,this);\" onblur=\"mix_function5();\" style='text-align:right'/>
                        <input type='hidden' id='fee_konsultan_persen2' value='".($act=='edit'?$curr_data['fee_konsultan_persen']:'0.00')."'/>
                        <input type='hidden' id='min_fee_konsultan_persen' value='1.5'/>
                        <input type='hidden' id='max_fee_konsultan_persen' value='10'/>
                      </td>
                      <td align='center'>
                        <input type='text' name='fee_konsultan_nilai' id='fee_konsultan_nilai' value='".($act=='edit'?number_format($curr_data['fee_konsultan_nilai'],2,'.',','):'0.00')."' style='text-align:right' class='autofill-bg'/>
                      </td>
                    </tr>
                    <tr>
                      <td colspan='2'>Biaya IMB</td>                    
                      <td align='center'><input type='text' name='biaya_imb' id='biaya_imb' value='".($act=='edit'?number_format($curr_data['biaya_imb'],2,'.',','):'0.00')."' style='text-align:right' class='autofill-bg'/></td>
                    </tr>
                    <tr>
                      <td colspan='2' align='right'><b>Total Biaya Langsung (Rp. 000/m<sup>2</sup>)</b></td>
                      <td align='center'><input type='text' name='total_biaya_langsung' id='total_biaya_langsung' value='".($act=='edit'?number_format($curr_data['total_biaya_langsung'],2,'.',','):'0.00')."' style='text-align:right' class='autofill-bg'/></td>
                    </tr>
                    <tr>
                      <td colspan='3'><br /></td>                    
                    </tr>
                    <tr>
                      <td colspan='3'><b>Biaya Tidak Langsung</td>                    
                    </tr>
                    <tr>
                      <td>PPN</td>
                      <td align='center'><input type='text' name='ppn_persen' id='ppn_persen' value='".($act=='edit'?$curr_data['ppn_persen']:'0.00')."' onkeypress=\"return only_number(event,this);\" onkeyup=\"mix_function6();\" style='text-align:right'/></td>
                      <td align='center'><input type='text' name='ppn_nilai' id='ppn_nilai' value='".($act=='edit'?number_format($curr_data['ppn_nilai'],2,'.',','):'0.00')."' style='text-align:right' class='autofill-bg'/></td>
                    </tr>
                    <tr>
                      <td>Biaya Lawyer, Akuntan, Penilai, dll (1.5% - 10%)</td>
                      <td align='center'><input type='text' name='biaya_lain_persen' id='biaya_lain_persen' value='".($act=='edit'?$curr_data['biaya_lain_persen']:'0.00')."' onkeypress=\"return only_number(event,this);\" onkeyup=\"mix_function7();\" style='text-align:right'/></td>
                      <td align='center'><input type='text' name='biaya_lain_nilai' id='biaya_lain_nilai' value='".($act=='edit'?number_format($curr_data['biaya_lain_nilai'],2,'.',','):'0.00')."' style='text-align:right' class='autofill-bg'/></td>
                    </tr>
                    <tr>
                      <td>IDC Bunga Konstruksi</td>
                      <td align='center'><input type='text' name='idc_bunga_konstruksi_persen' id='idc_bunga_konstruksi_persen' value='".($act=='edit'?$curr_data['idc_bunga_konstruksi_persen']:'0.00')."' onkeypress=\"return only_number(event,this);\" onkeyup=\"mix_function8();\" style='text-align:right'/></td>
                      <td align='center'><input type='text' name='idc_bunga_konstruksi_nilai' id='idc_bunga_konstruksi_nilai' value='".($act=='edit'?number_format($curr_data['idc_bunga_konstruksi_nilai'],2,'.',','):'0.00')."' style='text-align:right' class='autofill-bg'/></td>
                    </tr>
                    <tr>
                      <td colspan='2' align='right'><b>Total Biaya Tidak Langsung (Rp. 000/m<sup>2</sup>)</b></td>
                      <td align='center'><input type='text' name='total_biaya_tidak_langsung' id='total_biaya_tidak_langsung' value='".($act=='edit'?number_format($curr_data['total_biaya_tidak_langsung'],2,'.',','):'0.00')."' style='text-align:right' class='autofill-bg'/></td>
                    </tr>
                    <tr>
                      <td colspan='2' align='right'><b>Total Biaya Bangunan (Rp. 000/m<sup>2</sup>)</b></td>
                      <td align='center'><input type='text' name='total_biaya_bangunan' id='total_biaya_bangunan' value='".($act=='edit'?number_format($curr_data['total_biaya_bangunan'],2,'.',','):'0.00')."' style='text-align:right' class='autofill-bg'/></td>
                    </tr>
                    <tr>
                      <td colspan='2' align='right'><b>Jumlah</b></td>
                      <td align='center'><input type='text' name='jumlah_biaya_bangunan' id='jumlah_biaya_bangunan' value='".($act=='edit'?number_format((int)$curr_data['total_biaya_bangunan']*1000,2,'.',','):'0.00')."' style='text-align:right' class='autofill-bg'/></td>
                    </tr>
                    <tr>
                      <td colspan='2' align='right'><b>Rounded</b></td>
                      <td align='center'><input type='text' name='rounded' id='rounded' value='".($act=='edit'?number_format($curr_data['rounded']):'0.00')."' style='text-align:right' class='autofill-bg'/></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
              
        <div class='box-footer'>
          <button type='reset' class='btn btn-default'>Batal</button>
          <button type='submit' class='btn btn-info pull-right'>Simpan</button>
        </div><!-- /.box-footer -->
        </form>";    
      echo "</div>";
    }
    else
    {
      echo "    
      <div class='row'>
          <div class='col-md-12'>
              <div class='alert alert-warning' role='alert'>
              <button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
              Data yang ingin diedit tidak ditemukan
              </div>
          </div>
      </div>";   
    }
  }
  else
  {
    echo "    
      <div class='row'>
          <div class='col-md-12'>
              <div class='alert alert-warning' role='alert'>
              <button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
              Anda tidak memiliki akses untuk ".($act=='add'?'menambah':'merubah')." data
              </div>
          </div>
      </div>";
  }
?>