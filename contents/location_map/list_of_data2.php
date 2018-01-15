<?php
    $list_of_data2 = "<div class='row'>";    
        

    $arr_peta = array('peta1'=>'Peta 1','peta2'=>'Peta 2','peletakan_tanah'=>'Peletakan Tanah','peletakan_bangunan'=>'Peletakan Bangunan');
    $no=0;
    foreach($arr_peta as $key => $val)
    {
      $map = $maps[$no];
      $src = ($map[0]==0?"assets/images/no-thumb.png":"uploads/location_maps/".$map[1]['file_foto']);

      $list_of_data2.= "<div class='col-sm-6 col-md-3'><div class='thumbnail'><img src='".$src."'>
                      </div><div class='caption'>
                      <h4>".$val."</h4>";
      $list_of_data2 .= "<p>".($map[0]==0?'':$map[1]['keterangan'])."</p><p>";

      if($editAccess)
          $list_of_data2 .= "<a href='javascript:;' class='btn btn-primary btn-xs' role='button' id='edit_".$no."' onclick=\"load_form_content(this.id);\">";
      else
          $list_of_data2 .= "<a href='javascript:;' class='btn btn-primary btn-xs' role='button' onclick=\"alert('Anda tidak memiliki akses untuk mengedit data!');\">";

      $list_of_data2 .= "<input type='hidden' id='ajax-req-dt' name='id' value='".($map[0]==0?'':$map[1]['id_peta'])."'/>
                         <input type='hidden' id='ajax-req-dt' name='id_penugasan' value='".$id_penugasan."'/>
                         <input type='hidden' id='ajax-req-dt' name='jenis' value='".$key."'/>
                         <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                         <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
                         <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/><i class='fa fa-edit'></i>&nbsp;Edit</a>&nbsp;";

      if($map[0]>0)
      {
        if($deleteAccess)
            $list_of_data2 .= "<a href='javascript:;' class='btn btn-danger btn-xs' role='button' id='delete_".$no."' onclick=\"if(confirm('Anda yakin?')){exec_delajax(this.id)}\">";
        else
            $list_of_data2 .= "<a href='javascript:;' class='btn btn-danger btn-xs' role='button' onclick=\"alert('Anda tidak memiliki akses untuk menghapus data!');\">";
        $list_of_data2 .= "<input type='hidden' id='ajax-req-dt' name='id' value='".$map[1]['id_peta']."'/>
                           <input type='hidden' id='ajax-req-dt' name='filename' value='".$map[1]['file_foto']."'/>
                          <input type='hidden' id='ajax-req-dt' name='fk_penugasan' value='".$id_penugasan."'/>
                          <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                          <input type='hidden' id='ajax-req-dt' name='act' value='delete'/>
                          <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
                          <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/><i class='fa fa-trash-o'></i>&nbsp;Hapus</a>";
      }
      $list_of_data2 .= "</p></div></div>";
      $no++;
    }

    $list_of_data2 .= "</div>";
?>