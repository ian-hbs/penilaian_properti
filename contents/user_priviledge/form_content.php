<?php
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../config/app_param.php";
    include_once "../../libraries/DML.php";

	$act=$_POST['act'];
    $menu_id=$_POST['menu_id'];
    $fn=$_POST['fn'];
    $act_lbl=($act=='add'?'menambah':'memperbaharui');
	
    $DML1 = new DML('user_priviledges',$db);
    $DML2 = new DML('user_types',$db);
    $DML3 = new DML('menus',$db);


    $_SYSTEM_PARAMS = $_APP_PARAM['system_params'];

    $id_name = 'priviledge_id';
    $id_value = ($act=='edit'?$_POST['id']:'');
    $arr_field = array('type_fk','menu_fk','read_priv','add_priv','update_priv','delete_priv');
    $curr_data = $DML1->getCurrentData($act,$arr_field,$id_name,$id_value);
    $id_form = 'user-priviledge-form';
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
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="type_fk">Jenis User <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <select name="type_fk" id="type_fk" class="form-control" required>
                <option value=""></option>
                <?php
                    $data = $DML2->FetchAllData();
                    foreach($data as $row)
                    {
                        $selected = ($curr_data['type_fk']==$row['type_id']?'selected':'');
                        echo "<option value='".$row['type_id']."' ".$selected.">".$row['name']."</option>";
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="menu_fk">Menu <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <select name="menu_fk" id="menu_fk" class="form-control" required>
                <option value=""></option>
                <?php
                    $data = $DML3->FetchAllData();
                    foreach($data as $row)
                    {
                        $selected = ($curr_data['menu_fk']==$row['menu_id']?'selected':'');
                        echo "<option value='".$row['menu_id']."' ".$selected.">".$row['title']."</option>";
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="priviledges">Hak Akses <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <?php
                $checked1 = ($curr_data['read_priv']=='1'?'checked':'');
                $checked2 = ($curr_data['add_priv']=='1'?'checked':'');
                $checked3 = ($curr_data['update_priv']=='1'?'checked':'');
                $checked4 = ($curr_data['delete_priv']=='1'?'checked':'');
                echo "<input type='checkbox' name='read_priv' id='read_priv' value='1' ".$checked1."/>Read&nbsp;";
                echo "<input type='checkbox' name='add_priv' id='add_priv' value='1' ".$checked2."/>Add&nbsp;";
                echo "<input type='checkbox' name='update_priv' id='update_priv' value='1' ".$checked3."/>Update&nbsp;";
                echo "<input type='checkbox' name='delete_priv' id='delete_priv' value='1' ".$checked4."/>Delete";
            ?>
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