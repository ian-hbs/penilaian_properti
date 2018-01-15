<?php    
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/DML.php";
    include_once "../../libraries/user_controller.php"; 

    $DML = new DML('catatan_penilai',$db);    
    
    $id_penugasan = $_POST['id_penugasan'];
    $kunci_pencarian = $_POST['kunci_pencarian'];
    $act = $_POST['act'];
    $menu_id = $_POST['menu_id'];
    $fn = $_POST['fn'];

    $id_name = 'id_catatan_penilai';
    $id_value = ($act=='edit'?$_POST['id']:'');
    $arr_field = array('catatan');
    $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'appraiser-notes-form';
    $act_lbl=($act=='add'?'menambah':'memperbaharui');
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
                var content = new Array('#list-of-data2','#list-of-data1');
                var plugin_datatable = new Array(false,true);

                ajax_manipulate.set_plugin_datatable(plugin_datatable)
                               .set_content(content)                               
                               .set_loading('#preloadAnimation')
                               .enable_pnotify()
                               .set_close_modal('')
                               .set_form($input_form)
                               .submit_ajax(act_lbl,1);
                $('#form-content').hide();
                return false;
            }
        });

        
	</script>

    <form id="<?=$form_id?>" class="form-horizontal form-label-left" method="POST" action="contents/<?=$fn?>/manipulating.php">
        <input type="hidden" name="id" value="<?=$id_value?>"/>
        <input type="hidden" name="act" value="<?=$act?>"/>
        <input type="hidden" name="menu_id" value="<?=$menu_id?>"/>
        <input type="hidden" name="fn" value="<?=$fn?>"/>
        <input type="hidden" name="fk_penugasan" value="<?=$id_penugasan?>"/>
        <input type="hidden" name="kunci_pencarian" value="<?=$kunci_pencarian?>"/>
        
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_urut">Catatan <font color="red">*</font></label>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <textarea name="catatan" id="catatan" class="form-control"><?=$curr_data['catatan']?></textarea>
            </div>
        </div>
        <div class="ln_solid"></div>
        <div class="form-group">
            <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-3">
                <button type="button" class="btn btn-danger" onclick="close_form();">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </div>
    </form>
