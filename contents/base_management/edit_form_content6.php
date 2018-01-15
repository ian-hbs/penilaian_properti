<?php
	echo "
	<div class='row'>
        <div class='col-md-6'>
            <div class='form-group'>
              <label class='col-sm-3 control-label'>No. Laporan <font color='red'>*</font></label>
              <div class='col-sm-9'><input type='text' name='no_laporan_penugasan' value=\"".$curr_data['no_laporan']."\" id='no_laporan_penugasan' class='form-control' required/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-3 control-label'>Tgl. Laporan <font color='red'>*</font></label>
              <div class='col-sm-9'><input type='text' id='tgl_laporan_penugasan' name='tgl_laporan_penugasan' value='".$curr_data['tgl_laporan']."' class='form-control datepicker' required/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-3 control-label'>Tgl. Survei <font color='red'>*</font></label>
              <div class='col-sm-9'><input type='text' id='tgl_survei_penugasan' name='tgl_survei_penugasan' value='".$curr_data['tgl_survei']."' class='form-control datepicker' required/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-3 control-label'>Reviewer I<font color='red'>*</font></label>
              <div class='col-sm-9'>
                <select name='reviewer1_penugasan' id='reviewer1_penugasan' class='form-control' required>
                <option value='' selected></option>";
                  $data = $DML5->fetchAllData();
                  foreach($data as $row)
                  {
                      $selected = ($curr_data['reviewer1']==$row['id_penilai']?'selected':'');
                      echo "<option value='".$row['id_penilai']."' ".$selected.">".$row['nama']."</option>";
                  }
                echo "</select>
              </div>
            </div>            
          </div><!-- /.col -->
          <div class='col-md-6'>
            <div class='form-group'>
              <label class='col-sm-3 control-label'>Reviewer II <font color='red'>*</font></label><br />
              <div class='col-sm-9'>
                <select name='reviewer2_penugasan' id='reviewer2_penugasan' class='form-control' required>
                <option value='' selected></option>";
                  $data = $DML5->fetchAllData();
                  foreach($data as $row)
                  {
                      $selected = ($curr_data['reviewer2']==$row['id_penilai']?'selected':'');
                      echo "<option value='".$row['id_penilai']."' ".$selected.">".$row['nama']."</option>";
                  }
                echo "</select>
              </div>
            </div>            
            <div class='form-group'>
              <label class='col-sm-3 control-label'>Penilai I <font color='red'>*</font></label>
              <div class='col-sm-9'>
                <select name='penilai1_penugasan' id='penilai1_penugasan' class='form-control' required>
                <option value='' selected></option>";
                  $data = $DML5->fetchAllData();
                  foreach($data as $row)
                  {
                      $selected = ($curr_data['penilai1']==$row['id_penilai']?'selected':'');
                      echo "<option value='".$row['id_penilai']."' ".$selected.">".$row['nama']."</option>";
                  }
                echo "</select>
              </div>
            </div>
            <div class='form-group'>
              <label class='col-sm-3 control-label'>Penilai II <font color='red'>*</font></label>
              <div class='col-sm-9'>
                <select name='penilai2_penugasan' id='penilai2_penugasan' class='form-control' required>
                <option value='' selected></option>";
                  $data = $DML5->fetchAllData();
                  foreach($data as $row)
                  {
                      $selected = ($curr_data['penilai2']==$row['id_penilai']?'selected':'');
                      echo "<option value='".$row['id_penilai']."' ".$selected.">".$row['nama']."</option>";
                  }
                echo "</select>
              </div>
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