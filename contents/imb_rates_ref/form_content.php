<?php
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../config/app_param.php";
    include_once "../../libraries/DML.php";

	$act=$_POST['act'];
    $menu_id=$_POST['menu_id'];
    $fn=$_POST['fn'];
    $act_lbl=($act=='add'?'menambah':'memperbaharui');
	
    $DML1 = new DML('ref_tarif_imb',$db);
    $DML2 = new DML('ref_jenis_objek',$db);

    $_SYSTEM_PARAMS = $_APP_PARAM['system_params'];

    $id_name = 'id_tarif_imb';
    $id_value = ($act=='edit'?$_POST['id']:'');
    $arr_field = array('fk_jenis_objek','nilai_1lantai','nilai_2lantai','nilai_3lantai','nilai_4lantai','nilai_nlantai');
    $curr_data = $DML1->getCurrentData($act,$arr_field,$id_name,$id_value);
    $id_form = 'imb-rates-form';
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
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="tipe_komponen_bangunan">Jenis Objek<font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <select name="fk_jenis_objek" id="fk_jenis_objek" class="form-control" required>
                <option value="" selected></option>
                <?php
                    $opts = $DML2->FetchAllData();
                    foreach($opts as $row)
                    {
                        $selected = ($curr_data['fk_jenis_objek']==$row['id_jenis_objek']?'selected':'');
                        echo "<option value='".$row['id_jenis_objek']."' ".$selected.">".$row['jenis_objek']."</option>";
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="nilai_1lantai">Tarif 1 Lantai <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="nilai_1lantai" name="nilai_1lantai" value="<?=$curr_data['nilai_1lantai']?>" class="form-control col-md-7 col-xs-12"  onkeypress="return only_number(event,this)" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="nilai_2lantai">Tarif 2 Lantai <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="nilai_2lantai" name="nilai_2lantai" value="<?=$curr_data['nilai_2lantai']?>" class="form-control col-md-7 col-xs-12"  onkeypress="return only_number(event,this)" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="nilai_3lantai">Tarif 3 Lantai <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="nilai_3lantai" name="nilai_3lantai" value="<?=$curr_data['nilai_3lantai']?>" class="form-control col-md-7 col-xs-12"  onkeypress="return only_number(event,this)" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="nilai_4lantai">Tarif 4 Lantai <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="nilai_4lantai" name="nilai_4lantai" value="<?=$curr_data['nilai_4lantai']?>" class="form-control col-md-7 col-xs-12"  onkeypress="return only_number(event,this)" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="nilai_nlantai">Tarif >4 Lantai <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="nilai_nlantai" name="nilai_nlantai" value="<?=$curr_data['nilai_nlantai']?>" class="form-control col-md-7 col-xs-12"  onkeypress="return only_number(event,this)" required>
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