<?php    
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/DML.php";
    include_once "../../libraries/user_controller.php"; 

    //instantiate objects
    $DML1 = new DML('objek_pembanding',$db);
    $DML2 = new DML('penugasan',$db);
    $DML3 = new DML('ref_jenis_objek',$db);    
        
    $id_penugasan = $_POST['id_penugasan'];
    $kunci_pencarian = $_POST['kunci_pencarian'];
    $act = $_POST['act'];
    $menu_id = $_POST['menu_id'];
    $fn = $_POST['fn'];

    $id_name = 'id_objek_pembanding';
    $id_value = ($act=='edit'?$_POST['id']:'');

    $arr_field = array('no_urut','alamat','pemberi_data','status','no_tlp','fk_jenis_objek','jarak_dari_properti');
    $curr_data = $DML1->getCurrentData($act,$arr_field,$id_name,$id_value);

    $no_urut = 1;
    $sql = "SELECT id_objek_pembanding FROM objek_pembanding WHERE(fk_penugasan='".$id_penugasan."')";
    $result = $db->Execute($sql);
    if(!$result)
        echo $db->ErrorMsg();

    $no_urut += $result->RecordCount();

    $form_id = 'comparative-land-data-form';
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
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_urut">No. Urut <font color="red">*</font></label>
            <div class="col-md-2 col-sm-2 col-xs-12">
                <input style="text-align:right" type="text" id="no_urut" name="no_urut" value="<?=($act=='add'?$no_urut:$curr_data['no_urut'])?>" onkeypress="return only_number(event,this);" class="form-control readonly-bg" required readonly/>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat">Alamat</label>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <textarea id="alamat" name="alamat" class="form-control"><?=$curr_data['alamat']?></textarea>                
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pemberi_data">Pemberi Data <font color="red">*</font></label>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" id="pemberi_data" name="pemberi_data" value="<?=$curr_data['pemberi_data']?>" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status Pemberi Data <font color="red">*</font></label>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" id="status" name="status" value="<?=$curr_data['status']?>" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_tlp">No. Telepon <font color="red">*</font></label>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" id="no_tlp" name="no_tlp" value="<?=$curr_data['no_tlp']?>" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fk_klasifikasi1">Jenis Objek <font color="red">*</font></label>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <select name="fk_jenis_objek" id="fk_jenis_objek" class="form-control" required>
                    <option value="" selected></option>
                    <?php
                        $data = $DML3->fetchAllData();
                        foreach($data as $row)
                        {
                            $selected = ($act=='edit'?($curr_data['fk_jenis_objek']==$row['id_jenis_objek']?'selected':''):'');
                            echo "<option value='".$row['id_jenis_objek']."' ".$selected.">".$row['id_jenis_objek']." - ".$row['jenis_objek']."</option>";
                        }

                    ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jarak_dari_properti">Jarak Dari Properti<font color="red">*</font></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="jarak_dari_properti" name="jarak_dari_properti" value="<?=$curr_data['jarak_dari_properti']?>" class="form-control" required>
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
