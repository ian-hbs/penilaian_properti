<script type="text/javascript">
    var form_id = '<?php echo $form_id;?>';
    var $form = $('#'+form_id);
    var stat = $form.validate();    
    var fn = "<?php echo $fn; ?>";

    $form.submit(function(){
        if(stat.checkForm())
        {
            ajax_manipulate.reset_object();
            ajax_manipulate.set_plugin_datatable(true)
                          .set_content('#form-content')
                          .set_loading('#preloadAnimation')                           
                          .set_form($form)
                          .submit_ajax('menambah');            
            return false;
        }
    });
</script>
<?php
  
  $DML1 = new DML('ref_jenis_objek',$db);
  $DML2 = new DML('ref_jenis_sertifikat',$db);
  $DML3 = new DML('ref_provinces',$db);
  $DML4 = new DML('ref_penilai',$db);
  $DML5 = new DML('ref_perusahaan_penunjuk',$db);

  
  $SYSTEM_PARAMS = $_APP_PARAM['system_params'];

  if($addAccess)
  {
    echo "  
    <div class='box'>
      <form name='valuation_form' class='form-horizontal' id='".$form_id."' method='POST' action='contents/".$fn."/manipulating.php'>
      <div class='box-header with-border'>
          <h3 class='box-title'><a href='#' onclick=\"fill_dummy_data()\">Perusahaan Jasa Penilai</a></h3>
          <div class='box-tools pull-right'>        
          </div>
        </div><!-- /.box-header -->
      <div class='box-body'>
          <input type='hidden' name='fn' value='".$fn."'/>
          <input type='hidden' name='menu_id' value='".$menu_id."'/>      
          <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Perusahaan <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='nama_perusahaan_penilai' id='nama_perusahaan_penilai' class='form-control autofill-bg' value='".$SYSTEM_PARAMS['nama_instansi']."' readonly/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>No. Ijin Usaha <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='no_ijin_usaha_penilai' id='no_ijin_usaha_penilai' class='form-control autofill-bg' value='".$SYSTEM_PARAMS['no_ijin_usaha']."' readonly/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Alamat <font color='red'>*</font></label>
                  <div class='col-sm-9'><textarea name='alamat_perusahaan_penilai' id='alamat_perusahaan_penilai' class='form-control autofill-bg' readonly>".$SYSTEM_PARAMS['alamat_instansi']."</textarea></div>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>No. Telpon <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='no_tlp_penilai' id='no_tlp_penilai' class='form-control autofill-bg' value='".$SYSTEM_PARAMS['tlp_instansi']."' readonly/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>No. Fax</label>
                  <div class='col-sm-9'><input type='text' name='no_fax_penilai' id='no_fax_penilai' value='".$SYSTEM_PARAMS['fax_instansi']."' class='form-control autofill-bg' readonly/></div>
                </div>
              </div>
          </div>
        </div>
        <div class='box-header with-border'>
          <h3 class='box-title'>Objek Penilaian</h3>
          <div class='box-tools pull-right'>        
          </div>
        </div><!-- /.box-header -->
        <div class='box-body'>
          <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Alamat Objek <font color='red'>*</font></label>
                  <div class='col-sm-9'><textarea name='alamat_op' id='alamat_op' class='form-control' required></textarea></div>
                </div>
                <div class='form-group'>                  
                  <label class='col-sm-3 control-label'>Jenis Objek <font color='red'>*</font></label>
                  <div class='col-sm-9'><select name='fk_jenis_objek_op' id='jenis_op' class='form-control' required>
                      <option value='' selected></option>";
                      $data = $DML1->fetchAllData();
                      foreach($data as $row)
                      {                            
                          echo "<option value='".$row['id_jenis_objek']."'>".$row['id_jenis_objek']." - ".$row['jenis_objek']."</option>";
                      }
                  echo "</select></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Provinsi <font color='red'>*</font></label>
                  <div class='col-sm-9'>
                    <select name='provinsi_op' id='provinsi_op' onchange=\"get_regencies_list(this.value);\" class='form-control' required>
                      <option value='' selected></option>";
                      $data = $DML3->fetchAllData();
                      foreach($data as $row)
                      {                            
                          echo "<option value='".$row['id']."'>".$row['name']."</option>";
                      }
                    echo "</select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Kota <font color='red'>*</font></label>
                  <div class='col-sm-9'>
                    <select name='kota_op' id='kota_op' class='form-control' onchange=\"get_districts_list(this.value);\" required>
                      <option value='' selected>- Pilih Provinsi Terlebih Dahulu -</option>
                    </select>
                  </div>
                </div>
                
              </div><!-- /.col -->
              <div class='col-md-6'>                  
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Kecamatan <font color='red'>*</font></label>
                  <div class='col-sm-9'>
                    <select name='kecamatan_op' id='kecamatan_op' onchange=\"get_villages_list(this.value)\" class='form-control' required>
                      <option value='' selected>- Pilih Kota Terlebih Dahulu -</option>
                    </select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Kelurahan <font color='red'>*</font></label>
                  <div class='col-sm-9'>
                    <select name='kelurahan_op' id='kelurahan_op' onchange=\"get_postal_code(this.value);\" class='form-control' required>
                      <option value=''>- Pilih Kecamatan Terlebih Dahulu -</option>
                    </select>
                  </div>
                </div>            
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Kode Pos <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='kd_pos_op' id='kd_pos_op' class='form-control' required/></div>
                </div>
              </div>
          </div>
        </div>
        <div class='box-header with-border'>
          <h3 class='box-title'>Debitur</h3>
          <div class='box-tools pull-right'>        
          </div>
        </div><!-- /.box-header -->
        <div class='box-body'>
            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Nama Debitur <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='nama_debitur' id='nama_debitur' class='form-control' required/></div>
                </div>                  
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Alamat Domisili<font color='red'>*</font></label>
                  <div class='col-sm-9'><textarea name='alamat_debitur' id='alamat_domisili_debitur' class='form-control' required></textarea></div>
                </div>
                
              </div>
              <div class='col-md-6'>            
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>No. Telepon</label>
                  <div class='col-sm-9'><input type='text' name='no_tlp_kantor_debitur' id='no_tlp_debitur' class='form-control'/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>No. Ponsel <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='no_ponsel_debitur' id='no_ponsel_debitur' class='form-control' required/></div>
                </div>
              </div>
            </div>
        </div>
        <div class='box-header with-border'>
          <h3 class='box-title'>Penugasan</h3>
          <div class='box-tools pull-right'>        
          </div>
        </div><!-- /.box-header -->
        <div class='box-body'>
          <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Nomor <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='no_penugasan' id='no_penugasan' class='form-control' required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Tanggal <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' id='tgl_penugasan' name='tgl_penugasan' class='form-control datepicker' required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Keperluan <font color='red'>*</font></label><br />
                  <div class='col-sm-9'><input type='radio' name='keperluan_penugasan' id='keperluan_penugasan1' value='KPR' checked required/>KPR&nbsp;
                  <input type='radio' name='keperluan_penugasan' id='keperluan_penugasan2' value='KP RUKO' required/>KP RUKO&nbsp;
                  <input type='radio' name='keperluan_penugasan' id='keperluan_penugasan3' value='AGUNAN' required/>AGUNAN&nbsp;</div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Per. Penunjuk <font color='red'>*</font></label>
                  <div class='col-sm-9'>
                    <select name='fk_perusahaan_penunjuk_penugasan' id='fk_perusahaan_penunjuk_penugasan' class='form-control' required>
                      <option value='' selected></option>";
                      $data = $DML5->fetchAllData();
                      foreach($data as $row)
                      {
                          echo "<option value='".$row['id_perusahaan_penunjuk']."'>".$row['perusahaan_penunjuk'].", ".$row['kantor_cabang']."</option>";
                      }
                    echo "
                    </select>
                  </div>
                </div>                
              </div><!-- /.col -->
              <div class='col-md-6'>                
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Pengorder 1 <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='nama_pengorder1_penugasan' id='nama_pengorder1_penugasan' class='form-control' required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Jababatan 1 <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='jabatan_pengorder1_penugasan' id='jabatan_pengorder1_penugasan' class='form-control' required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Pengorder 2</label>
                  <div class='col-sm-9'><input type='text' name='nama_pengorder2_penugasan' id='nama_pengorder2_penugasan' class='form-control'/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Jabatan 2</label>
                  <div class='col-sm-9'><input type='text' name='jabatan_pengorder2_penugasan' id='jabatan_pengorder2_penugasan' class='form-control'/></div>
                </div>
              </div><!-- /.col -->
          </div>
        </div>
        <div class='box-header with-border'>
          <h3 class='box-title'>Hasil Pemeriksaan</h3>
          <div class='box-tools pull-right'>        
          </div>
        </div><!-- /.box-header -->
        <div class='box-body'>
          <div class='row'>
              <div class='col-md-6'>                  
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Tanggal<font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' id='tgl_pemeriksaan' name='tgl_pemeriksaan' class='form-control datepicker' required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Status Objek <font color='red'>*</font></label><br />
                  <div class='col-sm-9'><input type='radio' name='status_objek_pemeriksaan' id='status_objek_pemeriksaan1' value='Kosong' checked required/>Kosong&nbsp;
                  <input type='radio' name='status_objek_pemeriksaan' id='status_objek_pemeriksaan2' value='Dihuni' required/>Dihuni&nbsp;</div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Dihuni Oleh</label>
                  <div class='col-sm-9'><input type='text' name='dihuni_oleh_pemeriksaan' id='dihuni_oleh_pemeriksaan' class='form-control'/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Pendamping Lokasi</label>
                  <div class='col-sm-9'><input type='text' name='klien_pendamping_lokasi_pemeriksaan' id='klien_pendamping_lokasi_pemeriksaan' class='form-control'/></div>
                </div>                  
              </div><!-- /.col -->
              <div class='col-md-6'>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Batas Depan <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='depan_pemeriksaan' id='depan_pemeriksaan' class='form-control' required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Batas Belakang <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='belakang_pemeriksaan' id='belakang_pemeriksaan' class='form-control' required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Batas Kanan <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='kanan_pemeriksaan' id='kanan_pemeriksaan' class='form-control' required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Batas Kiri <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='kiri_pemeriksaan' id='kiri_pemeriksaan' class='form-control' required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Keterangan</label>
                  <div class='col-sm-9'><textarea name='keterangan_pemeriksaan' id='keterangan_pemeriksaan' class='form-control'></textarea></div>
                </div>
              </div><!-- /.col -->
          </div>
        </div>
        <div class='box-header with-border'>
          <h3 class='box-title'>Data Tanah</h3>
          <div class='box-tools pull-right'>        
          </div>
        </div><!-- /.box-header -->
        <div class='box-body'>
          <div class='row'>
              <div class='col-md-6'>                  
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Jenis Sertifikat <font color='red'>*</font></label>
                  <div class='col-sm-9'><select name='fk_jenis_sertifikat_tanah' id='fk_jenis_sertifikat_tanah' class='form-control' required>
                  <option value=''></option>";
                  $data = $DML2->fetchAllData();
                  foreach($data as $row)
                  {                            
                      echo "<option value='".$row['id_jenis_sertifikat']."'>".$row['id_jenis_sertifikat']." - ".$row['jenis_sertifikat']."</option>";
                  }
                echo "</select></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>No. Sertifikat <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='no_sertifikat_tanah' id='no_sertifikat_tanah' class='form-control' required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Tgl. Terbit</label>
                  <div class='col-sm-9'><input type='text' name='tgl_terbit_sertifikat_tanah' id='tgl_terbit_sertifikat_tanah' class='form-control datepicker' required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Jatuh Tempo</label>
                  <div class='col-sm-9'><input type='text' name='tgl_jatuh_tempo_sertifikat_tanah' id='tgl_jatuh_tempo_sertifikat_tanah' class='form-control datepicker'/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>No. GS/SU <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='no_gs_su_tanah' id='no_gs_su_tanah' class='form-control' required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Tgl. GS/SU</label>
                  <div class='col-sm-9'><input type='text' name='tgl_gs_su_tanah' id='tgl_gs_su_tanah' class='form-control datepicker' required/></div>
                </div>
              </div><!-- /.col -->
              <div class='col-md-6'>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Hub. Dengan Calon Nasabah <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='hubungan_dengan_calon_nasabah_tanah' id='hubungan_dengan_calon_nasabah_tanah' class='form-control' required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Luas Tanah <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='luas_tanah' id='luas_tanah' class='form-control' onkeypress=\"return only_number(event,this);\" required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Prosentase Bangunan <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='prosentase_bangunan_tanah' id='prosentase_bangunan_tanah' class='form-control' onkeypress=\"return only_number(event,this);\" required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>T. Hal. Terhadap Jalan <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='tinggi_halaman_thd_jalan_tanah' id='tinggi_halaman_thd_jalan_tanah' class='form-control' onkeypress=\"return only_number(event,this);\" required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>T. Hal. Terhadap Lantai <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='tinggi_halaman_thd_lantai_tanah' id='tinggi_halaman_thd_lantai_tanah' class='form-control' onkeypress=\"return only_number(event,this);\" required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Keadaan Hal. <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='keadaan_halaman_tanah' id='keadaan_halaman_tanah' class='form-control' required/></div>
                </div>
              </div><!-- /.col -->
          </div>  
      </div>
      <div class='box-header with-border'>
          <h3 class='box-title'>Laporan Penilaian</h3>
          <div class='box-tools pull-right'>
          </div>
        </div><!-- /.box-header -->
        <div class='box-body'>
          <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>No. Laporan <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' name='no_laporan_penugasan' id='no_laporan_penugasan' class='form-control' required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Tgl. Laporan <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' id='tgl_laporan_penugasan' name='tgl_laporan_penugasan' class='form-control datepicker' required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Tgl. Survei <font color='red'>*</font></label>
                  <div class='col-sm-9'><input type='text' id='tgl_survei_penugasan' name='tgl_survei_penugasan' class='form-control datepicker' required/></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Reviewer I <font color='red'>*</font></label>
                  <div class='col-sm-9'>
                    <select name='reviewer1_penugasan' id='reviewer1_penugasan' class='form-control' required>
                    <option value='' selected></option>";
                      $data = $DML4->fetchAllData();
                      foreach($data as $row)
                      {                            
                          echo "<option value='".$row['id_penilai']."'>".$row['nama']."</option>";
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
                      $data = $DML4->fetchAllData();
                      foreach($data as $row)
                      {                            
                          echo "<option value='".$row['id_penilai']."'>".$row['nama']."</option>";
                      }
                    echo "</select>
                  </div>
                </div>            
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Penilai I <font color='red'>*</font></label>
                  <div class='col-sm-9'>
                    <select name='penilai1_penugasan' id='penilai1_penugasan' class='form-control' required>
                    <option value='' selected></option>";
                      $data = $DML4->fetchAllData();
                      foreach($data as $row)
                      {                            
                          echo "<option value='".$row['id_penilai']."'>".$row['nama']."</option>";
                      }
                    echo "</select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Penilai II <font color='red'>*</font></label>
                  <div class='col-sm-9'>
                    <select name='penilai2_penugasan' id='penilai2_penugasan' class='form-control' required>
                    <option value='' selected></option>";
                      $data = $DML4->fetchAllData();
                      foreach($data as $row)
                      {                            
                          echo "<option value='".$row['id_penilai']."'>".$row['nama']."</option>";
                      }
                    echo "</select>
                  </div>
                </div>            
              </div><!-- /.col -->
          </div>
        </div>
      <div class='box-footer'>
        <button type='reset' class='btn btn-default'>Batal</button>
        <button type='submit' class='btn btn-info pull-right'>Simpan</button>
      </div><!-- /.box-footer -->
      </form>
    </div>";
  }
  else
  {
    echo "    
      <div class='row'>
          <div class='col-md-12'>
              <div class='alert alert-warning' role='alert'>
              <button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
              Anda tidak memiliki akses untuk menambah data
              </div>
          </div>
      </div>";
  }
?>