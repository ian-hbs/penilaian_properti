<?php
    echo "
    <table class='table table-bordered table-striped'>
        <thead>
            <tr><td class='tableHead'>Lantai/Thn. Bangun</td><td class='tableHead'>Teras</td><td class='tableHead'>R. Tamu</td><td class='tableHead'>R. Klg.</td><td class='tableHead'>R. Tidur1</td><td class='tableHead'>R. Tidur2</td><td class='tableHead'>R. Tidur3</td>
            <td class='tableHead'>R. Dapur</td><td class='tableHead'>K. Mandi</td><td class='tableHead'>Lain-lain</td><td class='tableHead'>T. Luas</td>
            <td width='8%' class='tableHead'>Aksi</td></tr>
        </thead>
        <tbody>";
        $no = 0;
        $total_luas = 0;
        while($row = $list_of_data->FetchRow())
        {
            $no++;
            foreach($row as $key1 => $val1){
                $key1=strtolower($key1);
                $$key1=$val1;
            }
            $total_luas += $total;
            echo "<tr>
            <td align='center'>".NumToRomawi($tingkat_lantai)."/".$tahun_bangun."</td>
            <td align='right'>".number_format($teras,2,'.',',')."</td>
            <td align='right'>".number_format($ruang_tamu,2,'.',',')."</td><td align='right'>".number_format($ruang_keluarga,2,'.',',')."</td>
            <td align='right'>".number_format($ruang_tidur1,2,'.',',')."</td><td align='right'>".number_format($ruang_tidur2,2,'.',',')."</td>
            <td align='right'>".number_format($ruang_tidur3,2,'.',',')."</td><td align='right'>".number_format($ruang_dapur,2,'.',',')."</td>
            <td align='right'>".number_format($kamar_mandi,2,'.',',')."</td><td align='right'>".number_format($lain_lain,2,'.',',')."</td>
            <td align='right'>".number_format($total,2,'.',',')."</td>
            <td>";
                if($editAccess)
                    echo "<button type='button' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' onclick=\"load_form_content(this.id);\">";
                else
                    echo "<button type='button' title='Edit' class='btn btn-xs btn-default' onclick=\"alert('Anda tidak memiliki akses untuk mengedit data!');\">";
                echo "                              
                  <input type='hidden' id='ajax-req-dt' name='id' value='".$id_luas_bangunan."'/>
                  <input type='hidden' id='ajax-req-dt' name='id_penugasan' value='".$fk_penugasan."'/>
                  <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                  <input type='hidden' id='ajax-req-dt' name='act' value='edit'/>
                  <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
                  <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
                  <i class='fa fa-edit'></i>
                </button>&nbsp;";
                if($deleteAccess)
                    echo "<button type='button' title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"if(confirm('Anda yakin?')){exec_delajax(this.id)}\">";
                else
                    echo "<button type='button' title='Hapus' class='btn btn-xs btn-default' onclick=\"alert('Anda tidak memiliki akses untuk menghapus data!');\">";
                    
                echo "
                <input type='hidden' id='ajax-req-dt' name='id' value='".$id_luas_bangunan."'/>
                <input type='hidden' id='ajax-req-dt' name='fk_penugasan' value='".$fk_penugasan."'/>
                <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                <input type='hidden' id='ajax-req-dt' name='act' value='delete'/>
                <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
                <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
                <i class='fa fa-trash-o'></i></button>
            </td>
            </tr>";
        }
        echo "</tbody>
        <tfoot>
            <tr><td colspan='11' align='right'><b>Total Luas Bangunan</b></td>
            <td align='right'><b>".number_format($total_luas,2,'.',',')."</b></td>
            </tr>
        </tfoot>
    </table>"
?>