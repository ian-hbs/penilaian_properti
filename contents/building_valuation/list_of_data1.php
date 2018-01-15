<?php
    echo "
    <div style='margin-bottom:10px'>
      Status : B (Buildings), SI (Site Improvements), P (Pembanding), SM (Safety Margin)
    </div>
    
    <table id='data-table-jq' class='table table-bordered table-striped'>
      <thead>
        <tr>
          <th width='4%'>No.</th>          
          <th>No. Penilaian</th>
          <th>No. Penugasan</th>
          <th>Calon Debitur</th>                        
          <th>Alamat Properti</th>
          <th width='12%'>Status</th>          
          <th width='10%'>Aksi</th>
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

            $ket_status = "B = ".$num_buildings." data,<br />SI = ".$num_site_improvements." data,<br />P = ".$num_pembanding." data";
            
            if($jenis_perusahaan_penunjuk=='2')
            {
              $ket_status .= "<br />SM = ".($num_safetymargin_bangunan>0?"<font color='green'>available</font>":"<font color='orange'>empty</font>");
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
                    
                  if($status=='0')
                    echo "<button type='button' title='Edit' class='btn btn-xs btn-default' id='manajemen1_".$no."' data-toggle='modal' data-target='#formModal' onclick=\"load_manajemen_content(this.id);\">";
                  else
                    echo "<button type='button' title='Edit' class='btn btn-xs btn-default' onclick=\"alert('Penginputan telah selesai, data tidak bisa dimodifikasi lagi!');\">";

                  echo "
                  <input type='hidden' id='ajax-req-dt' name='id_penugasan' value='".$id_penugasan."'/>
                  <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
                  <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
                  <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                  <input type='hidden' id='ajax-req-dt' name='data_type' value='1'/>
                  <input type='hidden' id='ajax-req-dt' name='jenis_perusahaan_penunjuk' value='".$jenis_perusahaan_penunjuk."'/>
                  <i class='fa fa-edit'>&nbsp;Utama</i>
                  </button><br />";

                  if($status=='0')
                    echo "<button type='button' title='Edit' class='btn btn-xs btn-default' id='manajemen2_".$no."' data-toggle='modal' data-target='#formModal' onclick=\"load_manajemen_content(this.id);\">";
                  else
                    echo "<button type='button' title='Edit' class='btn btn-xs btn-default' onclick=\"alert('Penginputan telah selesai, data tidak bisa dimodifikasi lagi!');\">";

                  echo "
                  <input type='hidden' id='ajax-req-dt' name='id_penugasan' value='".$id_penugasan."'/>
                  <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
                  <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
                  <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                  <input type='hidden' id='ajax-req-dt' name='data_type' value='2'/>
                  <input type='hidden' id='ajax-req-dt' name='jenis_perusahaan_penunjuk' value='".$jenis_perusahaan_penunjuk."'/>
                  <i class='fa fa-edit'>&nbsp;Pembanding</i>
                  </button>";

                  if($jenis_perusahaan_penunjuk=='2')
                  {
                    echo "<br />";

                    if((($num_safetymargin_bangunan==0 and $addAccess==true) or ($num_safetymargin_bangunan>0 and $editAccess==true)) and ($status=='0'))
                      echo "<button type='button' title='Safety Margin' class='btn btn-xs btn-default' id='building_safety_margin_".$no."' data-toggle='modal' data-target='#formModal' onclick=\"load_safetymargin_form_content(this.id,'1');\">";
                    else
                    {
                      $msg = '';
                      if($num_safetymargin_bangunan==0 and $addAccess==false)
                        $msg = 'Anda tidak memiliki akses untuk menambah data';
                      else if($num_safetymargin_bangunan>0 and $editAccess==false)
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
                    <input type='hidden' id='ajax-req-dt' name='jenis_objek' value='bangunan'/>
                    <i class='fa fa-edit'></i> SM
                    </button>";                    
                  }

              echo "
              </td>
            </tr>
            ";
        }
      echo "</tbody>
    </table>";
?>
