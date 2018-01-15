<?php
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/DML.php";
    include_once "../../libraries/user_controller.php"; 
    include_once "../../libraries/global_obj.php";
    include_once "../../helpers/mix_helper.php";
    include_once "../../helpers/date_helper.php";
    
    //instantiate objects
    $uc = new user_controller($db);
    $global = new global_obj($db);
    
    $uc->check_access();
    
    $id_penugasan = $_POST['id_penugasan']; 
    $kunci_pencarian = $_POST['kunci_pencarian']; 
    $menu_id = $_POST['menu_id'];
    $fn = $_POST['fn'];

    $addAccess = $uc->check_priviledge('add',$menu_id);
    $editAccess = $uc->check_priviledge('update',$menu_id);
    $deleteAccess = $uc->check_priviledge('delete',$menu_id);
    
    $list_sql = "SELECT a.id_objek_pembanding,a.no_urut,a.fk_penugasan,b.id_foto_properti_pembanding,b.file_foto,b.keterangan FROM objek_pembanding as a 
                 LEFT JOIN foto_properti_pembanding as b ON (a.id_objek_pembanding=b.fk_objek_pembanding) 
                 WHERE a.fk_penugasan='".$id_penugasan."'";
    $list_of_data = $db->Execute($list_sql);

    if (!$list_of_data)
        print $db->ErrorMsg();

    $pd = $global->get_property_detail($id_penugasan);
    $pd['tgl_survei'] = indo_date_format($pd['tgl_survei'],'longDate');
    
    $global->print_property_detail($pd);

	echo "

    <div id='list-of-data2'>";
        include_once "list_of_data2.php";
        echo $list_of_data2;
    echo "
    </div>

    <div class='box'>
        <div class='box-header'>
            <div class='row'>
                <div class='col-md-6'>
                    <h3 class='box-title'>Form Data Foto Properti Pembanding</h3>
                </div>
                <div class='col-md-6' align='right'>
                </div>
            </div>
        </div>
        <div class='box-body'>
            <div id='form-loading' align='center' style='display:none'><img src='assets/images/ajax-loaders/ajax-loader-1.gif'/></div>
            <div id='form-content'></div>
        </div>
    </div>";
?>