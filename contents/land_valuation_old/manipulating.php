<?php
	session_start();
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";
	include_once "../../libraries/user_controller.php"; 
	include_once "../../libraries/DML.php";
	include_once "../../helpers/date_helper.php";
	include_once "../..//helpers/mix_helper.php";
	include_once "../../libraries/global_obj.php";
	include_once "../../config/app_param.php";
	
	$system_params = $_APP_PARAM['system_params'];
	$base_params = $_APP_PARAM['base'];

	$DML = new DML('tbl_transaksi_retribusi',$db);
	$global = new global_obj($db);
	$uc = new user_controller($db);

	$uc->check_access();

	$act = $_POST['act'];

	
	$menu_id = $_POST['menu_id'];
	$req = $_POST['req'];

	$arr_data=array();

	
	if($act=='add' || $act=='edit')
	{
		$arr_field = array('npwr','fk_kelurahan','tahun','bulan','diterima_oleh','tgl_bayar','diinput_oleh','total_bayar','status_bayar','status_lunas','status_disiplin');
		foreach($_POST as $key => $val)
		{
			if(in_array($key,$arr_field))
			{
				if($key=='npwr')
					$arr_data[$key]=preg_replace("/\.|-/i","",$val);
				else if($key=='tgl_bayar')					
					$arr_data[$key]=us_date_format($val);
				else if($key=='total_bayar')
					$arr_data[$key]=preg_replace("/\./i","",$val);
				else if($key=='status_disiplin')
					$arr_data[$key]=(strtolower($val)=='tepat waktu'?'1':'0');
				else
					$arr_data[$key]=$val;
			}
		}
	}

	if($act=='add')
	{
		//check transaction data
		$sql = "SELECT id_transaksi FROM tbl_transaksi_retribusi WHERE(npwr='".$arr_data['npwr']."') and (bulan='".$arr_data['bulan']."') and (tahun='".$arr_data['tahun']."')";
		$dt_transaction = $DML->fetchData($sql);
		$n_transaction = $dt_transaction->_getNumResult();
		
		if($n_transaction==0)
		{
			$arr_data['id_transaksi']=date('YmdHis');
			$arr_data['wkt_input']=date('Y-m-d H:i:s');
			$result = $DML->save($arr_data);
			if(!$result)
				die('failed');
			
			//data Wajib Retribusi
			$dt_wr = $global->get_wr_recordset($arr_data['npwr']);
			
			//data Template Pesan
			$template_type = ($arr_data['status_disiplin']=='1'?'1':'2');
			$sql = "SELECT isi FROM tbl_template_pesan WHERE(id='".$template_type."')";
			$dt_template = $DML->fetchData($sql);
			$row_template = $DML->getFetchResult($dt_template);

			$param[0] = $row_template['isi'];
			$param[1] = $dt_wr['nama'];
			$param[2] = get_monthName($arr_data['bulan']);
			$param[3] = number_format($arr_data['total_bayar'],0,',','.');
			$param[4] = ($arr_data['status_disiplin']=='1'?'Tepat waktu':'Telat wktu');

			$content_msg = preg_replace("/'/i","\'",$global->get_formatted_message($param));

			//save message to smsd database
			/*$sql_manipulating = "INSERT INTO outbox SET `DestinationNumber`='".$dt_wr['no_ponsel']."',`TextDecoded`='".$content_msg."',`CreatorID`='".$base_params['sys_name_acr1']."'";
			
			$result = $db_smsd->Execute($sql_manipulating);
			if(!$result)
			{
				die($db_smsd->ErrorMsg());
			}*/

		}
		else
			die('failed');

	}
	else if($act=='edit')
	{
		$id = $_POST['id'];		

		$cond = "id_transaksi='".$id."'";
		
		$result = $DML->update($arr_data,$cond);

		if(!$result)
			die('failed');		
				
	}
	else if($act=='delete')
	{
		$id = $_POST['id'];		

		$cond = "id_transaksi='".$id."'";
		$result = $DML->delete($cond);
		
		if(!$result)
			die('failed');
	}


	//refresh content of page	
	if($req=='1')
	{
		$fn = $_POST['fn'];
	    $addAccess = $uc->check_priviledge('add',$menu_id);    
	    $id_form = 'form-input-retribusi';

		include_once "form_content.php";
	}
	else
	{
		$fn1 = $_POST['fn1'];
		$fn2 = $_POST['fn2'];
		
		if($act=='delete')
		{
			include_once "../payment_management/list_sql.php";
	    
	    	$bulan = $_POST['bulan'];
			$id_kelurahan = $_POST['id_kelurahan'];
		   

		    $editAccess = $uc->check_priviledge('update',$menu_id);
		    $deleteAccess = $uc->check_priviledge('delete',$menu_id);

		    $list_sql .= " and (a.bulan='".$bulan."') and (x.id_kelurahan='".$id_kelurahan."')";
		    
		    $list_of_data = $db->Execute($list_sql);
		    if (!$list_of_data)
		        print $db->ErrorMsg();
		    	    
		    include_once "../".$fn1."/list_of_data.php";
		}
		else
		{

			?>    

			<script type='text/javascript'>    
			    var $form=$('#form-pencarian-pembayaran');
			    var stat=$form.validate();

			    $form.submit(function(){
			        if(stat.checkForm())
			        {
			            ajax_manipulate.set_content('#data-view')
			                           .set_plugin_datatable(true)
			                           .set_loading('#preloadAnimation')
			                           .disable_pnotify()
			                           .set_form($form)
			                           .submit_ajax('');
			            return false;
			        }
			    });
			        
			</script>

		<?php

			$readAccess = $uc->check_priviledge('read',$menu_id);
			$editAccess = $uc->check_priviledge('update',$menu_id);
			$deleteAccess = $uc->check_priviledge('delete',$menu_id);

		    if($readAccess)
		    {
		    	$bulan = $_POST['bulan'];
				$id_kelurahan = $_POST['id_kelurahan'];
		        echo "
		        <form id='form-pencarian-pembayaran' class='form-horizontal' method='POST' action='contents/".$fn1."/data_view.php'>
		        <input type='hidden' name='fn1' value='".$fn1."'/>
        		<input type='hidden' name='fn2' value='".$fn2."'/>
		        <input type='hidden' name='menu_id' value='".$menu_id."'/>
		        <div class='box'>
		            <div class='box-header'>
		                <div class='row'>
		                  <div class='col-md-6'>
		                    <h3 class='box-title'>Pencarian Data Pembayaran Retribusi</h3>
		                  </div>              
		                </div>
		            </div>
		            <div class='box-body'>
		                <div class='form-group'>
		                    <label class='col-md-3 col-xs-12 control-label'>Periode/Kelurahan <font color='red'>*</font></label>
		                    <div class='col-md-2 col-xs-12'>
		                        <select name='bulan' id='bulan' class='form-control' required>";
		                            $curr_month = $bulan;
		                            for($i=1;$i<=12;$i++)
		                            {
		                                $selected = ($curr_month==$i?'selected':'');
		                                echo "<option value='".$i."' ".$selected.">".get_monthName($i)."</option>";
		                            }
		                        echo "</select>
		                    </div>
		                    <div class='col-md-3 col-xs-12'>
		                        <select name='id_kelurahan' id='id_kelurahan' class='form-control' required>
		                            <option value='' selected></option>";
		                            $village_id = $id_kelurahan;
		                            $district_id = $system_params['kd_propinsi'].$system_params['kd_dt2'].$system_params['kd_kecamatan'];
		                            $sql = "SELECT * FROM villages WHERE (district_id='".$district_id."')";
		                            $result = $db->Execute($sql);
		                            if(!$result)
		                                die($db->ErrorMsg());
		                            
		                            while($row = $result->FetchRow())
		                            {
		                            	$selected = ($village_id==$row['id']?'selected':'');
		                                echo "<option value='".$row['id']."' ".$selected.">".$row['id']." - ".$row['name']."</option>";
		                            }
		                        echo "
		                        </select>
		                    </div>
		                </div>                
		            </div>
		            <div class='box-footer'>
		                <button type='reset' class='btn btn-default'>Clear Form</button>
		                <button type='submit' class='btn btn-primary pull-right'>Submit</button>
		            </div>
		        </div>
		        </form>
		        <div id='data-view'>";		        			   			    			  
			    

			    include_once "../".$fn1."/list_sql.php";
			    $list_sql .= " and (a.bulan='".$bulan."') and (x.id_kelurahan='".$id_kelurahan."')";
			    $list_of_data = $db->Execute($list_sql);

			    if (!$list_of_data)
			        print $db->ErrorMsg();
			     
			    echo "
			    <div class='box'>
			      <div class='box-header'>
			          <div class='row'>
			            <div class='col-md-6'>
			              <h3 class='box-title'>Daftar Pembayaran Retribusi</h3>
			            </div>
			            <div class='col-md-6' align='right'>                    
			                <button type='button' class='btn btn-xs btn-default' onclick='load_content();'><i class='fa fa-refresh'></i> Refresh</button>
			            </div>
			          </div>
			      </div>
			      <div class='box-body'>
			        <div id='list-of-data'>";
			            include_once "../".$fn1."/list_of_data.php"; 
			        echo "
			        </div>
			        <div id='test'>
			        </div>
			      </div>
			    </div>";

		        echo "</div>";
		    }
		    else
		    {
		        echo "
		            <div class='row'>
		                <div class='col-md-12'>
		                    <div class='alert alert-warning' role='alert'>
		                    <button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
		                    Anda tidak memiliki akses untuk melihat data
		                    </div>
		                </div>
		            </div>";
		    }
		}
	}
?>