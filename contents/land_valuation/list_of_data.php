<?php
    echo "
    <div style='margin-bottom:10px'>
      Status : LV (Land Valuation), SM (Safety Margin)
    </div>
    <table id='data-table-jq' class='table table-bordered table-striped'>
      <thead>
        <tr>
          <th width='4%'>No.</th>          
          <th>No. Penilaian</th>
          <th>No. Penugasan/<br />Per. Penunjuk</th>
          <th>Calon Debitur</th>                        
          <th>Alamat Properti</th>
          <th width='12%'>Status</th>
          <th width='12%'>Aksi</th>
        </tr>
      </thead>
      <tbody>";
        $no=0;
        while($row = $list_of_data->FetchRow())
        {
            $no++;
            foreach($row as $key => $val){
                $key=strtolower($key);
                $$key=$val;
            }

            $ket_status = "LV = ".($num_spesifikasi>0?"<font color='green'>available</font>":"<font color='orange'>empty</font>");
            
            if($jenis_perusahaan_penunjuk=='2')            
            {              
              $ket_status .= "<br />SM = ".($num_safetymargin>0?"<font color='green'>available</font>":"<font color='orange'>empty</font>");
            }

            echo "
            <tr>
              <td align='center'>".$no."</td>              
              <td>".$no_penilaian."</td>
              <td>".$no_penugasan."<br />
                  <b><small>".$perusahaan_penunjuk."</small></b>
              </td>
              <td>".$nama."</td>
              <td>".$alamat.", Kel. ".$kelurahan.", Kec. ".$kecamatan."</td>
              <td>".$ket_status."</td>
              <td align='center'>";

                  $js_arr = "[";
                  $sql = "SELECT no_urut FROM objek_pembanding WHERE fk_penugasan='".$id_penugasan."'";
                  $result = $db->Execute($sql);
                  
                  if(!$result)
                    echo ($db->ErrorMsg());

                  $s = false;
                  while($row2 = $result->FetchRow())
                  {
                    $js_arr .= ($s?",'".$row2['no_urut']."'":"'".$row2['no_urut']."'");
                    $s = true;
                  }
                  $js_arr .= "]";


                  if((($num_spesifikasi==0 and $addAccess==true) or ($num_spesifikasi>0 and $editAccess==true)) and ($status=='0'))
                    echo "<button type='button' title='Land Valuation' class='btn btn-xs btn-default' id='land_valuation_".$no."' data-toggle='modal' data-target='#formModal' onclick=\"load_valuation_form_content(this.id,".$js_arr.");\">";
                  else
                  {
                    $msg = '';
                    if($num_spesifikasi==0 and $addAccess==false)
                      $msg = 'Anda tidak memiliki akses untuk menambah data';
                    else if($num_spesifikasi>0 and $editAccess==false)
                      $msg = 'Anda tidak memiliki akses untuk mengedit data';
                    else
                      $msg = 'Penginputan telah selesai, data tidak bisa dimodifikasi lagi';
                    echo "<button type='button' title='Land Valuation' class='btn btn-xs btn-default' onclick=\"alert('".$msg."');\">";
                  }

                  echo "
                  <input type='hidden' id='ajax-req-dt' name='id_penugasan' value='".$id_penugasan."'/>
                  <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
                  <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
                  <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                  <i class='fa fa-edit'></i> LV
                  </button>";

                  if($jenis_perusahaan_penunjuk=='2')
                  {
                    echo "<br />";

                    if((($num_safetymargin==0 and $addAccess==true) or ($num_safetymargin>0 and $editAccess==true)) and ($status=='0'))
                      echo "<button type='button' title='Safety Margin' class='btn btn-xs btn-default' id='safety_margin_".$no."' data-toggle='modal' data-target='#formModal' onclick=\"load_safetymargin_form_content(this.id);\">";
                    else
                    {
                      $msg = '';
                      if($num_safetymargin==0 and $addAccess==false)
                        $msg = 'Anda tidak memiliki akses untuk menambah data';
                      else if($num_safetymargin>0 and $editAccess==false)
                        $msg = 'Anda tidak memiliki akses untuk mengedit data';
                      else
                        $msg = 'Penginputan telah selesai, data tidak bisa dimodifikasi lagi';
                      echo "<button type='button' title='Safety Margin' class='btn btn-xs btn-default' onclick=\"alert('".$msg."');\">";
                    }

                    echo "
                    <input type='hidden' id='ajax-req-dt' name='id_penugasan' value='".$id_penugasan."'/>
                    <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
                    <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
                    <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                    <i class='fa fa-edit'></i> SM
                    </button>";
                  }
              echo "</td>
            </tr>";
        }
      echo "</tbody>
    </table>";
?>
