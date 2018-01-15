<?php
	echo "
	<div class='row'>
      <div class='col-md-6'>
        <div class='form-group'>
          <label class='col-sm-3 control-label'>Alamat Objek <font color='red'>*</font></label>
          <div class='col-sm-9'><textarea name='alamat_op' id='alamat_op' class='form-control' required>".$curr_data['alamat_properti']."</textarea></div>
        </div>
        <div class='form-group'>                  
          <label class='col-sm-3 control-label'>Jenis Objek <font color='red'>*</font></label>
          <div class='col-sm-9'><select name='fk_jenis_objek_op' id='jenis_op' class='form-control' required>
              <option value='' selected></option>";
              $data = $DML1->fetchAllData();
              foreach($data as $row)
              {
                  $selected = ($curr_data['fk_jenis_objek']==$row['id_jenis_objek']?'selected':'');
                  echo "<option value='".$row['id_jenis_objek']."' ".$selected.">".$row['id_jenis_objek']." - ".$row['jenis_objek']."</option>";
              }
          echo "</select></div>
        </div>
        <div class='form-group'>
          <label class='col-sm-3 control-label'>Provinsi <font color='red'>*</font></label>
          <div class='col-sm-9'>
            <select name='provinsi_op' id='provinsi_op' class='form-control' onchange=\"get_regencies_list(this.value)\" required>";
              $data = $DML7->fetchAllData();
              foreach($data as $row)
              {
                $selected = (strtolower($curr_data['provinsi'])==strtolower($row['name'])?'selected':'');
                echo "<option value='".$row['id']."' ".$selected.">".$row['name']."</option>";
              }
            echo "
            </select>
          </div>
        </div>
        <div class='form-group'>
          <label class='col-sm-3 control-label'>Kota <font color='red'>*</font></label>
          <div class='col-sm-9'>
            <select name='kota_op' id='kota_op' class='form-control' onchange=\"get_districts_list(this.value)\" required>";
              $data = $DML8->fetchAllData();
              foreach($data as $row)
              {
                $selected = (strtolower($curr_data['kota'])==strtolower($row['name'])?'selected':'');
                echo "<option value='".$row['id']."' ".$selected.">".$row['name']."</option>";
              }
            echo "
            </select>
          </div>
        </div>
        
      </div><!-- /.col -->
      <div class='col-md-6'>                  
        <div class='form-group'>
          <label class='col-sm-3 control-label'>Kecamatan <font color='red'>*</font></label>
          <div class='col-sm-9'>
            <select name='kecamatan_op' id='kecamatan_op' onchange=\"get_villages_list(this.value)\" class='form-control' required>
              <option value='' selected></option>";
              $data = $DML2->fetchAllData();
              foreach($data as $row)
              {
                  $selected = (strtolower($curr_data['kecamatan'])==strtolower($row['name'])?'selected':'');
                  echo "<option value='".$row['id']."' ".$selected.">".$row['name']."</option>";
              }
            echo "
            </select>
          </div>
        </div>
        <div class='form-group'>
          <label class='col-sm-3 control-label'>Kelurahan <font color='red'>*</font></label>
          <div class='col-sm-9'>
            <select name='kelurahan_op' id='kelurahan_op' onchange=\"get_postal_code(this.value);\" class='form-control' required>
              <option value=''></option>";
              $data = $DML3->fetchData("SELECT a.* FROM ref_villages as a INNER JOIN (SELECT id FROM ref_districts WHERE(name='".$curr_data['kecamatan']."')) as b 
                                        ON (a.district_id=b.id)");
              foreach($data as $row)
              {
                  $selected = (strtolower($curr_data['kelurahan'])==strtolower($row['name'])?'selected':'');
                  echo "<option value='".$row['id']."' ".$selected.">".$row['name']."</option>";
              }
            echo "</select>
          </div>
        </div>            
        <div class='form-group'>
          <label class='col-sm-3 control-label'>Kode Pos <font color='red'>*</font></label>
          <div class='col-sm-9'><input type='text' name='kd_pos_op' id='kd_pos_op' class='form-control' value='".$curr_data['kd_pos']."' required/></div>
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