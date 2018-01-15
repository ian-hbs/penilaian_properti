<?php
    $list_of_data2 = "<div class='row'>";
    $no=0;
    while($row = $list_of_data->FetchRow())
    {
        $no++;
        foreach($row as $key => $val){
            $key=strtolower($key);
            $$key=$val;
        }
        $list_of_data2.= "<div class='col-sm-6 col-md-3' style='height:320px;overflow:auto'><div class='thumbnail'><img src='uploads/property_photos/".$file_foto."'>
                        </div><div class='caption'><p>".$keterangan."</p><p>";
        if($editAccess)
            $list_of_data2 .= "<a href='javascript:;' class='btn btn-primary btn-xs' role='button' id='edit_".$no."' onclick=\"load_form_content(this.id);\">";        
        else
            $list_of_data2 .= "<a href='javascript:;' class='btn btn-primary btn-xs' role='button' onclick=\"alert('Anda tidak memiliki akses untuk mengedit data!');\">";

        $list_of_data2 .= "<input type='hidden' id='ajax-req-dt' name='id' value='".$id_foto_properti."'/>
                           <input type='hidden' id='ajax-req-dt' name='id_penugasan' value='".$fk_penugasan."'/>
                           <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                           <input type='hidden' id='ajax-req-dt' name='act' value='edit'/>
                           <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
                           <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/><i class='fa fa-edit'></i>&nbsp;Edit</a>&nbsp;";
        if($deleteAccess)
            $list_of_data2 .= "<a href='javascript:;' class='btn btn-danger btn-xs' role='button' id='delete_".$no."' onclick=\"if(confirm('Anda yakin?')){exec_delajax(this.id)}\">";
        else
            $list_of_data2 .= "<a href='javascript:;' class='btn btn-danger btn-xs' role='button' onclick=\"alert('Anda tidak memiliki akses untuk menghapus data!');\">";
        $list_of_data2 .= "<input type='hidden' id='ajax-req-dt' name='id' value='".$id_foto_properti."'/>
                           <input type='hidden' id='ajax-req-dt' name='filename' value='".$file_foto."'/>
                          <input type='hidden' id='ajax-req-dt' name='fk_penugasan' value='".$fk_penugasan."'/>
                          <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                          <input type='hidden' id='ajax-req-dt' name='act' value='delete'/>
                          <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
                          <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/><i class='fa fa-trash-o'></i>&nbsp;Hapus</a></p></div></div>";
    }
    $list_of_data2 .= "</div>";
?>