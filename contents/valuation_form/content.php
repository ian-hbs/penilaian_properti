<?php
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";    
    include_once "../../config/app_param.php";
    include_once "../../libraries/user_controller.php"; 
    include_once "../../libraries/DML.php";         

    //instantiate objects
    $uc = new user_controller($db);        

    $fn = $_POST['fn'];
    $menu_id = $_POST['menu_id'];    
    $addAccess = $uc->check_priviledge('add',$menu_id);    

    $form_id = 'valuation-form';

    echo "
    <div id='form-content'>";
        include_once "form_content.php";
    echo "</div>";
?>