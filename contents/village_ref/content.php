<?php
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";    
    include_once "../../libraries/user_controller.php"; 
    include_once "../../libraries/DML.php"; 
    include_once "../../config/app_param.php";
  
    include_once "list_sql.php";
    
    //instantiate objects
    $uc = new user_controller($db);
    
    $fn = $_POST['fn'];
    $menu_id = $_POST['menu_id'];
    $readAccess = $uc->check_priviledge('read',$menu_id);
    $addAccess = $uc->check_priviledge('add',$menu_id);
    $editAccess = $uc->check_priviledge('update',$menu_id);
    $deleteAccess = $uc->check_priviledge('delete',$menu_id);

    $list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();

    echo "
    <div class='box' id='list-of-data'>";        
        include_once "list_of_data.php"; 
    echo "</div>";
?>