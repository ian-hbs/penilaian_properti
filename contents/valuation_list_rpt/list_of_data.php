<?php
    echo "
    <table id='data-table-jq' class='table table-bordered table-striped'>
      <thead>
        <tr>
          <th width='4%'>No.</th>
          <th>No. Penugasan</th>
          <th>Tgl. Penugasan</th>
          <th>Tgl. Survei</th>
          <th>No. Laporan</th>
          <th>Tgl. Laporan</th>
          <th>Nama Debitur</th>
          <th>Alamat Properti</th>
          <th>Nilai Pasar</th>
          <th>Nilai Likuidasi</th>
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
              <td>".$no_penugasan."</td>
              <td>".$tgl_penugasan."</td>
              <td>".$tgl_survei."</td>
              <td>".$no_laporan."</td>
              <td>".$tgl_laporan."</td>
              <td>".$nama."</td>
              <td>".$alamat.", Kel. ".$kelurahan.", Kec. ".$kecamatan."</td>              
              <td align='right'>".number_format($pembulatan_pasar_objek)."</td>
              <td align='right'>".number_format($pembulatan_likuidasi_objek)."</td>
            </tr>
            ";
        }
      echo "</tbody>
    </table>";
?>
