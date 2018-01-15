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
		            <h4><i class='fa fa-info'></i> No. BCT : ".$result['no_bct']."</h4>
		            No. BCT di atas dapat digunakan untuk melakukan pencarian
		        </div>		        
  			</div>
  			<div class='col-md-8'>
  				<table class='table table-bordered'>
  					<tbody>
              <tr><td colspan='2'><b>Spesifikasi Bangunan</b></td></tr>
              <tr><td>Properti</td><td>".$result['properti']."</td></tr>
              <tr><td>Klasifikasi</td><td>".$result['klasifikasi']."</td></tr>
  						<tr><td>Lokasi Properti</td><td>".$result['nm_perumahan']."</td></tr>
  						<tr><td colspan='2'><b>Alamat Properti</b></td></tr>
  						<tr><td>Alamat</td><td>".$result['alamat']."</td></tr>
  						<tr><td>Kelurahan</td><td>".$result['kelurahan']."</td></tr>
  						<tr><td>Kecamatan</td><td>".$result['kecamatan']."</td></tr>
              <tr><td>Kota/Kabupaten</td><td>".$result['kota']."</td></tr>
              <tr><td>Propinsi</td><td>".$result['provinsi']."</td></tr>
  					</tbody>
  				</table>
  				<button type='button' class='btn btn-default' onclick=\"load_content();\">Input Baru</button>
  				<button type='button' class='btn btn-default'>Edit Data</button>
  			</div>
  		</div>
  	</div>
  </div>";
?>