<?php
    $ct=$_GET['ct'];
    $menu_id = $uc->get_menu_id('url','index.php?ct='.$ct);
    $fn = $_CONTENT_FOLDER_NAME[2];

?>
<script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/maskedinput/jquery.maskedinput.min.js"></script>

<script type="text/javascript">
    var fn = "<?php echo $fn; ?>";    

    function load_form_content(id)
    {
        ajax_manipulate.reset_object();
        var masked_id = new Array(['tgl_penugasan','tgl_pemeriksaan','tgl_terbit_sertifikat_tanah','tgl_jatuh_tempo_sertifikat_tanah','tgl_gs_su_tanah','tgl_laporan_penugasan','tgl_survei_penugasan']);
        var masked_rules = new Array(['99-99-9999','99-99-9999','99-99-9999','99-99-9999','99-99-9999','99-99-9999','99-99-9999']);
        ajax_manipulate.set_plugin_datatable(false).set_plugin_datepicker(true).set_plugin_maskedinput(true).set_masked_id(masked_id).set_masked_rules(masked_rules)
        .set_url('contents/'+fn+'/edit_form.php').set_id_input(id).set_input_ajax('ajax-req-dt').set_data_ajax().set_loading('#form-loading').set_content('#form-content').request_ajax();
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
    
    function load_content()
    {
        ajax_manipulate.reset_object();
        var data_ajax = <?php echo "new Array('menu_id=".$menu_id."','fn='+fn)"; ?>;
        ajax_manipulate.set_plugin_datatable(true).set_url('contents/'+fn+'/content.php').set_data_ajax(data_ajax).set_loading('#content-loading').set_content('#main-content').request_ajax();
    }
    
    $(window).load(function() {
       	load_content();
    });
</script>
<section class="content-header">
  <h1>
    Penilaian Properti 
    <small>Manajemen Data Dasar</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Penilaian Properti</a></li>
    <li class="active">Manajemen Data Dasar</li>
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

<!-- MODAL -->
<div class="modal fade" id="formModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="defModalHead">Form Perubahan Data Dasar</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="form-loading" class="" align="center"><img src="assets/images/ajax-loaders/ajax-loader-1.gif"/></div>
                    <div class="col-md-12 col-sm-12 col-xs-12" id="form-content">
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>
<!-- END OF MODAL -->