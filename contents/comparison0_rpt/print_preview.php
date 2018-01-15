<?php
  session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../config/app_param.php";
    include_once "../../libraries/cipher.php";
    include_once "../../libraries/global_obj.php";
    include_once "../../helpers/date_helper.php";
    include_once "../../helpers/mix_helper.php";

    //instance object
    $cipher = new cipher(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $global = new global_obj($db);

    $dec_key = "+^?:^&%*S!3!c!12!31T";
    $id_penugasan = urldecode($_GET['id']);
    $id_penugasan_dec = $cipher->decrypt($id_penugasan,$dec_key);    

    $sql = "SELECT COUNT(1) n_penugasan FROM penugasan WHERE id_penugasan='".$id_penugasan_dec."'";
    $n_penugasan = $db->GetOne($sql);    

    $_BASE_PARAMS = $_APP_PARAM['base'];
    $_SYSTEM_PARAMS = $_APP_PARAM['system_params'];
?>
<!DOCTYPE html>
<html>
    <head>
      <meta charset="UTF-8">
      <title><?php echo $_BASE_PARAMS['sys_name_full'];?> - Data Pembanding D.0 Laporan Penilaian</title>
      <link rel="stylesheet" type="text/css" href="../../assets/dist/css/report-style.css"/>      
      <style type="text/css">
        .border{border:1px solid #000;}
        table{width:100%;}
        .img-container{width:9cm;height:8.5cm;float:left;margin-right:9px;margin-bottom:0;position:relative;}
        .map-container{}
        .img-footer{
            position:absolute;bottom:0;width:100%;height:1.2cm;text-align:center;font-weight:bold;
          }
        .description{height:0.8cm;overflow:hidden;}
        .label{border:1px solid #000;}

      </style>
    </head>
    <body>    
      <?php
      if($n_penugasan>0)
      {        
        $checked = array(3,12,13);
        $need_entry = $global->get_need_entry($id_penugasan_dec,$checked);        

        if($need_entry[0])
        {
          $sql = "SELECT file_foto,keterangan FROM peta_lokasi WHERE (fk_penugasan='".$id_penugasan_dec."') and (jenis='peta1')";

          $result = $db->Execute($sql);
          if(!$result)
            die($db->ErrorMsg());
          
          $row = $result->FetchRow();
          
          echo "
          <div class='header'>
            <img src='../../uploads/logo/01.jpg' width='36px'/>
          </div>
          <div style='margin-top:1cm;padding:2px'>     
          
              <div style='border:1px solid #000;text-align:center'>
                <h4>DATA PEMBANDING</h4>              
              </div>";

              $sql = "SELECT a.no_urut,b.file_foto,b.keterangan FROM objek_pembanding as a LEFT JOIN foto_properti_pembanding as b ON (a.id_objek_pembanding=b.fk_objek_pembanding) 
                      WHERE a.fk_penugasan='".$id_penugasan_dec."'";
              $result = $db->Execute($sql);
              if(!$result)
                echo $db->ErrorMsg();
              
              $photos = array();
              $i=0;
              $j=0;
              while($row2 = $result->FetchRow())
              {
                $photos[$i][$j] = array('no_urut'=>$row2['no_urut'],'file_foto'=>$row2['file_foto'],'keterangan'=>$row2['keterangan']);
                if(($j+1)%2==0)
                {
                  $j=0;
                  $i++;
                }
                else
                  $j++;
              }            

              echo "
              <table style='width:18.6cm;' cellpadding=0 cellspacing=0>";
              foreach($photos as $key1=>$val1)
              {              

                echo "<tr>";
                foreach($val1 as $key2=>$val2)
                {
                  $src = (!is_null($val2['file_foto'])?"../../uploads/comparative_property_photos/".$val2['file_foto']:"../../assets/images/no-thumb.png");
                  echo "<td width='50%' style='height:7.2cm;position:relative;' valign='top'>
                    <img src='".$src."' width='100%' height='83%'/>
                    <div class='img-footer'>
                      <div class='description'>".$val2['keterangan']."</div>
                      <div class='label'>Data ".$val2['no_urut']."</div>
                    </div>
                  </td>
                  </td>";
                }
                echo "</tr>";              
              }
              echo "
              </table>
              
              <div style='border:1px solid #000;text-align:center;margin-top:5px'>
                <h4>PETA LOKASI</h4>              
              </div>
              <div class='map-container'>";
                $src = ($n_row>0?"../../uploads/location_maps/".$row['file_foto']:"../../assets/images/no-thumb.png");
                echo "<img src='".$src."' width='100%' height='100%'/>
              </div>
          
          </div>";
        }
        else
        {
          $err_msg = "Data pada form ";
          $s = false;
          foreach($need_entry[1] as $val)
          {
            $err_msg .= ($s?", ":"")."(".$val.")";
            $s = true;
          }
          $err_msg .= ' belum diinput.';
          echo "<br />
          <center>".$err_msg."<br />
          Silahkan lengkapi terlebih dahulu!";
        }
      }
      else
      {
        echo "<br />
              <center>
                Data tidak ditemukan!
              </center>";
      }
      ?>
    </body>
</html>
