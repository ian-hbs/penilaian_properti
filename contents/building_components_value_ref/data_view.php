<?php     
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/user_controller.php";     
    include_once "list_sql.php";
    
    //instantiate objects
    $uc = new user_controller($db);    

    $fn = $_POST['fn'];    
    $menu_id = $_POST['menu_id'];
    $kunci_pencarian1 = $_POST['kunci_pencarian1'];
    $kunci_pencarian2 = $_POST['kunci_pencarian2'];
    $kunci_pencarian3 = $_POST['kunci_pencarian3'];
        

    $list_sql .= "WHERE (a.fk_jenis_objek='".$kunci_pencarian1."' AND a.fk_klasifikasi_bangunan='".$kunci_pencarian3."' AND b.fk_kelompok_komponen_bangunan='".$kunci_pencarian2."')";
    $list_of_data = $db->Execute($list_sql);
    
    if (!$list_of_data)
        print $db->ErrorMsg();    

    $addAccess = $uc->check_priviledge('add',$menu_id);
    $editAccess = $uc->check_priviledge('update',$menu_id); 
    $deleteAccess = $uc->check_priviledge('delete',$menu_id); 
    echo "
    <div class='box'>
      <div class='box-header'>

          <div class='row'>
            <div class='col-md-11'>
                <h3 class='box-title'>Daftar Nilai Komponen Bangunan</h3>
            </div>
            <div class='col-md-1' align='right'>";              
              if($addAccess)              
                echo "<button type='button' class='btn btn-xs btn-default' onclick=\"load_form_content(this.id);\" id='add-btn' data-toggle='modal' data-target='#formModal'>";
              else
                echo "<button type='button' class='btn btn-xs btn-default' onclick=\"alert('Anda tidak memiliki akses untuk menambah data!');\">";

              echo "
              <input type='hidden' id='ajax-req-dt' name='act' value='add'/>
              <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
              <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
              <input type='hidden' id='ajax-req-dt' name='kunci_pencarian1' value='".$kunci_pencarian1."'/>
              <input type='hidden' id='ajax-req-dt' name='kunci_pencarian2' value='".$kunci_pencarian2."'/>
              <input type='hidden' id='ajax-req-dt' name='kunci_pencarian3' value='".$kunci_pencarian3."'/>
              <i class='fa fa-plus'></i> Tambah</button>          
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