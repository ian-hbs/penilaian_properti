<?php
    $ct=$_GET['ct'];
    $menu_id1 = $uc->get_menu_id('url','index.php?ct='.$ct);
    $menu_id2 = $uc->get_menu_id('url','index.php?ct=51');

    $fn = $_CONTENT_FOLDER_NAME[43];
    $bct = '';
    $n_data = 1;

    $act = 'add';

    if(isset($_GET['bct']))
    {
        $act = 'edit';

        $bct = $_GET['bct'];

        $sql = "SELECT * FROM perhitunganbkb_master WHERE no_bct='".$bct."'";
        $result = $db->Execute($sql);
        if(!$result)
          echo $db->ErrorMsg();

        $n_data = $result->RecordCount();
        if($n_data>0)
        {
            $row = $result->FetchRow();
            $ot = $row['fk_jenis_objek'];
            $bc = $row['fk_klasifikasi_bangunan'];
            include_once "contents/building_construction_cost_form/building_component_value.php";
        }        
    }

?>

<script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?=$_ASSETS_PATH?>plugins/maskedinput/jquery.maskedinput.min.js"></script>

<script type="text/javascript">
    var fn = "<?php echo $fn; ?>";    

    var building_construction_values = '';
    var imb_values = '';

    <?php
        if($act=='edit' and $n_data>0)
        {
            echo "bcv_imb=JSON.parse('".$bcv_imb."');
                  building_construction_values = bcv_imb['bcv'];
                  imb_values = bcv_imb['imb'];";
        }
    ?>
    function load_content()
    {
        ajax_manipulate.reset_object();
        var data_ajax = <?php echo "new Array('menu_id1=".$menu_id1."','menu_id2=".$menu_id2."','fn='+fn,'bct=".$bct."','n_data=".$n_data."')"; ?>;
        var masked_id = new Array(['tgl_penilaian']);
        var masked_rules = new Array(['99-99-9999']);
        ajax_manipulate.set_plugin_datatable(false).set_plugin_datepicker(true).set_plugin_maskedinput(true).set_masked_id(masked_id).set_masked_rules(masked_rules)
        .set_url('contents/'+fn+'/content.php').set_data_ajax(data_ajax).set_loading('#content-loading').set_content('#main-content').request_ajax();
    }
    
    function get_regencies_list(province_id)
    {
        ajax_manipulate.reset_object();
        var data_ajax = new Array('province_id='+province_id);
        ajax_manipulate.set_plugin_datatable(false).set_url('contents/'+fn+'/regencies_list.php').set_data_ajax(data_ajax).set_loading('#preloadAnimation').set_content('#kota').request_ajax();
        $('#kecamatan').html("<option value=''>Pilih Kota/Kabupaten Terlebih Dahulu</option>");
        $('#kelurahan').html("<option value=''>Pilih Kecamatan Terlebih Dahulu</option>");
    }

    function get_districts_list(regency_id)
    {
        ajax_manipulate.reset_object();
        var data_ajax = new Array('regency_id='+regency_id);
        ajax_manipulate.set_plugin_datatable(false).set_url('contents/'+fn+'/districts_list.php').set_data_ajax(data_ajax).set_loading('#preloadAnimation').set_content('#kecamatan').request_ajax();
        $('#kelurahan').html("<option value=''>Pilih Kecamatan Terlebih Dahulu</option>");
    }

    function get_villages_list(district_id)
    {
        ajax_manipulate.reset_object();
        var data_ajax = new Array('district_id='+district_id);
        ajax_manipulate.set_plugin_datatable(false).set_url('contents/'+fn+'/villages_list.php').set_data_ajax(data_ajax).set_loading('#preloadAnimation').set_content('#kelurahan').request_ajax();        
    }

    function get_building_component_value(object_type,building_classification)
    {
        $.ajax({
            type:'POST',
            url:'contents/'+fn+'/building_component_value.php',
            data:'object_type='+object_type+'&building_classification='+building_classification,
            beforeSend:function(){
                $('#preloadAnimation').show();
            },
            complete:function(){
                $('#preloadAnimation').hide();
            },
            success:function(data){
                result = JSON.parse(data);            
                building_construction_values = result['bcv'];
                imb_values = result['imb'];
            }
        });        
    }
    
    function fill_dummy_data()
    {        
        
    }

    $(window).load(function() {
       	load_content();
    });
</script>

<script type="text/javascript" src="<?=$_ASSETS_PATH?>js/building_construction_cost_functions.js"></script>

<section class="content-header">
  <h1>
    Biaya Konstruksi Bangunan
    <small>Formulir Biaya Konstruksi Bangunan</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Biaya Konstruksi Bangunan</a></li>
    <li class="active">Formulir Biaya Konstruksi Bangunan</li>
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