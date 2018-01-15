<?php
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../config/app_param.php";
    include_once "../../libraries/DML.php";
    include_once "../../helpers/date_helper.php";
    include_once "../../libraries/user_controller.php";

    //instantiate objects
    $uc = new user_controller($db);

    $uc->check_access();
    
	$act=$_POST['act'];
    $menu_id=$_POST['menu_id'];
    $fn=$_POST['fn'];
    $act_lbl=($act=='add'?'menambah':'memperbaharui');
	
    $DML = new DML('ref_perusahaan_penunjuk',$db);

    $_SYSTEM_PARAMS = $_APP_PARAM['system_params'];

    $id_name = 'id_perusahaan_penunjuk';
    $id_value = ($act=='edit'?$_POST['id']:'');
    $arr_field = array('perusahaan_penunjuk','kantor_cabang','alamat','kota','kode_pos','no_kerjasama','tgl_kerjasama','jenis');
    $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);
    $id_form = 'pointing-company-form';
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
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="<?=$id_name?>">ID Perusahaan <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="<?=$id_name?>" name="<?=$id_name?>" value="<?=$id_value?>" class="form-control col-md-7 col-xs-12" maxlength=10 onkeypress="return only_number(event,this)" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="perusahaan_penunjuk">Nama Perusahaan<font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="perusahaan_penunjuk" name="perusahaan_penunjuk" value="<?=$curr_data['perusahaan_penunjuk']?>" class="form-control col-md-7 col-xs-12" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="kantor_cabang">Kantor Cabang <font color="red">*</font></label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="kantor_cabang" name="kantor_cabang" value="<?=$curr_data['kantor_cabang']?>" class="form-control col-md-7 col-xs-12" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="alamat">Alamat</label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="alamat" name="alamat" value="<?=$curr_data['alamat']?>" class="form-control col-md-7 col-xs-12"/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="kota">Kota</label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="kota" name="kota" value="<?=$curr_data['kota']?>" class="form-control col-md-7 col-xs-12"/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="kode_pos">Kode Pos</label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="kode_pos" name="kode_pos" value="<?=$curr_data['kode_pos']?>" class="form-control col-md-7 col-xs-12"/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="no_kerjasama">No. Kerjasama</label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="no_kerjasama" name="no_kerjasama" value="<?=$curr_data['no_kerjasama']?>" class="form-control col-md-7 col-xs-12"/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="tgl_kerjasama">Tgl. Kerjasama</label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="tgl_kerjasama" name="tgl_kerjasama" value="<?=($act=='add'?'':indo_date_format($curr_data['tgl_kerjasama'],'shortDate'))?>" class="form-control col-md-7 col-xs-12 datepicker"/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="tgl_kerjasama">Jenis Bank</label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <?php
                $checked1 = ($act=='edit'?($curr_data['jenis']=='1'?'checked':''):'');
                $checked2 = ($act=='edit'?($curr_data['jenis']=='2'?'checked':''):'');
                echo "<input type='radio' name='jenis' value='1' ".$checked1."/> 1&nbsp;&nbsp;
                      <input type='radio' name='jenis' value='2' ".$checked2."/> 2";
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