<?php
    echo "
    <table class='table table-bordered table-striped'>
        <thead>
            <tr><td class='tableHead'>No. Urut</td><td class='tableHead'>Alamat</td><td class='tableHead'>Pemberi Data</td><td class='tableHead'>Jenis Objek</td><td class='tableHead'>Jarak Dari Properti</td><td width='8%' class='tableHead'>Aksi</td></tr>
        </thead>
        <tbody>";
        $no = 0;
        while($row = $list_of_data->FetchRow())
        {
            $no++;
            foreach($row as $key1 => $val1){
                $key1=strtolower($key1);
                $$key1=$val1;
            }
            echo "<tr>
            <td>Data ".$no_urut."</td><td>".$alamat."</td><td>".$pemberi_data."</td>
            <td>".$jenis_objek."</td><td>".$jarak_dari_properti."</td>
            <td>";
                if($editAccess)
                    echo "<button type='button' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' onclick=\"load_form_content(this.id);\">";
                else
                    echo "<button type='button' title='Edit' class='btn btn-xs btn-default' onclick=\"alert('Anda tidak memiliki akses untuk mengedit data!');\">";
                echo "                              
                  <input type='hidden' id='ajax-req-dt' name='id' value='".$id_objek_pembanding."'/>
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
                <input type='hidden' id='ajax-req-dt' name='id' value='".$id_objek_pembanding."'/>
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
    </table>"
?>