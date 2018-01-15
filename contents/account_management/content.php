<?php
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";    
    include_once "../../libraries/user_controller.php"; 
    include_once "../../libraries/DML.php";     
    
    //instantiate objects
    $uc = new user_controller($db);
    
    $fn = $_POST['fn'];
    $menu_id = $_POST['menu_id'];        
    
    $editAccess = $uc->check_priviledge('update',$menu_id);

    $sql = "SELECT * FROM users WHERE user_id='".$_SESSION['user_id']."'";

    $result = $db->Execute($sql);
    if (!$result)
        print $db->ErrorMsg();
    
    $curr_data = $result->FetchRow();

    $id_form = 'account-management-form';
?>

<script type="text/javascript">
    var id_form = '<?php echo $id_form;?>';
    var $form = $('#'+id_form);
    
    var stat=$form.validate({
                rules: {                
                    username: {
                        required: true
                    },                
                    password: {
                        required: true                                          
                    },
                    confirm_password: {
                        required: true,
                        equalTo: "#password"
                    },                                      
                },
                messages: {                
                    username: {
                        required: "This field is required."
                    },
                    password: {
                        required: "This field is required"
                    },
                    confirm_password: {
                        required: "This field is required",
                        equalTo: "Please enter the same password as above"
                    },
                }
            });

    var act_lbl='memperbaharui';

    $form.submit(function(){
        if(stat.checkForm())
        {
            $pa1=$('#input_current_password'),$pa2=$('#current_password');

            if(calcMD5($pa1.val())!=$pa2.val())
            {
                alert('Password akun salah!');
                return false;
            }

            ajax_manipulate.set_plugin_datatable(true)
                           .set_content('')
                           .set_loading('#preloadAnimation')
                           .set_form($form)
                           .submit_ajax('merubah');

            reset_form();

            return false;
        }
    });
    
    function control_input(check_id,input_id)
    {
        var $check = document.getElementById(check_id);
        
        for(i=0;i<input_id.length;i++)
        {
            $('#'+input_id[i]).attr('disabled',!$check.checked);
            $('#'+input_id[i]).attr('required',$check.checked);
        }
    }
    
    function reset_form()
    {        
        document.getElementById('account-management-form').reset();
        
        arr_input=new Array('username');
        control_input('check_username',arr_input)
        
        arr_input=new Array('password','confirm_password');
        control_input('check_password',arr_input);
    }
</script>

<?php
    echo "
    <div class='box'>        
        <div class='box-header'>
            <div class='row'>
              <div class='col-md-6'></div>
              <div class='col-md-6' align='right'></div>
            </div>
        </div>
        <div class='box-body'>
            <form id='".$id_form."' class='form-horizontal form-label-left' method='POST' action='contents/".$fn."/manipulating.php'>
                <input type='hidden' name='fn' value='".$fn."'/>
                <input type='hidden' name='menu_id' value='".$menu_id."'/>
                <input type='hidden' name='id' value='".$_SESSION['user_id']."'/>
                <div class='form-group'>
                    <div class='col-md-4 col-sm-4 col-xs-12' align='right'>                             
                        <input type='checkbox' id='check_username' name='check_username' onclick=\"
                        var arr_input=new Array('username');
                        control_input(this.id,arr_input)\"/> Username Baru
                    </div>
                    <div class='col-md-3 col-sm-3 col-xs-12'>
                        <input type='text' name='username' id='username' class='form-control' disabled/>
                    </div>
                </div>
                <div class='form-group'>
                    <div class='col-md-4 col-sm-4 col-xs-12' align='right'>                             
                        <input type='checkbox' id='check_password' name='check_password' onclick=\"
                        var arr_input=new Array('password','confirm_password');
                        control_input(this.id,arr_input);\"/> Password Baru
                    </div>
                    <div class='col-md-3 col-sm-3 col-xs-12'>
                        <input type='password' name='password' id='password' class='form-control' disabled/>
                    </div>
                </div>
                <div class='form-group'>
                    <div class='col-md-4 col-sm-4 col-xs-12' align='right'>                             
                        Konfirmasi Password Baru
                    </div>
                    <div class='col-md-3 col-sm-3 col-xs-12'>
                        <input type='password' id='confirm_password' name='confirm_password' class='form-control' disabled/>
                    </div>
                </div>
                <div class='form-group'>
                    <div class='col-md-4 col-sm-4 col-xs-12' align='right'>                             
                        Password Saat Ini
                    </div>
                    <div class='col-md-3 col-sm-3 col-xs-12'>
                        <input type='password' name='input_current_password' id='input_current_password' class='form-control' required/>
                        <input type='hidden' name='current_password' id='current_password' value='".$curr_data['password']."'/>
                    </div>
                </div>
                <div class='ln_solid'></div>
                <div class='form-group'>
                    <div class='col-md-12' align='right'>
                        <button type='submit' class='btn btn-success'>Simpan Perubahan</button>                                                
                    </div>
                </div>
            </form>
        </div>
    </div>";
?>