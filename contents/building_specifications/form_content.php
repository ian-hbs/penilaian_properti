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
	
    $DML = new DML('spesifikasi_bangunan',$db);
    $global = new global_obj($db);

    $sql = "SELECT count(1) as tot_rec FROM spesifikasi_bangunan WHERE fk_penugasan='".$id_penugasan."'";
    $tot_rec = $db->getOne($sql);
    
    $act = ($tot_rec==0?'add':'edit');
    $act_lbl = ($act=='add'?'menambah':'memperbaharui');

    $id_name = 'fk_penugasan';
    $id_value = ($act=='edit'?$id_penugasan:'');
    $arr_field = array('pondasi','dinding','lantai','dinding_dalam','dinding_luar','kusen','atap','pagar');
    $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'building-specifications-form';
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
                    <label class='col-sm-5 control-label'>Pondasi <font color='red'>*</font></label>
                    <div class='col-sm-7'>
                        <select name='pondasi' id='pondasi' class='form-control' required>
                        <option value='' selected></option>";              
                        $arr_opt = array('Mini pile','Beton bertulang','Batu kali','Rolaag Bata','Batako');
                        foreach($arr_opt as $key=>$val)
                        {
                            $selected = ($curr_data['pondasi']==$val?'selected':'');
                            echo "<option value='".$val."' ".$selected.">".$val."</option>";
                        }
                      echo "</select>
                    </div>
                </div>
                <div class='form-group'>                  
                  <label class='col-sm-5 control-label'>Dinding <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                  <select name='dinding' id='dinding' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('Bata ringan aerasi diplester','Batubata diplester','Batako diplester','Bata tidak diplester','Batako tidak diplester','Papan/kayu/triplek');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['dinding']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Lantai <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='lantai' id='lantai' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('Marmer','Granit','Keramik 30 x 30','Tegel','Ubin Teraso','Semen/tajur');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['lantai']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Dinding Dalam <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='dinding_dalam' id='dinding_dalam' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('Cat halus','Cat sedang','Cat kasar');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['dinding_dalam']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select>
                  </div>
                </div>
                
            </div><!-- /.col -->
            <div class='col-md-6'>                  
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Dinding Luar <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='dinding_luar' id='dinding_luar' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('Tanpa cat','Cat halus','Cat sedang','Cat kasar','Tanpa cat');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['dinding_luar']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Kusen <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='kusen' id='kusen' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('Alumunium','Pitur','Cat halus','Cat sedang','Cat kasar','Kayu meranti');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['kusen']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Atap <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='atap' id='atap' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('Genteng keramik','Genteng beton','Dak beton','Asbes','Seng','Lainnya');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['atap']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Pagar <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='pagar' id='pagar' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('Keliling','Depan saja','Samping','Tanpa pagar');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['pagar']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select>
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