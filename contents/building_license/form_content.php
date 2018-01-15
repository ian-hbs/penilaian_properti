<?php
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/DML.php";
    include_once "../../libraries/global_obj.php";
    include_once "../../helpers/date_helper.php";
    include_once "../../libraries/user_controller.php";

    //instantiate objects
    $uc = new user_controller($db);

    $uc->check_access();
    
    $id_penugasan = $_POST['id_penugasan'];
    $menu_id = $_POST['menu_id'];
    $fn = $_POST['fn'];
    $kunci_pencarian = $_POST['kunci_pencarian'];
	
    $DML = new DML('perijinan_bangunan',$db);
    $global = new global_obj($db);

    $sql = "SELECT count(1) as tot_rec FROM perijinan_bangunan WHERE fk_penugasan='".$id_penugasan."'";
    $tot_rec = $db->getOne($sql);
    
    $act = ($tot_rec==0?'add':'edit');
    $act_lbl = ($act=='add'?'menambah':'memperbaharui');

    $id_name = 'fk_penugasan';
    $id_value = ($act=='edit'?$id_penugasan:'');
    $arr_field = array('no_imb','tanggal_imb','arsitek_bangunan','tahun_pembuatan','penggunaan','tahun_renovasi','luas_imb','keterangan');
    $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'building-license-form';
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
                    <label class='col-sm-5 control-label'>No. IMB <font color='red'>*</font></label>
                    <div class='col-sm-7'>
                        <input type='text' name='no_imb' id='no_imb' class='form-control' value=\"".$curr_data['no_imb']."\" required/>
                    </div>
                </div>
                <div class='form-group'>                  
                    <label class='col-sm-5 control-label'>Tgl. IMB <font color='red'>*</font></label>
                    <div class='col-sm-7'>
                        <input type='text' name='tanggal_imb' id='tanggal_imb' class='form-control datepicker' value='".($act=='add'?$curr_data['tanggal_imb']:indo_date_format($curr_data['tanggal_imb'],'shortDate'))."' required/>
                    </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Arsitek Bangunan <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <input type='text' name='arsitek_bangunan' id='arsitek_bangunan' class='form-control' value=\"".$curr_data['arsitek_bangunan']."\" required/>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Tahun Pembuatan <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <input type='text' name='tahun_pembuatan' id='tahun_pembuatan' class='form-control' value='".$curr_data['tahun_pembuatan']."' maxlength=4 onkeypress=\"return only_number(event,this)\" required/>
                  </div>
                </div>
            </div><!-- /.col -->
            <div class='col-md-6'>                  
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Penggunaan <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <input type='text' name='penggunaan' id='penggunaan' class='form-control' value=\"".$curr_data['penggunaan']."\" required/>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Tahun Renovasi</label>
                  <div class='col-sm-7'>
                    <input type='text' name='tahun_renovasi' id='tahun_renovasi' class='form-control' value='".$curr_data['tahun_renovasi']."' maxlength=4 onkeypress=\"return only_number(event,this)\"/>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Luas IMB</label>
                  <div class='col-sm-7'>
                    <input type='text' name='luas_imb' id='luas_imb' class='form-control' value='".$curr_data['luas_imb']."' onkeypress=\"return only_number(event,this)\"/>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Keterangan</label>
                  <div class='col-sm-7'>
                    <textarea name='keterangan' id='keterangan' class='form-control'>".$curr_data['keterangan']."</textarea>
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