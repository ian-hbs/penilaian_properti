<?php
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../config/app_param.php";
    include_once "../../libraries/DML.php";
    include_once "../../libraries/user_controller.php";
    
    //instantiate objects
    $uc = new user_controller($db);

    $uc->check_access();
    
	$act=$_POST['act'];
    $menu_id=$_POST['menu_id'];
    $fn=$_POST['fn'];
    $act_lbl=($act=='add'?'menambah':'memperbaharui');
	
    $DML1 = new DML('ref_jenis_komponen_bangunan',$db);    
    $DML2 = new DML('ref_kelompok_komponen_bangunan',$db);    

    $id_name = 'id_jenis_komponen_bangunan';
    $id_value = ($act=='edit'?$_POST['id']:'');
    $arr_field = array('fk_kelompok_komponen_bangunan','jenis_komponen_bangunan');
    $curr_data = $DML1->getCurrentData($act,$arr_field,$id_name,$id_value);
    $id_form = 'building-components-variety-form';
?>

<script type="text/javascript">
    var id_form = '<?php echo $id_form;?>';
    var $form = $('#'+id_form);
    var stat = $form.validate();
    var act_lbl = '<?php echo $act_lbl;?>';

    $form.submit(function(){
        if(stat.checkForm())
        {
            ajax_manipulate.set_plugin_datatable(true)
                           .set_content('#list-of-data')
                           .set_loading('#preloadAnimation')
                           .set_close_modal('#close-modal-form')
                           .set_form($form)
                           .submit_ajax(act_lbl);
            $('#close-modal-form').click();
            return false;
        }
    });

	</script>

<form id="<?=$id_form?>" class="form-horizontal form-label-left" method="POST" action="contents/<?=$fn?>/manipulating.php">
	<input type="hidden" name="id_" value="<?=$id_value?>"/>
    <input type="hidden" name="act" value="<?=$act?>"/>
    <input type="hidden" name="menu_id" value="<?=$menu_id?>"/>
    <input type="hidden" name="fn" value="<?=$fn?>"/>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="<?=$id_name?>">ID Jenis <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="<?=$id_name?>" name="<?=$id_name?>" value="<?=$id_value?>" class="form-control col-md-7 col-xs-12" maxlength=10 onkeypress="return only_number(event,this)" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="fk_kelompok_komponen_bangunan">Kelompok Komponen<font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <select name="fk_kelompok_komponen_bangunan" id="fk_kelompok_komponen_bangunan" class="form-control" required>
                <option value="" selected></option>
                <?php
                    $opts = $DML2->FetchAllData();
                    foreach($opts as $row)
                    {
                        $selected = ($curr_data['fk_kelompok_komponen_bangunan']==$row['id_kelompok_komponen_bangunan']?'selected':'');
                        echo "<option value='".$row['id_kelompok_komponen_bangunan']."' ".$selected.">".$row['kelompok_komponen_bangunan']."</option>";
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="jenis_komponen_bangunan">Nama Jenis <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="jenis_komponen_bangunan" name="jenis_komponen_bangunan" value="<?=$curr_data['jenis_komponen_bangunan']?>" class="form-control col-md-7 col-xs-12" required>
        </div>
    </div>
    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-4">
            <button type="button" class="btn btn-danger" id="close-modal-form" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </div>
</form>