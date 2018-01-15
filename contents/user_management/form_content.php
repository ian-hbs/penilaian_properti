<?php
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../config/app_param.php";
    include_once "../../libraries/DML.php";

	$act=$_POST['act'];
    $menu_id=$_POST['menu_id'];
    $fn=$_POST['fn'];
    $act_lbl=($act=='add'?'menambah':'memperbaharui');
	
    $DML1 = new DML('users',$db);
    $DML2 = new DML('user_types',$db);

    $_SYSTEM_PARAMS = $_APP_PARAM['system_params'];

    $id_name = 'user_id';
    $id_value = ($act=='edit'?$_POST['id']:'');
    $arr_field = array('type_fk','fullname','email','phone_number','blocked');
    $curr_data = $DML1->getCurrentData($act,$arr_field,$id_name,$id_value);
    $id_form = 'user-form';
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
                                    required: "This field is required."
                                },
                                confirm_password: {
                                    required: "This field is required.",
                                    equalTo: "Please enter the same password as above"
                                },
                            }
                        });

    var act_lbl = '<?php echo $act_lbl;?>';

    $form.submit(function(){
        if(stat.checkForm())
        {
            ajax_manipulate.set_plugin_datatable(true)
                           .set_content('#list-of-data')
                           .set_loading('#preloadAnimation')
                           .set_close_modal('#close-modal-form')
                           .set_form($form)
                           .submit_ajax(act_lbl);
            $('#close-modal-form').click();
            return false;
        }
    });

    function control_input(object,input_id){
        $('#'+input_id).attr('disabled',(!object.checked));
        $('#'+input_id).attr('required',(object.checked));
        if(input_id=='password')
        {            
            $('#confirm_password').attr('disabled',(!object.checked));
            $('#confirm_password').attr('required',(object.checked));
        }
    }
	</script>

<form id="<?=$id_form?>" class="form-horizontal form-label-left" method="POST" action="contents/<?=$fn?>/manipulating.php">
	<input type="hidden" name="id" value="<?=$id_value?>"/>
    <input type="hidden" name="act" value="<?=$act?>"/>
    <input type="hidden" name="menu_id" value="<?=$menu_id?>"/>
    <input type="hidden" name="fn" value="<?=$fn?>"/>
    <input type="hidden" name="modifiable" value="1"/>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="type_fk">Tipe User</label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <select name="type_fk" id="type_fk" class="form-control" required>
                <option value=""></option>
                <?php
                    $opts = $DML2->fetchData("SELECT * FROM user_types WHERE type_id<>0");
                    foreach($opts as $row)
                    {
                        $selected = ($curr_data['type_fk']==$row['type_id']?'selected':'');
                        echo "<option value='".$row['type_id']."' ".$selected.">".$row['name']."</option>";
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="username">
            <?php
            $label = "";
            $attribute = "";
            if($act=='edit')
            {
                echo "<input type='checkbox' name='check_username' id='check_username' value='1' onclick=\"control_input(this,'username');\"/>";
                $label = "Username";
                $attribute = "disabled";
            }
            else
            {
                $label = "Username <font color='red'>*</font>";
                $attribute = "required";
            }

            echo $label;            
            ?>
        </label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="username" name="username" class="form-control col-md-7 col-xs-12" <?=$attribute?>/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="password">
            <?php
            if($act=='edit')
            {
                echo "<input type='checkbox' name='check_password' id='check_password' value='1' onclick=\"control_input(this,'password');\"/>";
                $label = "Password";
                $attribute = "disabled";
            }
            else
            {
                $label = "Password <font color='red'>*</font>";
                $attribute = "required";
            }

            echo $label;
            ?>
        </label> 
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="password" id="password" name="password" class="form-control col-md-7 col-xs-12" <?=$attribute?>/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="confirm_password">
            <?php
            if($act=='edit')
            {                
                $label = "Konf. Password";
                $attribute = "disabled";
            }
            else
            {
                $label = "Konf. Password <font color='red'>*</font>";
                $attribute = "required";
            }

            echo $label;            
            ?>
        </label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="password" id="confirm_password" name="confirm_password" class="form-control col-md-7 col-xs-12" <?=$attribute?>/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="fullname">
            Nama Lengkap <font color="red">*</font>
        </label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="fullname" name="fullname" class="form-control col-md-7 col-xs-12" value="<?=$curr_data['fullname'];?>" required/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="email">
            Email <font color="red">*</font>
        </label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="email" id="email" name="email" class="form-control col-md-7 col-xs-12" value="<?=$curr_data['email'];?>" required/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="phone_number">
            No. Ponsel
        </label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" id="phone_number" name="phone_number" class="form-control col-md-7 col-xs-12" value="<?=$curr_data['phone_number'];?>"/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="blocked">
            &nbsp;
        </label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <?php
            $checked = ($curr_data['blocked']=='1'?'checked':'');
            echo "<input type='checkbox' name='blocked' id='blocked' value='1' ".$checked."/>&nbsp;Blok Akun";
            ?>
        </div>
    </div>
    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-4">
            <button type="button" class="btn btn-danger" id="close-modal-form" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </div>
</form>