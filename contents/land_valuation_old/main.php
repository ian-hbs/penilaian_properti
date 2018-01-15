<?php
    $ct=$_GET['ct'];
    $menu_id = $uc->get_menu_id('url','index.php?ct='.$ct);
    $fn = $_CONTENT_FOLDER_NAME[12];
?>
<script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/maskedinput/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="<?=$_ASSETS_PATH?>js/land_valuation_functions.js"></script>
<script type="text/javascript">
    var fn = "<?php echo $fn; ?>";
    
    function load_content()
    {      
        var data_ajax = <?php echo "new Array('menu_id=".$menu_id."','fn='+fn)"; ?>;
        var masked_id = new Array(['npwr','tgl_bayar']);
        var masked_rules = new Array(['99.99.999.999-9999','99-99-9999']);
        ajax_manipulate.set_plugin_datatable(false).set_plugin_datepicker(true).set_plugin_maskedinput(true).set_masked_id(masked_id).set_masked_rules(masked_rules)
        .set_url('contents/'+fn+'/content.php').set_data_ajax(data_ajax).set_loading('#content-loading').set_content('#main-content').request_ajax();
    }
    
    $(window).load(function() {
       	load_content();
    });    
</script>

<section class="content-header">
  <h1>
    Penginputan
    <small>Perhitungan Nilai Tanah</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Penginputan</a></li>
    <li class="active">Perhitungan Nilai Tanah</li>
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