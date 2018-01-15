<?php       
    include_once "list_sql.php";

    if(isset($_POST['kunci_pencarian']))
    {
      session_start();
      include_once "../../config/superglobal_var.php";
      include_once "../../config/db_connection.php";
      include_once "../../libraries/user_controller.php";  
      
      $uc = new user_controller($db);
      $fn = $_POST['fn'];    
      $menu_id = $_POST['menu_id'];
      $kunci_pencarian = $_POST['kunci_pencarian'];
      $list_sql .= " WHERE (a.no_penilaian LIKE '%".$kunci_pencarian."%' OR a.id_penugasan  LIKE '%".$kunci_pencarian."%' OR b.nama LIKE '%".$kunci_pencarian."%')";
    }
    else
    {      
      $list_sql .= " ORDER BY id_penugasan DESC LIMIT 0,10";
      $kunci_pencarian = '';
    }
    
    $list_of_data = $db->Execute($list_sql);

    if (!$list_of_data)
        print $db->ErrorMsg();
    
    $addAccess = $uc->check_priviledge('add',$menu_id);
    $editAccess = $uc->check_priviledge('update',$menu_id);   
    echo "
    <div class='box'>
      <div class='box-header'>

          <div class='row'>
            <div class='col-md-11'>
                <h3 class='box-title'>Daftar Data Dasar</h3>
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