<?php
    $ct=$_GET['ct'];
    $menu_id = $uc->get_menu_id('url','index.php?ct='.$ct);
    $fn = $_CONTENT_FOLDER_NAME[40];

?>
<script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/maskedinput/jquery.maskedinput.min.js"></script>
<script type="text/javascript">
    var fn = "<?php echo $fn; ?>";
    function load_form_content(id)
    {
        ajax_manipulate.reset_object();
        var masked_id = new Array(['tgl_kerjasama']);
        var masked_rules = new Array(['99-99-9999']);
        ajax_manipulate.set_plugin_datatable(false).set_plugin_datepicker(true).set_plugin_maskedinput(true).set_masked_id(masked_id).set_masked_rules(masked_rules)
        .set_url('contents/'+fn+'/form_content.php').set_id_input(id).set_input_ajax('ajax-req-dt').set_data_ajax().set_loading('#form-loading').set_content('#form-content').request_ajax();
    }

    function exec_delajax(id)
    {
        ajax_manipulate.reset_object();
        ajax_manipulate.set_url('contents/'+fn+'/manipulating.php').set_plugin_datatable(true).set_id_input(id).set_input_ajax('ajax-req-dt').set_data_ajax().set_loading('#preloadAnimation').set_content('#list-of-data').enable_pnotify().update_ajax('menghapus');
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
    Data Pendukung
    <small>Perusahaan Penunjuk</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Data Pendukung</a></li>
    <li class="active">Perusahaan Penunjuk</li>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="defModalHead">Form Perusahaan Penunjuk</h4>
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