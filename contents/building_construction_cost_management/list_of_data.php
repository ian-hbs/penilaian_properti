<?php
    echo "
    <table id='data-table-jq' class='table table-bordered table-striped'>
      <thead>
        <tr>
          <th width='4%'>No.</th>          
          <th>No. BCT</th>
          <th>Jenis Objek</th>
          <th>Klasifikasi</th>
          <th>Alamat</th>
          <th>Luas Bng.</th>
          <th>Jum. Lantai</th>
          <th>Biaya Bangunan</th>
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
              <td>".$no_bct."</td>
              <td>".$jenis_objek."</td>
              <td>".$klasifikasi_bangunan."</td>
              <td>
                ".$alamat."<br />
                <small><b>Kel. ".$kelurahan."<br />Kec. ".$kecamatan."</b></small>
              </td>
              <td align='right'>
              ".number_format($luas_bangunan)."
              </td>
              <td align='right'>
                ".$jumlah_lantai."
              </td>
              <td align='right'>".number_format($rounded)."</td>
              <td align='center'>";

                  if($editAccess)
                    echo "<a title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' href='index.php?ct=43&bct=".$no_bct."'>";
                  else
                    echo "<a title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' onclick=\"alert('Anda tidak memiliki akses untuk mengedit data!');\">";

                  echo "<i class='fa fa-edit'></i>
                  </a>";

                  if($deleteAccess)
                      echo "<button type='button' title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"if(confirm('Anda yakin?')){exec_delajax(this.id)}\">";
                  else
                      echo "<button type='button' title='Hapus' class='btn btn-xs btn-default' onclick=\"alert('Anda tidak memiliki akses untuk menghapus data!');\">";
                      
                  echo "
                  <input type='hidden' id='ajax-req-dt' name='id' value='".$id_perhitunganbkb_master."'/>
                  <input type='hidden' id='ajax-req-dt' name='act' value='delete'/>
                  <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
                  <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
                  <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                  <i class='fa fa-trash-o'></i></button>   
              </td>
            </tr>
            ";
        }
      echo "</tbody>
    </table>";
?>
