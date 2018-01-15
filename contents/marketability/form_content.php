<?php
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/DML.php";
    include_once "../../libraries/global_obj.php";
    include_once "../../libraries/user_controller.php";
    include_once "../../helpers/date_helper.php";

    //instantiate objects
    $uc = new user_controller($db);    

    $uc->check_access();

    $id_penugasan = $_POST['id_penugasan'];
    $menu_id = $_POST['menu_id'];
    $fn = $_POST['fn'];
    $kunci_pencarian = $_POST['kunci_pencarian'];
	
    $DML = new DML('marketabilitas',$db);
    $global = new global_obj($db);

    $sql = "SELECT count(1) as tot_rec FROM marketabilitas WHERE fk_penugasan='".$id_penugasan."'";
    $tot_rec = $db->getOne($sql);
    
    $act = ($tot_rec==0?'add':'edit');
    $act_lbl = ($act=='add'?'menambah':'memperbaharui');

    $id_name = 'fk_penugasan';
    $id_value = ($act=='edit'?$id_penugasan:'');
    $arr_field = array('lokasi_perumahan','lokasi_agunan','jarak_fasum_fasos','fasilitas_jenis_fasum_fasos','kondisi_jalan_ke_kota','kondisi_jalan_lingkungan',
                       'kenyamanan','jenis_jalan_lingkungan','aksesbilitas_jarak_ke_jalan_propinsi','resiko_bencana_banjir','persen_perumahan',
                       'persen_industri','persen_perkantoran','persen_pertokoan','persen_taman','persen_kosong');
    $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'marketability-form';
?>

<script type="text/javascript">
    var form_id = '<?php echo $form_id;?>';
    var $input_form = $('#'+form_id);
    var stat = $input_form.validate();
    var act_lbl = '<?php echo $act_lbl;?>';

    $input_form.submit(function(){
        if(stat.checkForm())
        {
            ajax_manipulate.reset_object();
            ajax_manipulate.set_plugin_datatable(true)
                           .set_content('#list-of-data')
                           .set_loading('#preloadAnimation')
                           .enable_pnotify()
                           .set_close_modal('#close-modal-form')
                           .set_form($input_form)
                           .submit_ajax(act_lbl);
            $('#close-modal-form').click();
            return false;
        }
    });

</script>

<?php
    
    $pd = $global->get_property_detail($id_penugasan);
    $pd['tgl_survei'] = indo_date_format($pd['tgl_survei'],'longDate');
    
    $global->print_property_detail($pd);
    
    echo "
    <form class='form-horizontal' id='".$form_id."' method='POST' action='contents/".$fn."/manipulating.php'>
    <input type='hidden' name='fk_penugasan' value='".$id_penugasan."'/>
    <input type='hidden' name='menu_id' value='".$menu_id."'/>
    <input type='hidden' name='fn' value='".$fn."'/>
    <input type='hidden' name='act' value='".$act."'/>
    <input type='hidden' name='kunci_pencarian' value='".$kunci_pencarian."'/>
        <div class='row'>
            <div class='col-md-6'>
                <div class='form-group'>
                    <label class='col-sm-5 control-label'>Lokasi Perumahan <font color='red'>*</font></label>
                    <div class='col-sm-7'>
                        <select name='lokasi_perumahan' id='lokasi_perumahan' class='form-control' required>
                        <option value='' selected></option>";              
                        $arr_opt = array('Dalam kota','Dekat kota','Jauh dari kota');
                        foreach($arr_opt as $key=>$val)
                        {
                            $selected = ($curr_data['lokasi_perumahan']==$val?'selected':'');
                            echo "<option value='".$val."' ".$selected.">".$val."</option>";
                        }
                      echo "</select>
                    </div>
                </div>
                <div class='form-group'>                  
                  <label class='col-sm-5 control-label'>Lokasi Agunan <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                  <select name='lokasi_agunan' id='lokasi_agunan' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('Di hook dan atau taman','Tidak di hook dan atau depan taman','Tusuk sate');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['lokasi_agunan']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select></div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Jarak Fasum Fasos <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='jarak_fasum_fasos' id='jarak_fasum_fasos' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('< 2 Km','2 Km s/d 5 Km','5 Km s/d 7 Km','7 Km s/d 10 Km','> 10 Km');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['jarak_fasum_fasos']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Fasilitas Jenis Fasum Fasos <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='fasilitas_jenis_fasum_fasos' id='fasilitas_jenis_fasum_fasos' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('Lengkap (Pasar, Sekolah, RS, Tempat ibadah)','Rata-rata (Pasar, Sekolah, Puskesmas dan Tempat Ibadah)',
                                     'Minimal (Pasar, Sekolah, Klinik dan Tempat Ibadah)');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['fasilitas_jenis_fasum_fasos']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Kondisi Jalan ke Kota <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='kondisi_jalan_ke_kota' id='kondisi_jalan_ke_kota' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('Tidak Macet','Relatif macet','Sering Macet');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['kondisi_jalan_ke_kota']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Kondisi Jalan Lingkungan <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='kondisi_jalan_lingkungan' id='kondisi_jalan_lingkungan' class='form-control' required>
                    <option value='' selected></option>";              
                    $arr_opt = array('Jauh dari kota','Sering macet','Tidak macet');
                    foreach($arr_opt as $key=>$val)
                    {
                        $selected = ($curr_data['kondisi_jalan_lingkungan']==$val?'selected':'');
                        echo "<option value='".$val."' ".$selected.">".$val."</option>";
                    }
                  echo "</select>
                  </div>
                </div>
            </div><!-- /.col -->
            <div class='col-md-6'>                  
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Kenyamanan <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='kenyamanan' id='kenyamanan' class='form-control' required>
                      <option value='' selected></option>";              
                        $arr_opt = array('Jauh dari tempat maksiat','Cukup jauh dari tempat maksiat','Dekat dengan tempat maksiat');
                        foreach($arr_opt as $key=>$val)
                        {
                            $selected = ($curr_data['kenyamanan']==$val?'selected':'');
                            echo "<option value='".$val."' ".$selected.">".$val."</option>";
                        }
                    echo "
                    </select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Jenis Jalan Lingkungan <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='jenis_jalan_lingkungan' id='jenis_jalan_lingkungan' class='form-control' required>
                      <option value='' selected></option>";              
                        $arr_opt = array('Aspal','Beton balok','Tanah dan sejenisnya');
                        foreach($arr_opt as $key=>$val)
                        {
                            $selected = ($curr_data['jenis_jalan_lingkungan']==$val?'selected':'');
                            echo "<option value='".$val."' ".$selected.">".$val."</option>";
                        }
                    echo "
                    </select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Aksesbilitas Jarak ke Jl. Prop. <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='aksesbilitas_jarak_ke_jalan_propinsi' id='aksesbilitas_jarak_ke_jalan_propinsi' class='form-control' required>
                      <option value='' selected></option>";              
                        $arr_opt = array('< 2 Km','2 Km s/d 5 Km','5 Km s/d 7 Km','7 Km s/d 10 Km','> 10 Km');
                        foreach($arr_opt as $key=>$val)
                        {
                            $selected = ($curr_data['aksesbilitas_jarak_ke_jalan_propinsi']==$val?'selected':'');
                            echo "<option value='".$val."' ".$selected.">".$val."</option>";
                        }
                    echo "
                    </select>
                  </div>
                </div>
                <div class='form-group'>
                  <label class='col-sm-5 control-label'>Resiko Bencana Banjir <font color='red'>*</font></label>
                  <div class='col-sm-7'>
                    <select name='resiko_bencana_banjir' id='resiko_bencana_banjir' class='form-control' required>
                      <option value='' selected></option>";              
                        $arr_opt = array('Tidak ada','Kadang-kadang','Sering');
                        foreach($arr_opt as $key=>$val)
                        {
                            $selected = ($curr_data['resiko_bencana_banjir']==$val?'selected':'');
                            echo "<option value='".$val."' ".$selected.">".$val."</option>";
                        }
                    echo "
                    </select>
                  </div>
                </div>
                <div class='form-group'>
                  <div class='col-sm-12'>
                    <div class='row'>
                        <div class='col-sm-6'>
                            <table border=0 width='100%'>
                                <tr>
                                    <td width='50%'><b>Perumahan</b></td>
                                    <td width='30%'><input type='text' name='persen_perumahan' class='form-control' style='text-align:right' value='".$curr_data['persen_perumahan']."' onkeypress=\"return only_number(event,this)\"/></td>
                                    <td width='20%'>&nbsp;%</td>
                                </tr>
                                <tr>
                                    <td><b>Industri</b></td>
                                    <td><input type='text' name='persen_industri' class='form-control' style='text-align:right' value='".$curr_data['persen_industri']."' onkeypress=\"return only_number(event,this)\"/></td>
                                    <td>&nbsp;%</td>
                                </tr>
                                <tr>
                                    <td><b>Perkantoran</b></td>
                                    <td><input type='text' name='persen_perkantoran' class='form-control' style='text-align:right' value='".$curr_data['persen_perkantoran']."' onkeypress=\"return only_number(event,this)\"/></td>
                                    <td>&nbsp;%</td>
                                </tr>
                            </table>
                        </div>
                        <div class='col-sm-6'>
                            <table border=0 width='100%'>
                                <tr>
                                    <td width='50%'><b>Pertokoan</b></td>
                                    <td width='30%'><input type='text' name='persen_pertokoan' class='form-control' style='text-align:right' value='".$curr_data['persen_pertokoan']."' onkeypress=\"return only_number(event,this)\"/></td>
                                    <td width='20%'>&nbsp;%</td>
                                </tr>
                                <tr>
                                    <td><b>Taman</b></td>
                                    <td><input type='text' name='persen_taman' class='form-control' style='text-align:right' value='".$curr_data['persen_taman']."' onkeypress=\"return only_number(event,this)\"/></td>
                                    <td>&nbsp;%</td>
                                </tr>
                                <tr>
                                    <td><b>Kosong</b></td>
                                    <td><input type='text' name='persen_kosong' class='form-control' style='text-align:right' value='".$curr_data['persen_kosong']."' onkeypress=\"return only_number(event,this)\"/></td>
                                    <td>&nbsp;%</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
        <div class='ln_solid'></div>
        <div class='form-group'>
            <div class='col-md-12 col-sm-12 col-xs-12' align='center'>
                <button type='button' class='btn btn-danger' id='close-modal-form' data-dismiss='modal'>Batal</button>
                <button type='submit' class='btn btn-success'>Simpan</button>
            </div>
        </div>
    </form>";
?>