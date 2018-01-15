<?php
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";    
    include_once "../../libraries/user_controller.php";     
    include_once "../../libraries/DML.php";
    
    //instantiate objects
    $uc = new user_controller($db);    

    $uc->check_access();

    $fn = $_POST['fn'];    
    $menu_id = $_POST['menu_id'];

    $readAccess = $uc->check_priviledge('read',$menu_id);    
?>

<script type="text/javascript">    
    var form_id = 'form-pencarian-data-dasar';
    var $search_form=$('#'+form_id);
    var stat=$search_form.validate();

    $search_form.submit(function(){
        if(stat.checkForm())
        {
            ajax_manipulate.reset_object();
            ajax_manipulate.set_content('#data-view')
                           .set_plugin_datatable(true)
                           .set_loading('#preloadAnimation')
                           .disable_pnotify()
                           .set_form($search_form)
                           .submit_ajax('');
            return false;
        }
    });
        
</script>

<?php    
    if($readAccess)
    {
        echo "
        <form id='form-pencarian-data-dasar' class='form-horizontal' method='POST' action='contents/".$fn."/data_view.php'>
        <input type='hidden' name='fn' value='".$fn."'/>
        <input type='hidden' name='menu_id' value='".$menu_id."'/>
        <div class='box'>
            <div class='box-header'>
                <div class='row'>
                  <div class='col-md-6'>
                    <h3 class='box-title'>Pencarian Data Dasar</h3>
                  </div>              
                </div>
            </div>
            <div class='box-body'>
                <div class='form-group'>
                    <label class='col-md-3 col-xs-12 control-label'>Berdasarkan</label>
                    <div class='col-md-5 col-xs-12'>
                        <select name='dasar' id='dasar' class='form-control' required>
                            <option value='tgl_penugasan' selected>Tgl. Penugasan</option>
                            <option value='tgl_survei'>Tgl. Survei</option>
                            <option value='tgl_laporan'>Tgl. Laporan</option>
                        </select>
                    </div>
                </div>
                <div class='form-group'>
                    <label class='col-md-3 col-xs-12 control-label'>Periode</label>
                    <div class='col-md-2 col-xs-12'>                        
                    <input type='text' name='tgl1' id='tgl1' class='form-control datepicker' required/>
                    </div>
                    <div class='col-md-2 col-xs-12'>
                    <input type='text' name='tgl2' id='tgl2' class='form-control datepicker' required/>
                    </div>
                </div>
            </div>
            <div class='box-footer'>
                <button type='reset' class='btn btn-default'>Kosongkan</button>
                <button type='submit' class='btn btn-primary pull-right'>Tampil</button>
            </div>
        </div>
        </form>
        <div id='data-view'></div>";
    }
    else
    {
        echo "
            <div class='row'>
                <div class='col-md-12'>
                    <div class='alert alert-warning' role='alert'>
                    <button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
                    Anda tidak memiliki akses untuk melihat data
                    </div>
                </div>
            </div>";
    }
?>