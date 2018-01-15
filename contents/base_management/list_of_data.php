<?php
    echo "
    <table id='data-table-jq' class='table table-bordered table-striped'>
      <thead>
        <tr>
          <th width='4%'>No.</th>          
          <th>No. Penilaian</th>
          <th>No. Penugasan</th>
          <th>Tgl. Penugasan</th>
          <th>Calon Debitur</th>                        
          <th>Alamat Properti</th>          
          <th width='10%'>Aksi</th>
        </tr>
      </thead>
      <tbody>";
        $no=0;
        while($row = $list_of_data->FetchRow())
        {
            $no++;
            foreach($row as $key1 => $val1){
                $key1=strtolower($key1);
                $$key1=$val1;
            }
            echo "
            <tr>
              <td align='center'>".$no."</td>              
              <td>".$no_penilaian."</td>
              <td>".$no_penugasan."</td>
              <td>".$tgl_penugasan."</td>
              <td>".$nama."</td>
              <td>".$alamat.", Kel. ".$kelurahan.", Kec. ".$kecamatan."</td>              
              <td align='center'>";

                  if($status=='0' and $editAccess===true)
                    echo "<button type='button' title='Edit' class='btn btn-xs btn-default' id='manajemen_".$no."' data-toggle='modal' data-target='#formModal' onclick=\"load_form_content(this.id);\">";
                  else
                  {
                    $msg = (!$editAccess?'Anda tidak memiliki akses untuk mengedit data':'Penginputan telah selesai, data tidak bisa dimodifikasi lagi');
                    echo "<button type='button' title='Edit' class='btn btn-xs btn-default' onclick=\"alert('".$msg."');\">";
                  }
                  echo "
                  <input type='hidden' id='ajax-req-dt' name='id_penugasan' value='".$id_penugasan."'/>
                  <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
                  <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
                  <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                  <i class='fa fa-edit'></i>
                  </button>
              </td>
            </tr>
            ";
        }
      echo "</tbody>
    </table>";
?>
