<?php
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../config/app_param.php";
    include_once "../../libraries/DML.php";

	$act=$_POST['act'];
    $menu_id=$_POST['menu_id'];
    $fn=$_POST['fn'];
    $act_lbl=($act=='add'?'menambah':'memperbaharui');
	
    $DML = new DML('user_types',$db);

    $_SYSTEM_PARAMS = $_APP_PARAM['system_params'];

    $id_name = 'type_id';
    $id_value = ($act=='edit'?$_POST['id']:'');
    $arr_field = array('name');
    $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);
    $id_form = 'user-type-form';
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
	<input type="hidden" name="id" value="<?=$id_value?>"/>
    <input type="hidden" name="act" value="<?=$act?>"/>
    <input type="hidden" name="menu_id" value="<?=$menu_id?>"/>
    <input type="hidden" name="fn" value="<?=$fn?>"/>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name">Jenis User <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="name" name="name" value="<?=$curr_data['name']?>" class="form-control col-md-7 col-xs-12" required>
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