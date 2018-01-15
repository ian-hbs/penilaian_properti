<?php       
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/user_controller.php";
    include_once "../../libraries/cipher.php";
    include_once "../../helpers/date_helper.php";
    include_once "list_sql.php";

    //instantiate objects
    $uc = new user_controller($db);
    $cipher = new cipher(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);    

    $fn = $_POST['fn'];    
    $menu_id = $_POST['menu_id'];
    $dasar = $_POST['dasar'];
    $tgl1 = us_date_format($_POST['tgl1']);
    $tgl2 = us_date_format($_POST['tgl2']);

    $list_sql .= " WHERE (a.".$dasar." BETWEEN '".$tgl1."' AND '".$tgl2."')";
    $list_of_data = $db->Execute($list_sql);
    
    if (!$list_of_data)
        print $db->ErrorMsg();
    
    $enc_key = "+^?:^&%*S!3!c!12!31T";
    $dasar_enc = $cipher->encrypt($dasar,$enc_key);
    $tgl1_enc = $cipher->encrypt($tgl1,$enc_key);
    $tgl2_enc = $cipher->encrypt($tgl2,$enc_key);
    echo "
    <div class='box'>
      <div class='box-header'>

          <div class='row'>
            <div class='col-md-9'>
                <h3 class='box-title'>Daftar Hasil Penilaian</h3>
            </div>
            <div class='col-md-3' align='right'>
                <a title='Pratinjau' class='btn btn-xs btn-default' id='report' role='button' target='_blank' href='contents/".$fn."/print_preview.php?dasar=".urlencode($dasar_enc)."&tgl1=".urlencode($tgl1_enc)."&tgl2=".urlencode($tgl2_enc)."'>
                <i class='fa fa-list-alt'></i> Pratinjau Cetak
              </a>
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