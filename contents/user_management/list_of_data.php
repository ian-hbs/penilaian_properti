<div class="box-header">
    <div class="row">
      <div class="col-md-6">
        <h3 class="box-title">Daftar User</h3>
      </div>
      <div class="col-md-6" align="right">          
          <?php 
            if($addAccess)              
              echo "<button type='button' class='btn btn-xs btn-default' onclick=\"load_form_content(this.id);\" id='add-btn' data-toggle='modal' data-target='#formModal'>";
            else
              echo "<button type='button' class='btn btn-xs btn-default' onclick=\"alert('Anda tidak memiliki akses untuk menambah data!');\">";

            echo "
            <input type='hidden' id='ajax-req-dt' name='act' value='add'/>
            <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
            <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
            <i class='fa fa-plus'></i> Tambah</button>";
          ?>
          <button type="button" class="btn btn-xs btn-default" onclick="load_content();"><i class="fa fa-refresh"></i> Refresh</button>
      </div>
    </div>
</div>
<div class="box-body">
    <?php                         
        if($readAccess)
        {
            echo "
            <table id='data-table-jq' class='table table-bordered table-striped'>
                    <thead>
                      <tr>
                        <th width='4%'>No.</th>
                        <th>Nama Lengkap/Username</th>
                        <th>Tipe User</th>
                        <th>ID Register</th>
                        <th>Terakhir dimodifikasi</th>
                        <th width='8%'>Blokir</th>                        
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
                            <td>
                              ".$fullname."<br />
                              <small><b>".$username."</b></small
                            </td>
                            <td>".$user_type."</td>
                            <td>".$register_id."</td>
                            <td align='center'>".$modified_time."</td>
                            <td align='center'>".$blocked."</td>
                            <td align='center'>";
                              if($modifiable=='1')
                              {
                                if($editAccess)
                                    echo "<button type='button' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' data-toggle='modal' data-target='#formModal' onclick=\"load_form_content(this.id);\">";
                                else
                                    echo "<button type='button' title='Edit' class='btn btn-xs btn-default' onclick=\"alert('Anda tidak memiliki akses untuk mengedit data!');\">";
                                echo "
                                <input type='hidden' id='ajax-req-dt' name='id' value='".$user_id."'/>
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
                                <input type='hidden' id='ajax-req-dt' name='id' value='".$user_id."'/>
                                <input type='hidden' id='ajax-req-dt' name='act' value='delete'/>
                                <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
                                <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
                                <i class='fa fa-trash-o'></i></button>";
                              }
                              else
                                echo "<font color='red'>can't modified</font>";
                            echo "</td>
                          </tr>
                          ";
                      }
                    echo "</tbody>
                  </table>";
        }
        else
        {
            echo "
            <div class='row'>
                <div class='col-md-12'>
                    <div class='alert alert-warning' role='alert'>
                    <button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
                    Anda tidak memiliki akses untuk melihat data
                    </div>
                </div>
            </div>";
        }
        
    ?>
</div>
