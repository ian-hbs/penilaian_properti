<?php
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/DML.php";
    include_once "../../libraries/user_controller.php"; 
    
    
    $id_penugasan = $_POST['id_penugasan'];
    $kunci_pencarian = $_POST['kunci_pencarian'];
    $act = $_POST['act'];
    $menu_id = $_POST['menu_id'];
    $fn = $_POST['fn'];
    $data_type = $_POST['data_type'];
    $jenis_perusahaan_penunjuk = $_POST['jenis_perusahaan_penunjuk'];    
    
    //default value
    $default_usd1 = 0;
    $default_usd2 = 0;
    $default_discount = 65;
    
    $id_value1 = ($act=='edit'?$_POST['id']:'');
    $arr_field1 = array('main_building','description','qty','built_year','renov_year','construction','eco_use_life','cond_on_inspec',
                       'maintenance','phys_deter','func_obsc','eco_obsc','location_index','floor_area',
                       'total_floor_area','price_sqm_usd','price_sqm_usd_abs','cost_sqm1','cost_sqm2','crn','remain','remain_year',
                       'market_value','liquidation_weight','liquidation_value');
    
    $id_value2 = ($act=='edit'?$_POST['id']:'');
    $arr_field2 = array('phys_act_age1','phys_deter_year','phys_deter1','phys_deter2','phys_deter3','func_obsc1','eco_obsc1','func_obsc2',
                       'eco_obsc2','phys_act_age2','phys_deter4','remain_act','remain_rebuild',
                       'first_remain','mv_per_sqr','maintenance','total');

    if($data_type=='1')
    {
        $DML1 = new DML('perhitungan_bangunan',$db);
        $DML2 = new DML('adjustment_bangunan',$db);
        $id_name1 = 'id_perhitungan_bangunan';
        $id_name2 = 'fk_perhitungan_bangunan';

        $arr_field1[] = 'type';
    }
    else
    {
        $DML1 = new DML('perhitungan_bangunan_pembanding',$db);
        $DML2 = new DML('adjustment_bangunan_pembanding',$db);
        $DML4 = new DML('objek_pembanding',$db);
        $id_name1 = 'id_perhitungan_bangunan_pembanding';
        $id_name2 = 'fk_perhitungan_bangunan_pembanding';

        $arr_field1[] = 'fk_objek_pembanding';
    }

    $DML3 = new DML('ref_nilai_bahan_bangunan',$db);    
    
    $curr_data1 = $DML1->getCurrentData($act,$arr_field1,$id_name1,$id_value1);
    $curr_data2 = $DML2->getCurrentData($act,$arr_field2,$id_name2,$id_value2);

    $form_id = 'building-valuation-form';
    $act_lbl=($act=='add'?'menambah':'memperbaharui');

    $saved = array();
    if($act=='add')
    {
        $sql = "SELECT fk_objek_pembanding FROM perhitungan_bangunan_pembanding WHERE(fk_penugasan='".$id_penugasan."')";
        $result = $db->Execute($sql);
        if(!$result)
            echo $db->ErrorMsg();
        while($row=$result->FetchRow())
        {
            $saved[] = $row['fk_objek_pembanding'];
        }
        
    }

    $built_years = array();
    $sql = "SELECT DISTINCT tahun_bangun FROM luas_bangunan WHERE(fk_penugasan='".$id_penugasan."')";
    $result = $db->Execute($sql);
    if(!$result)
        echo $db->ErrorMsg();
    while($row=$result->FetchRow())
    {
        $built_years[]=$row['tahun_bangun'];
    }
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
        <input type="hidden" name="id" value="<?=$id_value1?>"/>
        <input type="hidden" name="act" value="<?=$act?>"/>
        <input type="hidden" name="menu_id" value="<?=$menu_id?>"/>
        <input type="hidden" name="fn" value="<?=$fn?>"/>
        <input type="hidden" name="fk_penugasan" value="<?=$id_penugasan?>"/>
        <input type="hidden" name="kunci_pencarian" value="<?=$kunci_pencarian?>"/>
        <input type="hidden" name="data_type" value="<?=$data_type?>"/>
        <input type="hidden" name="jenis_perusahaan_penunjuk" value="<?=$jenis_perusahaan_penunjuk?>"/>
        <input type="hidden" name="_total_floor_area" value="<?=($act=='edit'?$curr_data1['total_floor_area']:'')?>"/>
        <input type="hidden" name="_market_value" value="<?=($act=='edit'?$curr_data1['market_value']:'')?>"/>
        <input type="hidden" name="_fk_objek_pembanding" value="<?=($act=='edit'?$curr_data1['fk_objek_pembanding']:'')?>"/>
        <input type="hidden" id="curr_year" value="<?=date('Y');?>"/>
        <input type="hidden" id="active_built_year" value='#built_year1'/>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="type"><?=($data_type=='1'?'Type':'Comparison Object')?> <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php
                        if($data_type=='1')
                        {
                            echo "
                            <select name='type' id='type' class='form-control' onchange=\"main_building_controller(this.value);\" required>
                                <option value='' selected></option>";                                
                                    $arr_opt = array('building'=>'Building');
                                    
                                    if($jenis_perusahaan_penunjuk=='1')
                                    {
                                        $arr_opt['site improvement'] = 'Site Improvement';
                                    }

                                    foreach($arr_opt as $key=>$val)
                                    {
                                        $selected = ($curr_data1['type']==$key?'selected':'');
                                        echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                    }                                
                            echo "</select>";
                        }
                        else
                        {
                            echo "
                            <select name='fk_objek_pembanding' id='fk_objek_pembanding' class='form-control' required ".($act=='edit'?'disabled':'').">
                                <option value='' selected></option>";
                                
                                $data = $DML4->fetchDataBy('fk_penugasan',$id_penugasan);
                                foreach($data as $row)
                                {
                                    if(!in_array($row['id_objek_pembanding'],$saved) || $act=='edit')
                                    {
                                        $selected = ($curr_data1['fk_objek_pembanding']==$row['id_objek_pembanding']?'selected':'');
                                        echo "<option value='".$row['id_objek_pembanding']."' ".$selected.">Data ".$row['no_urut']."</option>"; 
                                    }
                                }
                            echo "</select>";
                        }
                        ?>
                    </div>
                </div>
                <?php
                if($data_type=='1')
                {
                    $display = ($act=='add'?'none':($curr_data1['type']=='building'?'block':'none'));
                    echo "
                    <div class='form-group' id='main_building_container' style='display:".$display.";'>
                        <label class='control-label col-md-6 col-sm-6 col-xs-12' for='main_building'>Main Building <font color='red'>*</font></label>
                        <div class='col-md-6 col-sm-6 col-xs-12'>
                            <select name='main_building' id='main_building' class='form-control' required>
                                <option value='' selected></option>";
                                    $arr_opt = array('Y','N');
                                    foreach($arr_opt as $key=>$val)
                                    {
                                        $selected = ($curr_data1['main_building']==$val?'selected':'');
                                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                                    }                            
                            echo "</select>
                        </div>
                    </div>
                    ";
                }
                ?>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="description">Description <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="description" name="description" value="<?=$curr_data1['description']?>" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="built_year">Build Year <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">                        
                        <?php 
                        $display = 'block';
                        $ext_attr = 'required';
                        if($act=='edit')
                        {
                            $display = ($data_type=='1'?($curr_data1['type']=='building'?'none':'block'):'block');
                            $ext_attr = ($data_type=='1'?($curr_data1['type']=='building'?'disabled':'required'):'required');
                        }

                        echo "
                        <!-- if type==site improvement this element appears -->
                        <input type='text' id='built_year1' name='built_year' class='form-control' value='".$curr_data1['built_year']."' onkeypress=\"return only_number(event,this);\" onkeyup=\"mix_function2();\" style='display:".$display.";' ".$ext_attr."/>";
                        
                        $display = 'none';
                        $ext_attr = 'disabled';
                        if($act=='edit')
                        {
                            $display = ($data_type=='1'?($curr_data1['type']=='building'?'block':'none'):'none');
                            $ext_attr = ($data_type=='1'?($curr_data1['type']=='building'?'required':'disabled'):'disabled');
                        }

                        echo "<!-- if type==building this element appears -->                        
                        <select id='built_year2' name='built_year' class='form-control' onblur=\"mix_function2();\" onchange=\"load_floor_area('".$id_penugasan."',this.value);\" style='display:".$display.";' ".$ext_attr.">";
                        echo "<option value='' selected></option>";
                        foreach($built_years as $val)
                        {
                            $selected = ($curr_data1['built_year']==$val?'selected':'');
                            echo "<option value='".$val."' ".$selected.">".$val."</option>";
                        }
                        echo "</select>";
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="renov_year">Renov Year <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="renov_year" name="renov_year" value="<?=$curr_data1['renov_year']?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="mix_function2_2();" required>
                    </div>
                </div>                
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="construction">Const. <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="construction" id="construction" onchange="mix_function4()" class="form-control" required>
                            <option value="" selected></option>
                            <?php
                                $data = $DML3->fetchAllData();
                                foreach($data as $row)
                                {
                                    $selected = ($curr_data1['construction']==$row['nama']?'selected':'');
                                    echo "<option value='".$row['nama']."_".$row['nilai']."' ".$selected.">".$row['nama']."</option>";
                                }
                            ?>
                        </select>                        
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="eco_use_life">Eco. Use Life <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="eco_use_life" name="eco_use_life" value="<?=$curr_data1['eco_use_life']?>" class="form-control readonly-bg" onkeypress="return only_number(event,this);" required readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="cond_on_inspec">Cond. on Inspec <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="cond_on_inspec" id="cond_on_inspec" class="form-control" onchange="mix_function5();" required>
                            <option value="" selected></option>
                            <?php
                                $arr_opt = array('B','C','K');
                                foreach($arr_opt as $key=>$val)
                                {
                                    $selected = ($curr_data1['cond_on_inspec']==$val?'selected':'');
                                    echo "<option value='".$val."' ".$selected.">".$val."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="maintenance">Maintenance <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="maintenance" name="maintenance" value="<?=$curr_data1['maintenance']?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="mix_function6();" required>
                    </div>
                </div>
            </div>
            <div class="col-md-3">                                
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="phys_deter">Phys. Deter <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="phys_deter" name="phys_deter" value="<?=$curr_data1['phys_deter']?>" class="form-control readonly-bg" onkeypress="return only_number(event,this);" required readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="func_obsc">Func. Obsc. <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="func_obsc" name="func_obsc" value="<?=$curr_data1['func_obsc']?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="mix_function7()" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="eco_obsc">Eco. Obsc. <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="eco_obsc" name="eco_obsc" value="<?=$curr_data1['eco_obsc']?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="mix_function8()" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="location_index">Location Index <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="location_index" name="location_index" value="<?=$curr_data1['location_index']?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="mix_function3()" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="qty">Qty <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="qty" name="qty" value="<?=($act=='add'?1:$curr_data1['qty'])?>" onkeypress="return only_number(event,this);" onkeyup="mix_function1();" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="floor_area">Floor Area <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="floor_area" name="floor_area" value="<?=$curr_data1['floor_area']?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="mix_function1();" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="total_floor_area">Total Floor Area <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="total_floor_area" name="total_floor_area" value="<?=$curr_data1['total_floor_area']?>" class="form-control readonly-bg" onkeypress="return only_number(event,this);" required readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="price_sqm_usd_abs">Price/Sqm USD1</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="price_sqm_usd_abs" name="price_sqm_usd_abs" value="<?=($act=='add'?$default_usd1:number_format($curr_data1['price_sqm_usd_abs']))?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="thousand_format(this);mix_function3()">
                    </div>
                </div>

                
            </div>
            <div class="col-md-3">                
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="price_sqm_usd">Price/Sqm USD2</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="price_sqm_usd" name="price_sqm_usd" value="<?=($act=='add'?$default_usd2:number_format($curr_data1['price_sqm_usd']))?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="thousand_format(this);mix_function3()">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="cost_sqm1">Cost/Sqm Rp. <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="cost_sqm1" name="cost_sqm1" value="<?=($act=='add'?'':number_format($curr_data1['cost_sqm1']))?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="thousand_format(this);mix_function3()" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="cost_sqm2">Cost/Sqm Rp. <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="cost_sqm2" name="cost_sqm2" value="<?=($act=='add'?'':number_format($curr_data1['cost_sqm2']))?>" class="form-control readonly-bg" onkeypress="return only_number(event,this);" required readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="crn">CRN Rp. <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="crn" name="crn" value="<?=($act=='add'?'':number_format($curr_data1['crn']))?>" class="form-control readonly-bg" onkeypress="return only_number(event,this);" required readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="remain_year">Remain Year <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="remain_year" name="remain_year" value="<?=($act=='add'?date('Y'):$curr_data1['remain_year']);?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="mix_function9();" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="remain">Remain <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">

                        <input style="text-align:right" type="text" id="remain" name="remain" value="<?=$curr_data1['remain']?>" class="form-control readonly-bg" onkeypress="return only_number(event,this);" required readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="market_value">Market Value <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="market_value" name="market_value" value="<?=($act=='add'?'':number_format($curr_data1['market_value']))?>" class="form-control readonly-bg" onkeypress="return only_number(event,this);" required readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="liquidation_weight">Discount</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="liquidation_weight" name="liquidation_weight" value="<?=($act=='add'?$default_discount:$curr_data1['liquidation_weight']);?>" class="form-control" onkeypress="return only_number(event,this);" onkeyup="get_liquidation_value();">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" for="liquidation_value">Liquid. Value <font color="red">*</font></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="text-align:right" type="text" id="liquidation_value" name="liquidation_value" value="<?=($act=='add'?'':number_format($curr_data1['liquidation_value']))?>" class="form-control readonly-bg" onkeypress="return only_number(event,this);" required readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                    <table class="table table-bordered">                        
                        <tbody>
                            <tr>
                                <td>Phys. Act. Age<input type="hidden" name="phys_act_age1" id="phys_act_age1_val" value="<?=$curr_data2['phys_act_age1']?>"/></td><td id="phys_act_age1"><?=$curr_data2['phys_act_age1']?></td>
                                <td>Eco Obsc.<input type="hidden" name="eco_obsc2" id="eco_obsc2_val" value="<?=$curr_data2['eco_obsc2']?>"/></td><td id="eco_obsc2"><?=$curr_data2['eco_obsc2']?></td>
                            </tr>
                            <tr>
                                <td>Phys. Deter./Yr<input type="hidden" name="phys_deter_year" id="phys_deter_year_val" value="<?=$curr_data2['phys_deter_year']?>"/></td><td id="phys_deter_year"><?=$curr_data2['phys_deter_year']?></td>
                                <td>Phys. Act. Age<input type="hidden" name="phys_act_age2" id="phys_act_age2_val" value="<?=$curr_data2['phys_act_age2']?>"/></td><td id="phys_act_age2"><?=$curr_data2['phys_act_age2']?></td>
                            </tr>
                            <tr>
                                <td>Phys. Deter.<input type="hidden" name="phys_deter1" id="phys_deter1_val" value="<?=$curr_data2['phys_deter1']?>"/></td><td id="phys_deter1"><?=$curr_data2['phys_deter1']?></td>
                                <td>Phys. Deter.<input type="hidden" name="phys_deter4" id="phys_deter4_val" value="<?=$curr_data2['phys_deter4']?>"/></td><td id="phys_deter4"><?=($act=='add'?'':number_format($curr_data2['phys_deter4'],2,'.',','))?></td>
                            </tr>
                            <tr>
                                <td>Phys. Deter.<input type="hidden" name="phys_deter2" id="phys_deter2_val" value="<?=$curr_data2['phys_deter2']?>"/></td><td id="phys_deter2"><?=$curr_data2['phys_deter2']?></td>
                                <td>Remain Act.<input type="hidden" name="remain_act" id="remain_act_val" value="<?=$curr_data2['remain_act']?>"/></td><td id="remain_act"><?=$curr_data2['remain_act']?></td>
                            </tr>
                            <tr>
                                <td>Phys. Deter.<input type="hidden" name="phys_deter3" id="phys_deter3_val" value="<?=$curr_data2['phys_deter2']?>"/></td><td id="phys_deter3"><?=$curr_data2['phys_deter2']?></td>
                                <td>Remain Rebuild.<input type="hidden" name="remain_rebuild" id="remain_rebuild_val" value="<?=$curr_data2['remain_rebuild']?>"/></td><td id="remain_rebuild"><?=($act=='add'?'':number_format($curr_data2['remain_rebuild'],2,'.',','))?></td>
                            </tr>
                            <tr>
                                <td>Func. Obsc.<input type="hidden" name="func_obsc1" id="func_obsc1_val" value="<?=$curr_data2['func_obsc1']?>"/></td><td id="func_obsc1"><?=$curr_data2['func_obsc1']?></td>
                                <td>First Remain<input type="hidden" name="first_remain" id="first_remain_val" value="<?=$curr_data2['first_remain']?>"/></td><td id="first_remain"><?=$curr_data2['first_remain']?></td>
                            </tr>
                            <tr>
                                <td>Eco. Obsc.<input type="hidden" name="eco_obsc1" id="eco_obsc1_val" value="<?=$curr_data2['eco_obsc1']?>"/></td><td id="eco_obsc1"><?=$curr_data2['eco_obsc1']?></td>
                                <td>MV/Sqr<input type="hidden" name="mv_per_sqr" id="mv_per_sqr_val" value="<?=$curr_data2['mv_per_sqr']?>"/></td><td id="mv_per_sqr"><?=($act=='add'?'':number_format($curr_data2['mv_per_sqr']))?></td>
                            </tr>
                            <tr>
                                <td>Func Obsc.<input type="hidden" name="func_obsc2" id="func_obsc2_val" value="<?=$curr_data2['func_obsc2']?>"/></td><td id="func_obsc2"><?=$curr_data2['func_obsc2']?></td>
                                <td>Maintenance<input type="hidden" name="maintenance_" id="maintenance_val" value="<?=$curr_data2['maintenance']?>"/></td><td id="maintenance_"><?=$curr_data2['maintenance']?></td>
                            </tr>
                            <tr><td colspan="2"></td><td>Total<input type="hidden" name="total" id="total_adjustment_val" value="<?=$curr_data2['total']?>"/></td><td id="total_adjustment"><?=$curr_data2['total']?></td></tr>
                        </tbody>
                    </table>
            </div>
        </div>

        <div class="row">            
            <div class="col-md-12">
                <button type="button" class="btn btn-danger" onclick="close_form();">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>                    
            </div>
        </div>        
    </form>
