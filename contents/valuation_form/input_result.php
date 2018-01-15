<?php
	echo "
  <div class='box'>
  	<div class='box-header with-border'>
        <h3 class='box-title'>Hasil Penginputan</h3>
        <div class='box-tools pull-right'>        
        </div>
    </div><!-- /.box-header -->
  	<div class='box-body'>
  		<div class='row'>
  			<div class='col-md-4'>
  				<div class='callout callout-info' style='margin-bottom: 0!important;'>
		            <h4><i class='fa fa-info'></i> No. Penilaian : ".$result['no_penilaian']."</h4>		            
		            No. Penilaian di atas dapat digunakan untuk melakukan pencarian
		        </div>		        
  			</div>
  			<div class='col-md-8'>
  				<table class='table table-bordered'>
  					<tbody>
  						<tr><td>Calon Debitur</td><td>".$result['debitur']."</td></tr>
  						<tr><td colspan='2'><b>Lokasi Properti</b></td></tr>
  						<tr><td>Alamat</td><td>".$result['alamat']."</td></tr>
  						<tr><td>Kelurahan</td><td>".$result['kelurahan']."</td></tr>
  						<tr><td>Kecamatan</td><td>".$result['kecamatan']."</td></tr>
              <tr><td>Kota/Kabupaten</td><td>".$result['dt2']."</td></tr>
              <tr><td>Provinsi</td><td>".$result['provinsi']."</td></tr>
  					</tbody>
  				</table>
  				<button type='button' class='btn btn-default' onclick=\"load_content();\">Input Baru</button>
  			</div>
  		</div>
  	</div>
  </div>";
?>