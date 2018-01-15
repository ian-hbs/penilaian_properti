<script type="text/javascript">
    var form_id = 'property-photo-designer-form';
    var $input_designer_form = $('#'+form_id);
    var stat = $input_designer_form.validate();
    var act_lbl = 'menyimpan';

    $input_designer_form.submit(function(){
        if(stat.checkForm())
        {
            ajax_manipulate.reset_object();
            ajax_manipulate.set_plugin_datatable(false)
                           .set_content('#property-photo-designer')
                           .set_loading('#preloadAnimation')
                           .enable_pnotify()
                           .set_close_modal('')
                           .set_form($input_designer_form)
                           .submit_ajax(act_lbl);            
            return false;
        }
    });
</script>

<?php
	$sql = "SELECT perancang_foto_properti,skala_foto_properti FROM properti WHERE fk_penugasan='".$id_penugasan."'";
    $result = $db->Execute($sql);
    if(!$result)
        echo $db->ErrorMsg();
    $row = $result->FetchRow();
	echo "
	<form id='property-photo-designer-form' class='form-horizontal form-label-left' method='POST' action='contents/".$fn."/save_designer.php'>
		<input type='hidden' name='fk_penugasan' value='".$id_penugasan."'/>	    
	    <input type='hidden' name='fn' value='".$fn."'/>
        <div class='row'>
            <div class='col-md-12'>
                <div class='form-group'>
                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for='file_foto'>Digambar oleh</label>
                    <div class='col-md-7 col-sm-7 col-xs-12'>
                        <input type='text' class='form-control' id='perancang_foto_properti' name='perancang_foto_properti' value=\"".$row['perancang_foto_properti']."\" required/>
                    </div>                    
                </div>
                <div class='form-group'>
                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for='file_foto'>Skala</label>
                    <div class='col-md-7 col-sm-7 col-xs-12'>
                        <input type='text' class='form-control' id='skala_foto_properti' name='skala_foto_properti' value=\"".$row['skala_foto_properti']."\"/>
                    </div>                    
                </div>                
                <div class='form-group'>
                    <div class='col-md-9 col-md-offset-3'>
                        <button type='submit' class='btn btn-success'>Simpan</button>
                    </div>
                </div>
            </div>
        </div>        
    </form>";
?>