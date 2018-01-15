<script type="text/javascript">
    var id_form = '<?php echo $id_form;?>';
    var $form = $('#'+id_form);
    var stat = $form.validate();    
    var fn = "<?php echo $fn; ?>";

    $form.submit(function(){
        if(stat.checkForm())
        {
            ajax_manipulate.set_plugin_datatable(true)
                           .set_content('#form-content')
                           .set_loading('#preloadAnimation')
                           .set_close_modal('#close-modal-form')
                           .set_form($form)
                           .submit_ajax('menambah');
            $('#close-modal-form').click();
            return false;
        }
    });
</script>

<div class="box-header">
    <div class="row">
      <div class="col-md-6">
        <h3 class="box-title">Land Valuation <small>Market Data Adjustment Methode</small></h3>
      </div>      
    </div>
</div>
<div class="box-body">

<?php
if($addAccess)
{
    echo "
    <form id='".$id_form."' class='form-horizontal form-label-left' method='POST' action='contents/".$fn."/manipulating.php'>	
        <input type='hidden' name='act' value='add'/>
        <input type='hidden' name='menu_id' value='".$menu_id."'/>
        <input type='hidden' name='fn' value='".$fn."'/>        
        <!--script>
            function test()
            {
                number = $('#param1').val();
                number_digits = $('#param2').val();
                result = round(number,number_digits);
                $('#result').val(result);
            }
        </script>
        <input type='text' id='param1'/><input type='text' id='param2'/>
        <input type='text' id='result'/><button type='button' onclick=\"test();\">test</button--!>
        <div class='form-group'>
            <label class='control-label col-md-3 col-sm-3 col-xs-12'>No. Penugasan <font color='red'>*</font></label>
            <div class='col-md-4 col-sm-4 col-xs-12'>
                <select name='no_penugasan' id='no_penugasan' class='form-control'  onchange=\"get_data_debtor(this.value,'".$fn."');\" required>
                    <option value=''></option>";
                    $data = $DML1->fetchAllData();
                    foreach($data as $row)
                    {
                        echo "<option value='".$row['id_penugasan']."' ".$selected.">".$row['no_penugasan']."</option>";
                    }
                echo "</select>
            </div>
        </div>        
        <div class='form-group'>
            <label class='control-label col-md-3 col-sm-3 col-xs-12' for='calon_debitur'>Calon Debitur</label>
            <div class='col-md-4 col-sm-4 col-xs-12'>
                <input type='text' id='calon_debitur' name='calon_debitur' class='form-control col-md-7 col-xs-12 autofill-bg' readonly/>
            </div>
        </div>
        <div class='form-group'>
            <label class='control-label col-md-3 col-sm-3 col-xs-12' for='lokasi_properti'>Lokasi Properti</label>
            <div class='col-md-5 col-sm-5 col-xs-12'>
                <textarea id='lokasi_properti' name='lokasi_properti' class='form-control col-md-7 col-xs-12 autofill-bg' readonly></textarea>
            </div>
        </div>
        <div id='adjustment-form'>

        </div>
        <div class='ln_solid'></div>
        <div class='form-group'>
            <div class='col-md-12 col-sm-12 col-xs-12' align='right'>
                <button type='reset' class='btn btn-danger' id='close-modal-form' data-dismiss='modal'>Batal</button>
                <button type='submit' class='btn btn-success'>Simpan</button>
            </div>
        </div>
    </form>";
}
else
{
    echo "
    <div class='row'>
        <div class='col-md-12'>
            <div class='alert alert-warning' role='alert'>
            <button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
            Anda tidak memiliki akses untuk menambah data
            </div>
        </div>
    </div>";            
}
?>
</div>