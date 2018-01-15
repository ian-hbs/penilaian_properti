<?php
    $list_of_data1 = "<table id='data-table-jq' class='table table-bordered table-striped'><thead><tr><th width='4%'>No.</th>          
                      <th>No. Penilaian</th><th>No. Penugasan</th><th>Calon Debitur</th><th>Alamat Properti</th><th>Jumlah Foto</th>
                      <th width='10%'>Aksi</th></tr></thead><tbody>";
    $no=0;
    while($row = $list_of_data->FetchRow())
    {
        $no++;
        foreach($row as $key1 => $val1){
            $key1=strtolower($key1);
            $$key1=$val1;
        }
        $list_of_data1 .= "<tr><td align='center'>".$no."</td><td>".$no_penilaian."</td><td>".$no_penugasan."</td><td>".$nama."</td>
                           <td>".$alamat.", Kel. ".$kelurahan.", Kec. ".$kecamatan."</td><td>".$num_spesifikasi." Foto</td>
                           <td align='center'>";

              if($status=='0')
                $list_of_data1 .= "<button type='button' title='Edit' class='btn btn-xs btn-default' id='manajemen_".$no."' data-toggle='modal' data-target='#formModal' onclick=\"load_manajemen_content(this.id);\">";
              else
                $list_of_data1 .= "<button type='button' title='Edit' class='btn btn-xs btn-default' onclick=\"alert('Penginputan telah selesai, data tidak bisa dimodifikasi lagi!');\">";
              
              $list_of_data1 .= "<input type='hidden' id='ajax-req-dt' name='id_penugasan' value='".$id_penugasan."'/>
                                 <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
                                 <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
                                 <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                                 <i class='fa fa-edit'></i></button></td></tr>";
    }
    $list_of_data1 .= "</tbody></table>";
?>
