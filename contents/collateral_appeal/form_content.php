<?php
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/DML.php";
    include_once "../../libraries/global_obj.php";
    include_once "../../libraries/user_controller.php";
    include_once "../../helpers/date_helper.php";

    //instantiate objects
    $uc = new user_controller($db);

    $uc->check_access();
    
    $id_penugasan = $_POST['id_penugasan'];
    $menu_id = $_POST['menu_id'];
    $fn = $_POST['fn'];
    $kunci_pencarian = $_POST['kunci_pencarian'];
	
    $DML = new DML('daya_tarik_agunan',$db);
    $global = new global_obj($db);

    $sql = "SELECT count(1) as tot_rec FROM daya_tarik_agunan WHERE fk_penugasan='".$id_penugasan."'";
    $tot_rec = $db->getOne($sql);
    
    $act = ($tot_rec==0?'add':'edit');
    $act_lbl = ($act=='add'?'menambah':'memperbaharui');

    $id_name = 'fk_penugasan';
    $id_value = ($act=='edit'?$id_penugasan:'');
    $arr_field = array('sarana_listrik','sarana_telepon','sarana_untuk_olahraga','sarana_jalan_lingkungan_perumahan','sarana_fasos_fasum',
                       'sarana_air','sarana_taman_lingkungan','sarana_pengelolaan_lingkungan','sarana_jumlah_akses_jalan_ke_perumahan','bentuk_tanah');
    $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'collateral-appeal-form';
    $checked1 = '';
    $checked2 = '';
    $checked3 = '';
    $checked4 = '';
    $checked5 = '';
    if($act=='edit')
    {
      $x_sarana_fasos_fasum = explode('_',$curr_data['sarana_fasos_fasum']);
      $arr = array('Fasilitas kesehatan (Poliklinik)','Pasar','Rumah ibadah','Sarana hiburan/rekreasi','Sarana pendidikan');
            
      if(in_array($arr[0],$x_sarana_fasos_fasum))
        $checked1='checked';
      if(in_array($arr[1],$x_sarana_fasos_fasum))
        $checked2='checked';
      if(in_array($arr[2],$x_sarana_fasos_fasum))
        $checked3='checked';
      if(in_array($arr[3],$x_sarana_fasos_fasum))
        $checked4='checked';
      if(in_array($arr[4],$x_sarana_fasos_fasum))
        $checked5='checked';              
    }
?>

<script type="text/javascript">
    var form_id = '<?php echo $form_id;?>';
    var $input_form = $('#'+form_id);
    var stat = $input_form.validate();
    var act_lbl = '<?php echo $act_lbl;?>';

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

<?php

    $pd = $global->get_property_detail($id_penugasan);
    $pd['tgl_survei'] = indo_date_format($pd['tgl_survei'],'longDate');
    
    $global->print_property_detail($pd);
    
    echo "
    <form class='form-horizontal' id='".$form_id."' method='POST' action='contents/".$fn."/manipulating.php'>
    <input type='hidden' name='fk_penugasan' value='".$id_penugasan."'/>
    <input type='hidden' name='menu_id' value='".$menu_id."'/>
    <input type='hidden' name='fn' value='".$fn."'/>
    <input type='hidden' name='act' value='".$act."'/>
    <input type='hidden' name='kunci_pencarian' value='".$kunci_pencarian."'/>
        <div class='row'>
            <div class='col-md-6'>
                <div class='form-group'>
                    <label class='col-sm-5 control-label'>Sarana Listrik <font color='red'>*</font></label>
                    <div class='col-sm-7'>
                        <select name='sarana_listrik' id='sarana_listrik' class='form-control' required>
                        <option value='' selected></option>";              
                        $arr_opt = array('Ada','Tidak ada');
                        foreach($arr_opt as $key=>$val)
                        {
                            $selected = ($curr_data['sarana_listrik']==$val?'selected':'');
                            echo "<option value='".$val."' ".$selected.">".$val."</option>";
                        }
                      echo "</select>
                    </div>
                </div>
                <div class='form-group'>                  
                  <label class='col-sm-5 control-label'>Sarana Telepon <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                  <select name='sarana_telepon' id='sarana_telepon' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('Ada','Tidak ada');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['sarana_telepon']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Sarana Untuk Olahraga <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='sarana_untuk_olahraga' id='sarana_untuk_olahraga' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('Lengkap (Semacam Sport Center/Indoor Sport)','Sederhana (Outdoor bulu tangkis)','Tidak ada');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['sarana_untuk_olahraga']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Sarana Jalan Lingkungan Perumahan <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='sarana_jalan_lingkungan_perumahan' id='sarana_jalan_lingkungan_perumahan' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('Aspal','Makadam/Pengerasan','Tanah dan sejenisnya');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['sarana_jalan_lingkungan_perumahan']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Sarana Fasos Fasum <font color='red'>*</font></label>
                  <div class='col-sm-7'>                    
                    <input type='checkbox' name='sarana_fasos_fasum1' id='sarana_fasos_fasum1' value='Fasilitas kesehatan (Poliklinik)' ".$checked1."/> Fasilitas kesehatan (Poliklinik)<br />
                    <input type='checkbox' name='sarana_fasos_fasum2' id='sarana_fasos_fasum2' value='Pasar' ".$checked2."/> Pasar<br />
                    <input type='checkbox' name='sarana_fasos_fasum3' id='sarana_fasos_fasum3' value='Rumah ibadah' ".$checked3."/> Rumah ibadah<br />
                    <input type='checkbox' name='sarana_fasos_fasum4' id='sarana_fasos_fasum4' value='Sarana hiburan/rekreasi' ".$checked4."/> Sarana hiburan/rekreasi<br />
                    <input type='checkbox' name='sarana_fasos_fasum5' id='sarana_fasus_fasos5' value='Sarana pendidikan' ".$checked5."/> Sarana pendidikan
                  </div>
                </div>                
            </div><!-- /.col -->
            <div class='col-md-6'>                  
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Sarana Air <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='sarana_air' id='sarana_air' class='form-control' required>
                      <option value='' selected></option>";              
                        $arr_opt = array('Air Tanah','PDAM');
                        foreach($arr_opt as $key=>$val)
                        {
                            $selected = ($curr_data['sarana_air']==$val?'selected':'');
                            echo "<option value='".$val."' ".$selected.">".$val."</option>";
                        }
                    echo "
                    </select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Sarana Taman Lingkungan <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='sarana_taman_lingkungan' id='sarana_taman_lingkungan' class='form-control' required>
                      <option value='' selected></option>";              
                        $arr_opt = array('Ada','Tidak ada');
                        foreach($arr_opt as $key=>$val)
                        {
                            $selected = ($curr_data['sarana_taman_lingkungan']==$val?'selected':'');
                            echo "<option value='".$val."' ".$selected.">".$val."</option>";
                        }
                    echo "
                    </select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Sarana Pengelolaan Lingkungan <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='sarana_pengelolaan_lingkungan' id='sarana_pengelolaan_lingkungan' class='form-control' required>
                      <option value='' selected></option>";              
                        $arr_opt = array('Keamanan & kebersihan baik','Keamanan & kebersihan minim','Tidak ada');
                        foreach($arr_opt as $key=>$val)
                        {
                            $selected = ($curr_data['sarana_pengelolaan_lingkungan']==$val?'selected':'');
                            echo "<option value='".$val."' ".$selected.">".$val."</option>";
                        }
                    echo "
                    </select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Sarana Jumlah Akses Jalan Ke Perumahan <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='sarana_jumlah_akses_jalan_ke_perumahan' id='sarana_jumlah_akses_jalan_ke_perumahan' class='form-control' required>
                      <option value='' selected></option>";              
                        $arr_opt = array('Hanya 1 akses jalan','Lebih dari 1 akses jalan','Lebih dari 3 akses jalan');
                        foreach($arr_opt as $key=>$val)
                        {
                            $selected = ($curr_data['sarana_jumlah_akses_jalan_ke_perumahan']==$val?'selected':'');
                            echo "<option value='".$val."' ".$selected.">".$val."</option>";
                        }
                    echo "
                    </select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Bentuk Tanah <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='bentuk_tanah' id='bentuk_tanah' class='form-control' required>
                      <option value='' selected></option>";              
                        $arr_opt = array('Beraturan','Tidak beraturan','Trapesium','Letter L');
                        foreach($arr_opt as $key=>$val)
                        {
                            $selected = ($curr_data['bentuk_tanah']==$val?'selected':'');
                            echo "<option value='".$val."' ".$selected.">".$val."</option>";
                        }
                    echo "
                    </select>
                  </div>
                </div>
            </div>
        </div>
        <div class='ln_solid'></div>
        <div class='form-group'>
            <div class='col-md-12 col-sm-12 col-xs-12' align='center'>
                <button type='button' class='btn btn-danger' id='close-modal-form' data-dismiss='modal'>Batal</button>
                <button type='submit' class='btn btn-success'>Simpan</button>
            </div>
        </div>
    </form>";
?>