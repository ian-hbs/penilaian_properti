<?php
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../config/app_param.php";
    include_once "../../libraries/DML.php";

	$act=$_POST['act'];
    $menu_id=$_POST['menu_id'];
    $fn=$_POST['fn'];
    $kunci_pencarian1=$_POST['kunci_pencarian1'];
    $kunci_pencarian2=$_POST['kunci_pencarian2'];
    $kunci_pencarian3=$_POST['kunci_pencarian3'];
    $act_lbl=($act=='add'?'menambah':'memperbaharui');
	
    $DML1 = new DML('ref_nilai_komponen_bangunan',$db);
    $DML2 = new DML('ref_jenis_objek',$db);
    $DML3 = new DML('ref_klasifikasi_bangunan',$db);
    $DML4 = new DML('ref_jenis_komponen_bangunan',$db);    

    $_SYSTEM_PARAMS = $_APP_PARAM['system_params'];

    $id_name = 'id_nilai_komponen_bangunan';
    $id_value = ($act=='edit'?$_POST['id']:'');
    $arr_field = array('fk_jenis_objek','fk_jenis_komponen_bangunan','fk_klasifikasi_bangunan','nilai_1lantai','nilai_2lantai','nilai_3lantai','nilai_4lantai','nilai_nlantai');
    $curr_data = $DML1->getCurrentData($act,$arr_field,$id_name,$id_value);
    $id_form = 'building-components-value-form';
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
    <input type="hidden" name="kunci_pencarian1" value="<?=$kunci_pencarian1?>"/>
    <input type="hidden" name="kunci_pencarian2" value="<?=$kunci_pencarian2?>"/>
    <input type="hidden" name="kunci_pencarian3" value="<?=$kunci_pencarian3?>"/>
    
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="fk_jenis_objek_lbl">Jenis Objek <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <select name="fk_jenis_objek_lbl" id="fk_jenis_objek_lbl" class="form-control" required disabled>
                <option value="" selected></option>
                <?php
                    $opts = $DML2->FetchAllData();
                    foreach($opts as $row)
                    {
                        $selected = ($kunci_pencarian1==$row['id_jenis_objek']?'selected':'');
                        echo "<option value='".$row['id_jenis_objek']."' ".$selected.">".$row['jenis_objek']."</option>";
                    }
                ?>
            </select>
            <input type="hidden" name="fk_jenis_objek" value="<?=$kunci_pencarian1;?>"/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="fk_klasifikasi_bangunan_lbl">Klasifikasi Bangunan <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <select name="fk_klasifikasi_bangunan_lbl" id="fk_klasifikasi_bangunan_lbl" class="form-control" required disabled>
                <option value="" selected></option>
                <?php
                    $opts = $DML3->FetchAllData();
                    foreach($opts as $row)
                    {
                        $selected = ($kunci_pencarian3==$row['id_klasifikasi_bangunan']?'selected':'');
                        echo "<option value='".$row['id_klasifikasi_bangunan']."' ".$selected.">".$row['klasifikasi_bangunan']."</option>";
                    }
                ?>
            </select>
            <input type="hidden" name="fk_klasifikasi_bangunan" value="<?=$kunci_pencarian3;?>"/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="fk_jenis_komponen_bangunan">Jenis Komponen <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <select name="fk_jenis_komponen_bangunan" id="fk_jenis_komponen_bangunan" class="form-control" required>
                <option value="" selected></option>
                <?php
                    $opts = $DML4->fetchDataBy('fk_kelompok_komponen_bangunan',$kunci_pencarian2);
                    foreach($opts as $row)
                    {
                        $selected = ($curr_data['fk_jenis_komponen_bangunan']==$row['id_jenis_komponen_bangunan']?'selected':'');
                        echo "<option value='".$row['id_jenis_komponen_bangunan']."' ".$selected.">".$row['jenis_komponen_bangunan']."</option>";
                    }
                ?>
            </select>            
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="nilai_1lantai">Nilai 1 Lantai <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="nilai_1lantai" name="nilai_1lantai" value="<?=$curr_data['nilai_1lantai']?>" class="form-control col-md-7 col-xs-12"  onkeypress="return only_number(event,this)" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="nilai_2lantai">Nilai 2 Lantai <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="nilai_2lantai" name="nilai_2lantai" value="<?=$curr_data['nilai_2lantai']?>" class="form-control col-md-7 col-xs-12"  onkeypress="return only_number(event,this)" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="nilai_3lantai">Nilai 3 Lantai <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="nilai_3lantai" name="nilai_3lantai" value="<?=$curr_data['nilai_3lantai']?>" class="form-control col-md-7 col-xs-12"  onkeypress="return only_number(event,this)" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="nilai_4lantai">Nilai 4 Lantai <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="nilai_4lantai" name="nilai_4lantai" value="<?=$curr_data['nilai_4lantai']?>" class="form-control col-md-7 col-xs-12"  onkeypress="return only_number(event,this)" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="nilai_nlantai">Nilai >4 Lantai <font color="red">*</font></label>
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