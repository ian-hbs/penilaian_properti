<?php
    
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/DML.php";
    include_once "../../libraries/user_controller.php"; 
    
    //instantiate objects
    $DML = new DML('luas_bangunan',$db);    
    
    $id_penugasan = $_POST['id_penugasan'];
    $kunci_pencarian = $_POST['kunci_pencarian'];
    $act = $_POST['act'];
    $menu_id = $_POST['menu_id'];
    $fn = $_POST['fn'];

    $id_name = 'id_luas_bangunan';
    $id_value = ($act=='edit'?$_POST['id']:'');
    $arr_field = array('tahun_bangun','tingkat_lantai','teras','ruang_tamu','ruang_keluarga','ruang_tidur1','ruang_tidur2','ruang_tidur3','ruang_dapur',
                       'kamar_mandi','lain_lain','total');
    $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'building-area-form';
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

        function sum_building_area()
        {
            var $tot = $('#total'), $r1 = $('#teras'), $r2 = $('#ruang_tamu'), $r3 = $('#ruang_keluarga'), $r4 = $('#ruang_tidur1'), $r5 = $('#ruang_tidur2'), 
                $r6 = $('#ruang_tidur3'), $r7 = $('#ruang_dapur'), $r8 = $('#kamar_mandi'), $r9 = $('#lain_lain');

            var l1 = ($r1.val()==''?"0":$r1.val()), l2 = ($r2.val()==''?"0":$r2.val()), l3 = ($r3.val()==''?"0":$r3.val()),
                l4 = ($r4.val()==''?"0":$r4.val()), l5 = ($r5.val()==''?"0":$r5.val()), l6 = ($r6.val()==''?"0":$r6.val()),
                l7 = ($r7.val()==''?"0":$r7.val()), l8 = ($r8.val()==''?"0":$r8.val()), l9 = ($r9.val()==''?"0":$r9.val());

            l1 = replaceall(l1,',','');
            l2 = replaceall(l2,',','');
            l3 = replaceall(l3,',','');
            l4 = replaceall(l4,',','');
            l5 = replaceall(l5,',','');
            l6 = replaceall(l6,',','');
            l7 = replaceall(l7,',','');
            l8 = replaceall(l8,',','');
            l9 = replaceall(l9,',','');

            var tot = parseFloat(l1) + parseFloat(l2) + parseFloat(l3) + parseFloat(l4) + parseFloat(l5) + parseFloat(l6) + parseFloat(l7) + parseFloat(l8) + parseFloat(l9);

            $tot.val(number_format(tot,2,'.',','));
        }

	</script>

    <form id="<?=$form_id?>" class="form-horizontal form-label-left" method="POST" action="contents/<?=$fn?>/manipulating.php">
        <input type="hidden" name="id" value="<?=$id_value?>"/>
        <input type="hidden" name="act" value="<?=$act?>"/>
        <input type="hidden" name="menu_id" value="<?=$menu_id?>"/>
        <input type="hidden" name="fn" value="<?=$fn?>"/>
        <input type="hidden" name="fk_penugasan" value="<?=$id_penugasan?>"/>
        <input type="hidden" name="kunci_pencarian" value="<?=$kunci_pencarian?>"/>
        <input type="hidden" name="_total" value="<?=($act=='edit'?$curr_data['total']:'');?>"/>
        
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="tingkat_lantai">Lantai <font color="red">*</font></label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input style="text-align:right" type="text" id="tingkat_lantai" name="tingkat_lantai" value="<?=$curr_data['tingkat_lantai']?>" onkeypress="return only_number(event,this);" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="tahun_bangun">T. Bangun <font color="red">*</font></label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input style="text-align:right" type="text" id="tahun_bangun" name="tahun_bangun" value="<?=$curr_data['tahun_bangun']?>" onkeypress="return only_number(event,this);" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="teras">Teras</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input style="text-align:right" type="text" id="teras" name="teras" value="<?=$curr_data['teras']?>" onkeypress="return only_number(event,this);" onkeyup="sum_building_area();" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="ruang_tamu">R. Tamu <font color="red">*</font></label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input style="text-align:right" type="text" id="ruang_tamu" name="ruang_tamu" value="<?=$curr_data['ruang_tamu']?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="sum_building_area();" required>
                    </div>
                </div>
                
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="ruang_keluarga">R. Klg. <font color="red">*</font></label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input style="text-align:right" type="text" id="ruang_keluarga" name="ruang_keluarga" value="<?=$curr_data['ruang_keluarga']?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="sum_building_area();" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="ruang_tidur1">R. Tidur1 <font color="red">*</font></label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input style="text-align:right" type="text" id="ruang_tidur1" name="ruang_tidur1" value="<?=$curr_data['ruang_tidur1']?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="sum_building_area();" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="ruang_tidur2">R. Tidur2 <font color="red">*</font></label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input style="text-align:right" type="text" id="ruang_tidur2" name="ruang_tidur2" value="<?=$curr_data['ruang_tidur2']?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="sum_building_area();" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="ruang_tidur3">R. Tidur3 <font color="red">*</font></label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input style="text-align:right" type="text" id="ruang_tidur3" name="ruang_tidur3" value="<?=$curr_data['ruang_tidur3']?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="sum_building_area();" required>
                    </div>
                </div>                
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="ruang_dapur">R. Dapur <font color="red">*</font></label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input style="text-align:right" type="text" id="ruang_dapur" name="ruang_dapur" value="<?=$curr_data['ruang_dapur']?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="sum_building_area();" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="kamar_mandi">K. Mandi <font color="red">*</font></label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input style="text-align:right" type="text" id="kamar_mandi" name="kamar_mandi" value="<?=$curr_data['kamar_mandi']?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="sum_building_area();" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="lain_lain">Lain-lain <font color="red">*</font></label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input style="text-align:right" type="text" id="lain_lain" name="lain_lain" value="<?=$curr_data['lain_lain']?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="sum_building_area();" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="total">Total <font color="red">*</font></label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input style="text-align:right" type="text" id="total" name="total" value="<?=$curr_data['total']?>" class="form-control readonly-bg" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">            
            <div class="col-md-12" align="right">                
                <button type="button" class="btn btn-danger" onclick="close_form();">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>                    
            </div>
        </div>        
    </form>
