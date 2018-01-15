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
	
    $DML = new DML('sarana_bangunan',$db);
    $global = new global_obj($db);

    $sql = "SELECT count(1) as tot_rec FROM sarana_bangunan WHERE fk_penugasan='".$id_penugasan."'";
    $tot_rec = $db->getOne($sql);
    
    $act = ($tot_rec==0?'add':'edit');
    $act_lbl = ($act=='add'?'menambah':'memperbaharui');

    $id_name = 'fk_penugasan';
    $id_value = ($act=='edit'?$id_penugasan:'');
    $arr_field = array('listrik','daya_listrik','air_bersih','bak_sampah','bak_sampah_dikelola_oleh','telepon','no_telepon');
    $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);
    echo $curr_data['bak_sampah_dikelola_oleh'];
    $form_id = 'building-means-form';
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
                    <label class='col-sm-5 control-label'>Listrik <font color='red'>*</font></label>
                    <div class='col-sm-7'>
                        <select name='listrik' id='listrik' class='form-control' required>
                        <option value='' selected></option>";              
                        $arr_opt = array('Ada','Tidak ada');
                        foreach($arr_opt as $key=>$val)
                        {
                            $selected = ($curr_data['listrik']==$val?'selected':'');
                            echo "<option value='".$val."' ".$selected.">".$val."</option>";
                        }
                      echo "</select>
                    </div>
                </div>
                <div class='form-group'>                  
                  <label class='col-sm-5 control-label'>Daya Listrik <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                  <select name='daya_listrik' id='daya_listrik' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('900 Watt','1300 Watt','2200 Watt','3300 Watt','4400 Watt','>5500 Watt');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['daya_listrik']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Air Bersih <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='air_bersih' id='air_bersih' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('PDAM','Jetpump','Sumur pantek','Sumur gali');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['air_bersih']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select>
                  </div>
                </div>                
                
            </div><!-- /.col -->
            <div class='col-md-6'>                  
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Bak Sampah <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='bak_sampah' id='bak_sampah' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('Ada','Tidak ada');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['bak_sampah']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Bak Sampah Dikelola Oleh</label>
                  <div class='col-sm-7'>
                    <input type='text' name='bak_sampah_dikelola_oleh' id='bak_sampah_dikelola_oleh' class='form-control' value=\"".$curr_data['bak_sampah_dikelola_oleh']."\" />
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Telepon <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='telepon' id='telepon' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('Ada','Tidak ada');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['telepon']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>No. Telepon</label>
                  <div class='col-sm-7'>
                    <input type='text' name='no_telepon' id='no_telepon' class='form-control' value=\"".$curr_data['no_telepon']."\" />
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