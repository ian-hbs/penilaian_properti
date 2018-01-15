<?php       
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/user_controller.php";
    include_once "../../libraries/cipher.php";    

    //instantiate objects
    $uc = new user_controller($db);
    $cipher = new cipher(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);    

    $fn = $_POST['fn'];    
    $menu_id = $_POST['menu_id'];
    $kunci_pencarian = $_POST['kunci_pencarian'];    

    $sql = "SELECT a.id_penugasan FROM penugasan as a, debitur as b WHERE (a.id_penugasan=b.fk_penugasan) 
             AND (a.no_penilaian LIKE '%".$kunci_pencarian."%' OR a.id_penugasan  LIKE '%".$kunci_pencarian."%' OR b.nama LIKE '%".$kunci_pencarian."%')";
    $result = $db->Execute($sql);
    if (!$result)
        echo $db->ErrorMsg();
    $n_row = $result->RecordCount();
    if($n_row>0)
        $row = $result->FetchRow();

    $fn = $_CONTENT_FOLDER_NAME;

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
        <div class='box-body'>";
            if($n_row>0)
            {
                $enc_key = "+^?:^&%*S!3!c!12!31T";
                $id_penugasan_enc = $cipher->encrypt($row['id_penugasan'],$enc_key);
                echo "
                <table class='table table-bordered table-striped'>
                    <thead>
                        <tr><th width='4%'>No.</th><th>Jenis Laporan</th><th width='8%'>Aksi</th></tr>
                    </thead>
                    <tbody>";
                    $report_types = array(array('Sampul',28),array('Pengantar (Hal.1)',29),array('Syarat & Ketentuan (Hal.2)',30),array('Halaman 3',31),array('Halaman 4',32),
                                          array('Foto Properti',33),array('Data Pembanding D.0',34),array('Data Pembanding D.1',35),array('Peta Lokasi',36),
                                          array('Ringkasan 1',37),array('Ringkasan 2',38));
                    $no=0;
                    foreach($report_types as $report_type)
                    {
                        $no++;
                        echo "
                        <tr>
                            <td align='center'>".$no.".</td><td>".$report_type[0]."</td>
                            <td align='center'>
                                <a title='Pratinjau' class='btn btn-xs btn-default' id='report_".$no."' role='button' target='_blank' href='contents/".$fn[$report_type[1]]."/print_preview.php?id=".urlencode($id_penugasan_enc)."'>
                                <i class='fa fa-list-alt'></i>
                                </a>
                            </td>
                        </tr>";
                    }                    
                    echo "</tbody>
                </table>";
            }
            else
            {
                echo "<center>Data tidak ditemukan!</center>";
            }
      echo "</div>
    </div>";
?>