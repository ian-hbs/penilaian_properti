<?php
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";    
    include_once "../../libraries/user_controller.php"; 
    include_once "../../helpers/date_helper.php";
    include_once "../../libraries/DML.php";

    //instantiate objects
    $uc = new user_controller($db);
    
    $fn = $_POST['fn'];
    $menu_id = $_POST['menu_id'];
    $addAccess = $uc->check_priviledge('add',$menu_id);    

    $id_form = 'land-valuation-form';

    $DML1 = new DML('penugasan',$db);
    
echo "
<div class='box' id='form-content'>";
        include_once "form_content.php";
echo "</div>";