<?php
    echo "
    <table id='data-table-jq' class='table table-bordered table-striped'>
      <thead>
        <tr>
          <th width='4%'>No.</th>          
          <th>No. Penilaian</th>
          <th>No. Penugasan/<br />Perusahaan Penunjuk</th>
          <th>Calon Debitur</th>                        
          <th>Alamat Properti</th>
          <th>Aktif/<br />Jumlah</th>
          <th>Status</th>
          <th width='8%'>Aksi</th>
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

            $_conclusion = $global->get_active_conclusion($id_penugasan);
            
            $active = $_conclusion[0];
            $n_data = $_conclusion[1];
            $status = $_conclusion[2];

            echo "
            <tr>
              <td align='center'>".$no."</td>              
              <td>".$no_penilaian."</td>
              <td>".$no_penugasan."<br />
              <small><b>".$perusahaan_penunjuk."</b></small>
              </td>
              <td>".$nama."</td>
              <td>".$alamat.", Kel. ".$kelurahan.", Kec. ".$kecamatan."</td>
              <td align='right'>".$active."/".$n_data."</td>
              <td>".($status?"<font color='green'>sudah diinput</font>":"<font color='orange'>belum diinput</font>")."</td>
              <td align='center'>";

                if(($num_spesifikasi==0 and $addAccess==true) or ($num_spesifikasi>0 and $editAccess==true))
                  echo "<button type='button' title='Edit' class='btn btn-xs btn-default' id='manajemen_".$no."' data-toggle='modal' data-target='#formModal' onclick=\"load_form_content(this.id);\">";
                else
                {
                    $msg = '';
                    if($num_spesifikasi==0 and $addAccess==false)
                      $msg = 'Anda tidak memiliki akses untuk menambah data';
                    else
                      $msg = 'Anda tidak memiliki akses untuk mengedit data';
                    
                    echo "<button type='button' title='Edit' class='btn btn-xs btn-default' onclick=\"alert('".$msg."');\">";
                }
                echo "
                  <input type='hidden' id='ajax-req-dt' name='id_penugasan' value='".$id_penugasan."'/>
                  <input type='hidden' id='ajax-req-dt' name='jenis_perusahaan_penunjuk' value='".$jenis_perusahaan_penunjuk."'/>
                  <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
                  <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
                  <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                  <i class='fa fa-edit'></i>
                  </button>
              </td>
            </tr>";
        }
      echo "</tbody>
    </table>";
?>
