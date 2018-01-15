<?php
    $ct=$_GET['ct'];
    $menu_id = $uc->get_menu_id('url','index.php?ct='.$ct);
    $fn = $_CONTENT_FOLDER_NAME[1];

?>

<script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/maskedinput/jquery.maskedinput.min.js"></script>

<script type="text/javascript">
    var fn = "<?php echo $fn; ?>";    

    function load_content()
    {        
        ajax_manipulate.reset_object();
        var data_ajax = <?php echo "new Array('menu_id=".$menu_id."','fn='+fn)"; ?>;
        var masked_id = new Array(['tgl_penugasan','tgl_pemeriksaan','tgl_terbit_sertifikat_tanah','tgl_jatuh_tempo_sertifikat_tanah','tgl_gs_su_tanah','tgl_laporan_penugasan','tgl_survei_penugasan']);
        var masked_rules = new Array(['99-99-9999','99-99-9999','99-99-9999','99-99-9999','99-99-9999','99-99-9999','99-99-9999']);
        ajax_manipulate.set_plugin_datatable(false).set_plugin_datepicker(true).set_plugin_maskedinput(true).set_masked_id(masked_id).set_masked_rules(masked_rules)
        .set_url('contents/'+fn+'/content.php').set_data_ajax(data_ajax).set_loading('#content-loading').set_content('#main-content').request_ajax();
    }
    
    function get_regencies_list(province_id)
    {
        ajax_manipulate.reset_object();
        var data_ajax = new Array('province_id='+province_id);
        ajax_manipulate.set_plugin_datatable(false).set_url('contents/'+fn+'/regencies_list.php').set_data_ajax(data_ajax).set_loading('#preloadAnimation').set_content('#kota_op').request_ajax();
        $('#kecamatan_op').html("<option value=''>Pilih Kota/Kabupaten Terlebih Dahulu</option>");
        $('#kelurahan_op').html("<option value=''>Pilih Kecamatan Terlebih Dahulu</option>");
    }

    function get_districts_list(regency_id)
    {
        ajax_manipulate.reset_object();
        var data_ajax = new Array('regency_id='+regency_id);
        ajax_manipulate.set_plugin_datatable(false).set_url('contents/'+fn+'/districts_list.php').set_data_ajax(data_ajax).set_loading('#preloadAnimation').set_content('#kecamatan_op').request_ajax();
        $('#kelurahan_op').html("<option value=''>Pilih Kecamatan Terlebih Dahulu</option>");
    }

    function get_villages_list(district_id)
    {
        ajax_manipulate.reset_object();
        var data_ajax = new Array('district_id='+district_id);
        ajax_manipulate.set_plugin_datatable(false).set_url('contents/'+fn+'/villages_list.php').set_data_ajax(data_ajax).set_loading('#preloadAnimation').set_content('#kelurahan_op').request_ajax();
    }

    function get_postal_code(village_id)
    {
        ajax_manipulate.reset_object();
        var data_ajax = new Array('village_id='+village_id);
        ajax_manipulate.set_plugin_datatable(false).set_url('contents/'+fn+'/postal_code.php').set_data_ajax(data_ajax).set_loading('#preloadAnimation').set_content('#kd_pos_op').request_ajax(0,2);
    }

    function fill_dummy_data()
    {        

        document.valuation_form.alamat_op.value='Akasia Valley Blok C No. 15';
        document.valuation_form.fk_jenis_objek_op.value='1';        
        document.valuation_form.kd_pos_op.value='15313';

        document.valuation_form.nama_debitur.value='Ayu Citra Rachmawati';
        document.valuation_form.alamat_debitur.value='-';        
        document.valuation_form.no_tlp_kantor_debitur.value='-';
        document.valuation_form.no_ponsel_debitur.value='08170009886';

        document.valuation_form.no_penugasan.value='1971/Cpt.I/OP-LA/VI/2016';
        document.valuation_form.tgl_penugasan.value='09-06-2016';
        document.valuation_form.keperluan_penugasan.value='AGUNAN';
        document.valuation_form.fk_perusahaan_penunjuk_penugasan.value='1';        
        document.valuation_form.nama_pengorder1_penugasan.value='Iswanto';
        document.valuation_form.jabatan_pengorder1_penugasan.value='DBM Supporting';
        document.valuation_form.nama_pengorder2_penugasan.value='Insan Nuryatim';
        document.valuation_form.jabatan_pengorder2_penugasan.value='Operation Unit Head';

        document.valuation_form.tgl_pemeriksaan.value='09-06-2016';
        document.valuation_form.status_objek_pemeriksaan.value='Dihuni';
        document.valuation_form.dihuni_oleh_pemeriksaan.value='Kel. Ibu Ayu Citra';
        document.valuation_form.klien_pendamping_lokasi_pemeriksaan.value='Ibu Julaiha';
        document.valuation_form.depan_pemeriksaan.value='Jalan Lingkungan';
        document.valuation_form.belakang_pemeriksaan.value='Rumah Tinggal';
        document.valuation_form.kanan_pemeriksaan.value='Jalan Lingkungan';
        document.valuation_form.kiri_pemeriksaan.value='Rumah Tinggal';

        document.valuation_form.fk_jenis_sertifikat_tanah.value='1';
        document.valuation_form.no_sertifikat_tanah.value='02255';
        document.valuation_form.tgl_terbit_sertifikat_tanah.value='06-03-2014';
        document.valuation_form.tgl_jatuh_tempo_sertifikat_tanah.value='';
        document.valuation_form.no_gs_su_tanah.value='65/Kademangan/2016';
        document.valuation_form.tgl_gs_su_tanah.value='20-02-2014';
        document.valuation_form.hubungan_dengan_calon_nasabah_tanah.value='Pemilik Rumah';
        document.valuation_form.luas_tanah.value='90';
        document.valuation_form.prosentase_bangunan_tanah.value='49';
        document.valuation_form.tinggi_halaman_thd_jalan_tanah.value='10';
        document.valuation_form.tinggi_halaman_thd_lantai_tanah.value='10';
        document.valuation_form.keadaan_halaman_tanah.value='Cukup Terawat';
    }
    $(window).load(function() {
       	load_content();
    });
</script>

<section class="content-header">
  <h1>
    Penilaian Properti
    <small>Formulir Dasar</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Penilain Properti</a></li>
    <li class="active">Formulir Dasar</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div id="content-loading" align="center"><img src="assets/images/ajax-loaders/ajax-loader-1.gif"/>&nbsp;Mohon tunggu sejenak...</div>
			<div id="main-content"></div>
		</div>
	</div>
</section>
<!-- /.content -->