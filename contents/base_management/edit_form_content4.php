<?php
	echo "
	<div class='row'>
        <div class='col-md-6'>                  
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Tanggal <font color='red'>*</font></label>
              <div class='col-sm-8'><input type='text' id='tgl_pemeriksaan' name='tgl_pemeriksaan' value='".$curr_data['tgl_pemeriksaan']."' class='form-control datepicker' required/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Status Objek <font color='red'>*</font></label><br />
              <div class='col-sm-8'>";
              $checked1 = ($curr_data['status_objek']=='Kosong'?'checked':'');
              $checked2 = ($curr_data['status_objek']=='Dihuni'?'checked':'');
              echo "
              <input type='radio' name='status_objek_pemeriksaan' id='status_objek_pemeriksaan1' value='Kosong' ".$checked1." required/>Kosong&nbsp;
              <input type='radio' name='status_objek_pemeriksaan' id='status_objek_pemeriksaan2' value='Dihuni' ".$checked2." required/>Dihuni&nbsp;</div>
            </div>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Dihuni Oleh</label>
              <div class='col-sm-8'><input type='text' name='dihuni_oleh_pemeriksaan' id='dihuni_oleh_pemeriksaan' value=\"".$curr_data['dihuni_oleh']."\" class='form-control'/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Pendamping Lokasi</label>
              <div class='col-sm-8'><input type='text' name='klien_pendamping_lokasi_pemeriksaan' value=\"".$curr_data['klien_pendamping_lokasi']."' id='klien_pendamping_lokasi_pemeriksaan\" class='form-control'/></div>
            </div>                  
          </div><!-- /.col -->
          <div class='col-md-6'>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Batas Depan <font color='red'>*</font></label>
              <div class='col-sm-8'><input type='text' name='depan_pemeriksaan' id='depan_pemeriksaan' value=\"".$curr_data['depan']."\" class='form-control' required/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Batas Belakang <font color='red'>*</font></label>
              <div class='col-sm-8'><input type='text' name='belakang_pemeriksaan' id='belakang_pemeriksaan' value=\"".$curr_data['belakang']."\" class='form-control' required/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Batas Kanan <font color='red'>*</font></label>
              <div class='col-sm-8'><input type='text' name='kanan_pemeriksaan' id='kanan_pemeriksaan' value=\"".$curr_data['kanan']."\" class='form-control' required/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Batas Kiri <font color='red'>*</font></label>
              <div class='col-sm-8'><input type='text' name='kiri_pemeriksaan' id='kiri_pemeriksaan' value=\"".$curr_data['kiri']."\" class='form-control' required/></div>
            </div>
            <div class='form-group'>
              <label class='col-sm-4 control-label'>Keterangan</label>
              <div class='col-sm-8'><textarea name='keterangan_pemeriksaan' id='keterangan_pemeriksaan' class='form-control'>".$curr_data['keterangan']."</textarea></div>
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