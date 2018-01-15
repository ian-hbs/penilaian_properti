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
    $menu_id1 = $_POST['menu_id1'];
    $menu_id2 = $_POST['menu_id2'];
    $bct = $_POST['bct'];
    $n_data = $_POST['n_data'];
    
    $addAccess = $uc->check_priviledge('add',$menu_id1);

    $form_id = 'building-construction-cost-form';

    echo "
    <div id='form-content'>";
        include_once "form_content.php";
    echo "</div>";
?>