<?php
    $ct=$_GET['ct'];
    $menu_id = $uc->get_menu_id('url','index.php?ct='.$ct);
    $fn = $_CONTENT_FOLDER_NAME[15];
?>
<script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/maskedinput/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="<?=$_ASSETS_PATH?>js/land_valuation_functions.js"></script>
<script type="text/javascript">
    var fn = "<?php echo $fn; ?>";    

    function load_valuation_form_content(id,arr_nu)
    {
        $('#defModalHead').html("Land Valuation <small>Market Data Adjustment Methode</small>");        
        $('#modal-wrapper').removeClass('modal-dialog modal-lg');
        $('#modal-wrapper').addClass('modal-dialog modal-lg');
        $('#modal-wrapper').css('width','100%');

        ajax_manipulate.reset_object();
        
        var masked_id = new Array(['time0']);
        var masked_rules = new Array(['99-99-9999']);
        for(i=0;i<arr_nu.length;i++)
        {
          ln = masked_id[0].length;
          masked_id[0][ln] = 'time'+arr_nu[i];
          masked_rules[0][ln] = '99-99-9999';
        }
        ajax_manipulate.set_plugin_datatable(false).set_plugin_maskedinput(true).set_masked_id(masked_id).set_masked_rules(masked_rules)
        .set_url('contents/'+fn+'/valuation_form_content.php').set_id_input(id).set_input_ajax('ajax-req-dt').set_data_ajax().set_loading('#form-loading').set_content('#form-content').request_ajax();
    }

    function load_safetymargin_form_content(id)
    {
        $('#defModalHead').html("Safety Margin Tanah");
        $('#modal-wrapper').removeClass('modal-dialog modal-lg');
        $('#modal-wrapper').addClass('modal-dialog');
        $('#modal-wrapper').attr('style','');

        ajax_manipulate.reset_object();

        ajax_manipulate.set_plugin_datatable(false)
        .set_url('contents/'+fn+'/safetymargin_form_content.php').set_id_input(id).set_input_ajax('ajax-req-dt').set_data_ajax().set_loading('#form-loading').set_content('#form-content').request_ajax();

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
<style type="text/css">
    input[type="text"],input[type="password"],select,textarea{
      padding:5px;
      border:1px solid #cccccc;
      background:#ffffff; 
      color:#626262;
      box-shadow:0 0 2px #d5d5d5 inset;
      -moz-box-shadow:0 0 2px #d5d5d5 inset;
      -webkit-box-shadow:0 0 2px #d5d5d5 inset;
    }

    input[type="text"]:focus,input[type="password"]:focus{background:#ffffff;}
</style>
<section class="content-header">
  <h1>
    Penilaian Properti 
    <small>Perhitungan Nilai Tanah</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Penilaian Properti</a></li>
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

<!-- MODAL -->
<div class="modal fade" id="formModal" role="dialog">
    <div id="modal-wrapper" class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="defModalHead"></h4>
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