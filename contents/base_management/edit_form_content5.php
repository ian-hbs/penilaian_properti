<?php
	echo "
	<div class='row'>
        <div class='col-md-6'>                  
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Jenis Sertifikat <font color='red'>*</font></label>
              <div class='col-sm-8'><select name='fk_jenis_sertifikat_tanah' id='fk_jenis_sertifikat_tanah' class='form-control' required>
              <option value=''></option>";
              $data = $DML4->fetchAllData();
              foreach($data as $row)
              {
                  $selected = ($curr_data['fk_jenis_sertifikat']==$row['id_jenis_sertifikat']?'selected':'');
                  echo "<option value='".$row['id_jenis_sertifikat']."' ".$selected.">".$row['id_jenis_sertifikat']." - ".$row['jenis_sertifikat']."</option>";
              }
            echo "</select></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>No. Sertifikat <font color='red'>*</font></label>
              <div class='col-sm-8'><input type='text' name='no_sertifikat_tanah' id='no_sertifikat_tanah' value=\"".$curr_data['no_sertifikat']."\" class='form-control' required/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Tgl. Terbit</label>
              <div class='col-sm-8'><input type='text' name='tgl_terbit_sertifikat_tanah' id='tgl_terbit_sertifikat_tanah' value='".$curr_data['tgl_terbit_sertifikat']."' class='form-control datepicker' required/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Jatuh Tempo</label>
              <div class='col-sm-8'><input type='text' name='tgl_jatuh_tempo_sertifikat_tanah' id='tgl_jatuh_tempo_sertifikat_tanah' value='".$curr_data['tgl_jatuh_tempo_sertifikat']."' class='form-control datepicker'/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>No. GS/SU <font color='red'>*</font></label>
              <div class='col-sm-8'><input type='text' name='no_gs_su_tanah' id='no_gs_su_tanah' value=\"".$curr_data['no_gs_su']."\" class='form-control' required/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Tgl. GS/SU</label>
              <div class='col-sm-8'><input type='text' name='tgl_gs_su_tanah' id='tgl_gs_su_tanah' class='form-control datepicker' value='".$curr_data['tgl_gs_su']."' required/></div>
            </div>
          </div><!-- /.col -->
          <div class='col-md-6'>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Hub. Dengan Calon Nasabah <font color='red'>*</font></label>
              <div class='col-sm-8'><input type='text' name='hubungan_dengan_calon_nasabah_tanah' id='hubungan_dengan_calon_nasabah_tanah' value=\"".$curr_data['hubungan_dengan_calon_nasabah']."\" class='form-control' required/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Luas Tanah <font color='red'>*</font></label>
              <div class='col-sm-8'><input type='text' name='luas_tanah' id='luas_tanah' class='form-control' value='".$curr_data['luas_tanah']."' onkeypress=\"return only_number(event,this);\" required/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Prosentase Bangunan <font color='red'>*</font></label>
              <div class='col-sm-8'><input type='text' name='prosentase_bangunan_tanah' id='prosentase_bangunan_tanah' value='".$curr_data['prosentase_bangunan']."' class='form-control' onkeypress=\"return only_number(event,this);\" required/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>T. Hal. Terhadap Jalan <font color='red'>*</font></label>
              <div class='col-sm-8'><input type='text' name='tinggi_halaman_thd_jalan_tanah' id='tinggi_halaman_thd_jalan_tanah' value='".$curr_data['tinggi_halaman_thd_jalan']."' class='form-control' onkeypress=\"return only_number(event,this);\" required/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>T. Hal. Terhadap Lantai <font color='red'>*</font></label>
              <div class='col-sm-8'><input type='text' name='tinggi_halaman_thd_lantai_tanah' id='tinggi_halaman_thd_lantai_tanah' value='".$curr_data['tinggi_halaman_thd_lantai']."' class='form-control' onkeypress=\"return only_number(event,this);\" required/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Keadaan Hal. <font color='red'>*</font></label>
              <div class='col-sm-8'><input type='text' name='keadaan_halaman_tanah' id='keadaan_halaman_tanah' value=\"".$curr_data['keadaan_halaman']."\" class='form-control' required/></div>
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