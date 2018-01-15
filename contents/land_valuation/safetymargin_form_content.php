<?php
	session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/DML.php";
    include_once "../../libraries/global_obj.php";
    include_once "../../libraries/user_controller.php";
    include_once "../../helpers/date_helper.php";

    //instantiate objects
    $uc = new user_controller($db);    

    $uc->check_access();

    $id_penugasan = $_POST['id_penugasan'];
    $menu_id = $_POST['menu_id'];
    $fn = $_POST['fn'];
    $kunci_pencarian = $_POST['kunci_pencarian'];
    
    $DML1 = new DML('ref_param_safetymargin',$db);
    $DML2 = new DML('ref_faktor_safetymargin',$db);
    $DML3 = new DML('ref_prosentase_safetymargin',$db);
    $global = new global_obj($db);

    // fetching perhitungan_tanah
    $sql = "SELECT total_land_value FROM perhitungan_tanah WHERE(fk_penugasan='".$id_penugasan."')";
	$result = $db->Execute($sql);
	if(!$result)
		echo $db->ErrorMsg();

	$npt = 0;
	$ndpt = $result->RecordCount();
	if($ndpt>0)
	{
		$dpt = $result->FetchRow();
		$npt = $dpt['total_land_value'];
	}	
	$disabled = ($ndpt==0?'disabled':'');
	// == //

	//fetching ref_prosentase_safetymargin
	$psm = $DML3->fetchAllData();
	$psm_arr = array();
	foreach($psm as $row)
	{
		$psm_arr[] = array('min'=>$row['min'],'max'=>$row['max'],'prosentase'=>$row['prosentase']);
	}	
	$psm_arr = json_encode($psm_arr);
	// == //

    //fetching current data nilai_safetymargin
    $sql = "SELECT id_nilai_safetymargin,total_score,prosentase,nilai FROM nilai_safetymargin WHERE(fk_penugasan='".$id_penugasan."') AND (jenis_objek='tanah')";
    $result1 = $db->Execute($sql);
    if(!$result1)
        echo $db->ErrorMsg();

    $id_nilai_safetymargin = '';
    $ndsm = $result1->RecordCount();
    if($ndsm>0)
    {
        $dsm = $result1->FetchRow();
        
        $id_nilai_safetymargin = $dsm['id_nilai_safetymargin'];

        $sql = "SELECT id_faktor_safetymargin,fk_param_safetymargin,faktor FROM faktor_safetymargin WHERE(fk_nilai_safetymargin='".$id_nilai_safetymargin."')";
        $result2 = $db->Execute($sql);
        if(!$result)
            echo $db->ErrorMsg();

        $factors = array();
        while($row = $result2->FetchRow())
        {
            $factors[$row['fk_param_safetymargin']] = array('id_faktor_safetymargin'=>$row['id_faktor_safetymargin'],'faktor'=>$row['faktor']);
        }
    }
    // == //

    $act = ($ndsm==0?'add':'edit');
    $act_lbl = ($act=='add'?'menambah':'memperbaharui');

    $form_id = 'safetymargin-form';    
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
            ajax_manipulate.set_plugin_datatable(true)
                           .set_content('#list-of-data')
                           .set_loading('#preloadAnimation')
                           .enable_pnotify()
                           .set_close_modal('#close-modal-form')
                           .set_form($input_form)
                           .submit_ajax(act_lbl);
            $('#close-modal-form').click();
            return false;
        }
    });

    var psm = <?php echo $psm_arr;?>;    
    function get_safetymargin()
    {
    	var $ts = $('#total_score');
    	var $pr = $('#prosentase');
    	var $nl = $('#nilai');

    	var npt = $('#nilai_pasar_tanah').val();
    	var n_psm = $('#n_param_safetymargin').val();
    	var nsm = 0;

    	npt = replaceall(npt,',','');

    	var tot_score = 0;
    	for(i=1;i<=n_psm;i++)
    	{
    		val = $('#faktor_safetymargin'+i).val();
    		score = parseInt((val==''?0:val));
    		tot_score += score;
    	}
    	
    	var prosentase = 0;
    	for(i=0;i<psm.length;i++)
    	{
    		x = psm[i];
    		min = parseInt(x['min']);
    		max = parseInt(x['max']);
    		if(tot_score>=min && tot_score<=max)
    		{
    			prosentase = x['prosentase'];
    		}
    	}

    	nsm = ((100-parseInt(prosentase)) * parseInt(npt))/100;
    	nsm = round(nsm,-3);
    	
    	$ts.val(tot_score);
    	$pr.val(prosentase);
    	$nl.val(number_format(nsm,0,'.',','));
    }


</script>

<?php
	$pd = $global->get_property_detail($id_penugasan);
    $pd['tgl_survei'] = indo_date_format($pd['tgl_survei'],'longDate');
    
    $global->print_property_detail($pd,20);

    echo "
    <form class='form-horizontal' id='".$form_id."' method='POST' action='contents/".$fn."/safetymargin_manipulating.php'>
        <input type='hidden' name='fk_penugasan' value='".$id_penugasan."'/>
        <input type='hidden' name='menu_id' value='".$menu_id."'/>
        <input type='hidden' name='fn' value='".$fn."'/>
        <input type='hidden' name='act' value='".$act."'/>
        <input type='hidden' name='kunci_pencarian' value='".$kunci_pencarian."'/>
        <input type='hidden' name='id_nilai_safetymargin' value='".$id_nilai_safetymargin."'/>

        <div class='row'>
            <div class='col-md-12'>";

            	if($ndpt==0)
            	{
            		echo "
	            	<div class='alert alert-warning' role='alert'>
		              <button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
		              Silahkan lakukan perhitungan Nilai Tanah terlebih dahulu!
		            </div>";
		        }
            	
            	echo "
            	<div class='form-group'>
            		<label class='col-sm-4 control-label'>Nilai Pasar Tanah</label>
            		<div class='col-sm-3'>
            			<input type='text' class='form-control readonly-bg' id='nilai_pasar_tanah' name='nilai_pasar_tanah' style='text-align:right' value='".number_format($npt)."' readonly/>
            		</div>
            	</div>";
            	
            	$data = $DML1->fetchData("SELECT id_param_safetymargin,deskripsi FROM ref_param_safetymargin WHERE(jenis_objek='tanah')");
            	$no = 0;
            	foreach($data as $row1)
            	{
            		$no++;
            		echo "
	                <div class='form-group'>
	                    <label class='col-sm-4 control-label'>".$row1['deskripsi']." <font color='red'>*</font></label>
	                    <div class='col-sm-6'>
	                        <select name='faktor_safetymargin".$no."' id='faktor_safetymargin".$no."' class='form-control' onchange=\"get_safetymargin();\" required ".$disabled.">
	                        <option value='' selected></option>";
	                        $opts = $DML2->fetchData("SELECT id_faktor_safetymargin,nilai_faktor,deskripsi FROM ref_faktor_safetymargin WHERE(fk_param_safetymargin='".$row1['id_param_safetymargin']."')");
	                        foreach($opts as $row2)
	                        {
                                $selected = ($act=='edit'?($factors[$row1['id_param_safetymargin']]['faktor']==$row2['nilai_faktor']?'selected':''):'');
	                        	echo "<option value='".$row2['nilai_faktor']."' ".$selected.">".$row2['nilai_faktor']." - ".$row2['deskripsi']."</option>";
	                        }
	                      	echo "</select>
                            <input type='hidden' name='id_param_safetymargin".$no."' value='".$row1['id_param_safetymargin']."'/>
                            <input type='hidden' name='id_faktor_safetymargin".$no."' value='".($ndsm>0?$factors[$row1['id_param_safetymargin']]['id_faktor_safetymargin']:'')."'/>
	                    </div>
	                </div>";
	            }
            	
            echo "
            	<input type='hidden' name='n_param_safetymargin' id='n_param_safetymargin' value='".$no."'/>
            	<div class='form-group'>
            		<label class='col-sm-4 control-label'>Total Score</label>
            		<div class='col-sm-3'>
            			<input type='text' class='form-control autofill-bg' id='total_score' name='total_score' style='text-align:right' value='".($act=='edit'?$dsm['total_score']:'0')."' readonly/>
            		</div>
            	</div>

            	<div class='form-group'>
            		<label class='col-sm-4 control-label'>Safety Margin</label>
            		<div class='col-sm-3'>
            			<input type='text' class='form-control autofill-bg' id='prosentase' name='prosentase' style='text-align:right' value='".($act=='edit'?$dsm['prosentase']:'0')."' readonly/>
            		</div>
            	</div>

            	<div class='form-group'>
            		<label class='col-sm-4 control-label'>Nilai Setelah Safety Margin</label>
            		<div class='col-sm-3'>
            			<input type='text' class='form-control autofill-bg' id='nilai' name='nilai' style='text-align:right' value='".($act=='edit'?number_format($dsm['nilai']):'0')."' readonly/>
            		</div>
            	</div>

            </div><!-- /.col -->            
        </div>
        <div class='ln_solid'></div>
        <div class='form-group'>
            <div class='col-md-12 col-sm-12 col-xs-12' align='center'>
                <button type='button' class='btn btn-danger' id='close-modal-form' data-dismiss='modal'>Batal</button>
                <button type='submit' class='btn btn-success' ".$disabled.">Simpan</button>
            </div>
        </div>
    </form>";
?>