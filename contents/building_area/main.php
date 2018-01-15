<?php
    $ct=$_GET['ct'];
    $menu_id = $uc->get_menu_id('url','index.php?ct='.$ct);
    $fn = $_CONTENT_FOLDER_NAME[10];

?>
<script type="text/javascript">
    var fn = "<?php echo $fn; ?>";
    
    function load_manajemen_content(id)
    {
        ajax_manipulate.reset_object();
        ajax_manipulate.set_url('contents/'+fn+'/manajemen_content.php').set_plugin_datatable(false).set_id_input(id).set_input_ajax('ajax-req-dt').set_data_ajax().set_loading('#manajemen-loading').set_content('#manajemen-content').request_ajax();
    }

    function load_form_content(id)
    {
        ajax_manipulate.reset_object();
        ajax_manipulate.set_url('contents/'+fn+'/form_content.php').set_plugin_datatable(false).set_id_input(id).set_input_ajax('ajax-req-dt').set_data_ajax().set_loading('#form-loading').set_content('#form-content').request_ajax();
    }
    
    function close_form()
    {
        $('#form-content').html('');
    }
    
    function exec_delajax(id)
    {
        ajax_manipulate.reset_object();
        var content = new Array('#list-of-data2','#list-of-data1');
        var plugin_datatable = new Array(false,true);

        ajax_manipulate.set_plugin_datatable(plugin_datatable).set_url('contents/'+fn+'/manipulating.php').set_id_input(id).set_input_ajax('ajax-req-dt').set_data_ajax().set_loading('#preloadAnimation').set_content(content).enable_pnotify().update_ajax('menghapus',1);
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
    <small>Luas Bangunan</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Penilaian Properti</a></li>
    <li class="active">Luas Bangunan</li>
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
                <h4 class="modal-title" id="defModalHead">Manajemen Luas Bangunan</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="manajemen-loading" class="" align="center"><img src="assets/images/ajax-loaders/ajax-loader-1.gif"/></div>
                    <div class="col-md-12 col-sm-12 col-xs-12" id="manajemen-content">
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>
<!-- END OF MODAL -->