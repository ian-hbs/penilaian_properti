<?php
	echo "
	<div class='row'>
      	<div class='col-md-6'>
	        <div class='form-group'>
	          <label class='col-sm-4 control-label'>Nomor <font color='red'>*</font></label>
	          <div class='col-sm-8'><input type='text' name='no_penugasan' id='no_penugasan' class='form-control' value=\"".$curr_data['no_penugasan']."\" required/></div>
	        </div>
	        <div class='form-group'>
	          <label class='col-sm-4 control-label'>Tanggal <font color='red'>*</font></label>
	          <div class='col-sm-8'><input type='text' id='tgl_penugasan' name='tgl_penugasan' class='form-control datepicker' value='".$curr_data['tgl_penugasan']."' required/></div>
	        </div>
	        <div class='form-group'>
	          <label class='col-sm-4 control-label'>Keperluan <font color='red'>*</font></label><br />
	          <div class='col-sm-8'>";
	          $checked1 = ($curr_data['keperluan_penugasan']=='KPR'?'checked':'');
	          $checked2 = ($curr_data['keperluan_penugasan']=='KP RUKO'?'checked':'');
	          $checked3 = ($curr_data['keperluan_penugasan']=='AGUNAN'?'checked':'');
	          echo "
	          <input type='radio' name='keperluan_penugasan' id='keperluan_penugasan1' value='KPR' ".$checked1." required/>KPR&nbsp;
	          <input type='radio' name='keperluan_penugasan' id='keperluan_penugasan2' value='KP RUKO' ".$checked2." required/>KP RUKO&nbsp;
	          <input type='radio' name='keperluan_penugasan' id='keperluan_penugasan3' value='AGUNAN' ".$checked3." required/>AGUNAN&nbsp;</div>
	        </div>
	        <div class='form-group'>
	          <label class='col-sm-4 control-label'>Per. Penunjuk <font color='red'>*</font></label>
	          <div class='col-sm-8'>
	          	<select name='fk_perusahaan_penunjuk' id='fk_perusahaan_penunjuk' class='form-control' required>
                  	<option value='' selected></option>";
                  	$data = $DML6->fetchAllData();
                  	foreach($data as $row)
                  	{
                  		$selected = ($curr_data['fk_perusahaan_penunjuk']==$row['id_perusahaan_penunjuk']?'selected':'');
                      	echo "<option value='".$row['id_perusahaan_penunjuk']."' ".$selected.">".$row['perusahaan_penunjuk'].", ".$row['kantor_cabang']."</option>";
                  	}
                echo "
                </select>
	          </div>
	        </div>	        
	      </div><!-- /.col -->
	      <div class='col-md-6'>	        
	        <div class='form-group'>
	          <label class='col-sm-4 control-label'>Pengorder 1 <font color='red'>*</font></label>
	          <div class='col-sm-8'><input type='text' name='nama_pengorder1_penugasan' id='nama_pengorder1_penugasan' value=\"".$curr_data['nama_pengorder1']."\" class='form-control' required/></div>
	        </div>
	        <div class='form-group'>
	          <label class='col-sm-4 control-label'>Jababatan 1 <font color='red'>*</font></label>
	          <div class='col-sm-8'><input type='text' name='jabatan_pengorder1_penugasan' id='jabatan_pengorder1_penugasan' value=\"".$curr_data['jabatan_pengorder1']."\" class='form-control' required/></div>
	        </div>
	        <div class='form-group'>
	          <label class='col-sm-4 control-label'>Pengorder 2</label>
	          <div class='col-sm-8'><input type='text' name='nama_pengorder2_penugasan' id='nama_pengorder2_penugasan' value=\"".$curr_data['nama_pengorder2']."\" class='form-control'/></div>
	        </div>
	        <div class='form-group'>
	          <label class='col-sm-4 control-label'>Jabatan 2</label>
	          <div class='col-sm-8'><input type='text' name='jabatan_pengorder2_penugasan' id='jabatan_pengorder2_penugasan' value=\"".$curr_data['jabatan_pengorder2']."\" class='form-control'/></div>
	        </div>
      	</div><!-- /.col -->
  	</div>
  	<div class='ln_solid'></div>
	  <div class='form-group'>
	      <div class='col-md-12 col-sm-12 col-xs-12' align='right'>
	          <button type='button' class='btn btn-danger' id='close-modal-form' data-dismiss='modal'>Batal</button>
	          <button type='submit' class='btn btn-success'>Simpan</button>
	      </div>
	  </div>";
?>