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
          <th width='8%'>Aksi</th>
        </tr>
      </thead>
      <tbody>";
        $no=0;
        $enc_key = "+^?:^&%*S!3!c!12!31T";
        while($row = $list_of_data->FetchRow())
        {
            $no++;
            foreach($row as $key => $val){
                $key=strtolower($key);
                $$key=$val;
            }
            $id_penugasan_enc = $cipher->encrypt($id_penugasan,$enc_key);
            echo "
            <tr>
              <td align='center'>".$no."</td>              
              <td>".$no_penilaian."</td>
              <td>".$no_penugasan."</td>
              <td>".$tgl_penugasan."</td>
              <td>".$nama."</td>
              <td>".$alamat.", Kel. ".$kelurahan.", Kec. ".$kecamatan."</td>              
              <td align='center'>
                  <a title='Pratinjau' class='btn btn-xs btn-default' id='report_".$no."' role='button' target='_blank' href='contents/".$fn."/print_preview.php?id=".urlencode($id_penugasan_enc)."'>
                  <i class='fa fa-list-alt'></i>
                  </a>
              </td>
            </tr>
            ";
        }
      echo "</tbody>
    </table>";
?>
