<?php
	echo "
	<div class='row'>
      <div class='col-md-6'>
        <div class='form-group'>
          <label class='col-sm-3 control-label'>Nama <font color='red'>*</font></label>
          <div class='col-sm-9'><input type='text' name='nama_debitur' id='nama_debitur' value=\"".$curr_data['nama']."\" class='form-control' required/></div>
        </div>                  
        <div class='form-group'>
          <label class='col-sm-3 control-label'>Domisili<font color='red'>*</font></label>
          <div class='col-sm-9'><textarea name='alamat_debitur' id='alamat_domisili_debitur' class='form-control' required>".$curr_data['alamat_debitur']."</textarea></div>
        </div>
        
      </div>
      <div class='col-md-6'>            
        <div class='form-group'>
          <label class='col-sm-3 control-label'>No. Telepon</label>
          <div class='col-sm-9'><input type='text' name='no_tlp_kantor_debitur' id='no_tlp_debitur' value=\"".$curr_data['no_tlp_kantor']."\" class='form-control'/></div>
        </div>
        <div class='form-group'>
          <label class='col-sm-3 control-label'>No. Ponsel <font color='red'>*</font></label>
          <div class='col-sm-9'><input type='text' name='no_ponsel_debitur' id='no_ponsel_debitur' value=\"".$curr_data['no_ponsel']."\" class='form-control' required/></div>
        </div>
      </div>
  </div>
  <div class='ln_solid'></div>
  <div class='form-group'>
      <div class='col-md-12 col-sm-12 col-xs-12' align='right'>
          <button type='button' class='btn btn-danger' id='close-modal-form' data-dismiss='modal'>Batal</button>
          <button type='submit' class='btn btn-success'>Simpan</button>
      </div>
  </div>";
?>