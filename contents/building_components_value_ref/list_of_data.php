<?php
    echo "
    <table id='data-table-jq' class='table table-bordered table-striped'>
      <thead>
        <tr>
          <th width='4%'>No.</th>          
          <th>Jenis Komponen</th>
          <th>1 Lantai</th>
          <th>2 Lantai</th>
          <th>3 Lantai</th>
          <th>4 Lantai</th>
          <th>>4 Lantai</th>
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
            echo "
            <tr>
              <td align='center'>".$no."</td>
              <td>".$jenis_komponen_bangunan."</td>
              <td align='right'>".number_format($nilai_1lantai,2,'.',',')."</td>
              <td align='right'>".number_format($nilai_2lantai,2,'.',',')."</td>
              <td align='right'>".number_format($nilai_3lantai,2,'.',',')."</td>
              <td align='right'>".number_format($nilai_4lantai,2,'.',',')."</td>
              <td align='right'>".number_format($nilai_nlantai,2,'.',',')."</td>
              <td align='center'>";
                  if($editAccess)
                      echo "<button type='button' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' data-toggle='modal' data-target='#formModal' onclick=\"load_form_content(this.id);\">";
                  else
                      echo "<button type='button' title='Edit' class='btn btn-xs btn-default' onclick=\"alert('Anda tidak memiliki akses untuk mengedit data!');\">";
                  echo "
                  <input type='hidden' id='ajax-req-dt' name='id' value='".$id_nilai_komponen_bangunan."'/>
                  <input type='hidden' id='ajax-req-dt' name='act' value='edit'/>
                  <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
                  <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
                  <input type='hidden' id='ajax-req-dt' name='kunci_pencarian1' value='".$kunci_pencarian1."'/>
                  <input type='hidden' id='ajax-req-dt' name='kunci_pencarian2' value='".$kunci_pencarian2."'/>
                  <input type='hidden' id='ajax-req-dt' name='kunci_pencarian3' value='".$kunci_pencarian3."'/>
                  <i class='fa fa-edit'></i>
                  </button>&nbsp;";

                  if($deleteAccess)
                      echo "<button type='button' title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"if(confirm('Anda yakin?')){exec_delajax(this.id)}\">";
                  else
                      echo "<button type='button' title='Hapus' class='btn btn-xs btn-default' onclick=\"alert('Anda tidak memiliki akses untuk menghapus data!');\">";
                      
                  echo "
                  <input type='hidden' id='ajax-req-dt' name='id' value='".$id_nilai_komponen_bangunan."'/>
                  <input type='hidden' id='ajax-req-dt' name='act' value='delete'/>
                  <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
                  <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
                  <input type='hidden' id='ajax-req-dt' name='kunci_pencarian1' value='".$kunci_pencarian1."'/>
                  <input type='hidden' id='ajax-req-dt' name='kunci_pencarian2' value='".$kunci_pencarian2."'/>
                  <input type='hidden' id='ajax-req-dt' name='kunci_pencarian3' value='".$kunci_pencarian3."'/>
                  <i class='fa fa-trash-o'></i></button>                        
              </td>
            </tr>
            ";
        }
      echo "</tbody>
    </table>";
?>
