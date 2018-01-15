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
	
    $DML = new DML('pertumbuhan_agunan',$db);
    $global = new global_obj($db);

    $sql = "SELECT count(1) as tot_rec FROM pertumbuhan_agunan WHERE fk_penugasan='".$id_penugasan."'";
    $tot_rec = $db->getOne($sql);
    
    $act = ($tot_rec==0?'add':'edit');
    $act_lbl = ($act=='add'?'menambah':'memperbaharui');

    $id_name = 'fk_penugasan';
    $id_value = ($act=='edit'?$id_penugasan:'');
    $arr_field = array('kecepatan_pertambahan_nilai','kondisi_wilayah_agunan');
    $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'collateral-growth-form';
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
            <div class='col-md-12'>
                <div class='form-group'>
                    <label class='col-sm-4 control-label'>Kecepatan Pertambahan Nilai <font color='red'>*</font></label>
                    <div class='col-sm-6'>
                        <select name='kecepatan_pertambahan_nilai' id='kecepatan_pertambahan_nilai' class='form-control' required>
                        <option value='' selected></option>";
                        $arr_opt = array('Sangat tinggi','Rata-rata','Tidak ada pertumbuhan','Penurunan nilai');
                        foreach($arr_opt as $key=>$val)
                        {
                            $selected = ($curr_data['kecepatan_pertambahan_nilai']==$val?'selected':'');
                            echo "<option value='".$val."' ".$selected.">".$val."</option>";
                        }
                      echo "</select>
                    </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-4 control-label'>Kondisi Wilayah Agunan <font color='red'>*</font></label>
                  <div class='col-sm-6'>
                    <select name='kondisi_wilayah_agunan' id='kondisi_wilayah_agunan' class='form-control' required>
                      <option value='' selected></option>";              
                        $arr_opt = array('Sedang berkembang','Akan berkembang dalam jangka pendek','Mapan',
                                         'Tidak berkembang','Terpencil');
                        foreach($arr_opt as $key=>$val)
                        {
                            $selected = ($curr_data['kondisi_wilayah_agunan']==$val?'selected':'');
                            echo "<option value='".$val."' ".$selected.">".$val."</option>";
                        }
                    echo "
                    </select>
                  </div>
                </div>
            </div><!-- /.col -->            
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