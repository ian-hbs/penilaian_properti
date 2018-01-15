<?php       
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/user_controller.php";
    include_once "list_sql.php";
    
    $uc = new user_controller($db);

    $fn = $_POST['fn'];    
    $menu_id = $_POST['menu_id'];
    $kunci_pencarian = $_POST['kunci_pencarian'];    

    $list_sql .= " WHERE (a.no_bct LIKE '%".$kunci_pencarian."%' OR a.alamat  LIKE '%".$kunci_pencarian."%')";

    $list_of_data = $db->Execute($list_sql);

    if (!$list_of_data)
        print $db->ErrorMsg();
    
    $editAccess = $uc->check_priviledge('update',$menu_id);
    $deleteAccess = $uc->check_priviledge('delete',$menu_id);

    echo "
    <div class='box'>
      <div class='box-header'>

          <div class='row'>
            <div class='col-md-11'>
                <h3 class='box-title'>Daftar Data Biaya Konstruksi Bangunan</h3>
            </div>
            <div class='col-md-1' align='right'>
          </div>
          </div>
      </div>
      <div class='box-body'>
        <div id='list-of-data'>";
            include_once "list_of_data.php"; 
        echo "
        </div>        
      </div>
    </div>";
?>