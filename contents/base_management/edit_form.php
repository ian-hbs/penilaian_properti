<?php
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../config/app_param.php";
    include_once "../../libraries/DML.php";
    include_once "../../libraries/user_controller.php";

    //instantiate objects
    $uc = new user_controller($db);    
    $DML1 = new DML('ref_jenis_objek',$db);
    $DML2 = new DML('ref_districts',$db);
    $DML3 = new DML('ref_villages',$db);
    $DML4 = new DML('ref_jenis_sertifikat',$db);
    $DML5 = new DML('ref_penilai',$db);
    $DML6 = new DML('ref_perusahaan_penunjuk',$db);
    $DML7 = new DML('ref_provinces',$db);
    $DML8 = new DML('ref_regencies',$db);

    $uc->check_access();

    $id_penugasan = $_POST['id_penugasan'];
    $menu_id = $_POST['menu_id'];
    $fn = $_POST['fn'];
    $kunci_pencarian = $_POST['kunci_pencarian'];
        
    
    $sql = "SELECT a.id_penugasan,a.perusahaan_penilai,f.perusahaan_penunjuk,a.no_penugasan,DATE_FORMAT(a.tgl_penugasan,'%d-%m-%Y') as tgl_penugasan,
            a.nama_pengorder1,a.jabatan_pengorder1,a.nama_pengorder2,a.jabatan_pengorder2,a.no_laporan,DATE_FORMAT(a.tgl_laporan,'%d-%m-%Y') as tgl_laporan,
            DATE_FORMAT(a.tgl_survei,'%d-%m-%Y') as tgl_survei,a.reviewer1,a.reviewer2,a.penilai1,a.penilai2,a.keperluan_penugasan,a.fk_perusahaan_penunjuk,
            b.fk_jenis_objek,b.alamat as alamat_properti,b.kelurahan,b.kecamatan,b.kota,b.provinsi,b.kd_pos,
            c.nama,c.alamat as alamat_debitur,c.no_tlp_kantor,c.no_ponsel,
            d.perusahaan_penilai,d.depan,d.belakang,d.kanan,d.kiri,d.klien_pendamping_lokasi,d.status_objek,d.dihuni_oleh,DATE_FORMAT(d.tgl_pemeriksaan,'%d-%m-%Y') as tgl_pemeriksaan,
            d.keterangan,e.fk_jenis_sertifikat,e.no_sertifikat,DATE_FORMAT(e.tgl_terbit_sertifikat,'%d-%m-%Y') as tgl_terbit_sertifikat,DATE_FORMAT(e.tgl_jatuh_tempo_sertifikat,'%d-%m-%Y') as tgl_jatuh_tempo_sertifikat,
            e.no_gs_su,DATE_FORMAT(e.tgl_gs_su,'%d-%m-%Y') as tgl_gs_su,e.atas_nama,e.hubungan_dengan_calon_nasabah,e.luas_tanah,e.prosentase_bangunan,e.tinggi_halaman_thd_jalan,
            e.tinggi_halaman_thd_lantai,e.keadaan_halaman FROM penugasan as a, properti as b, debitur as c, pemeriksaan as d, objek_tanah as e, ref_perusahaan_penunjuk as f 
            WHERE (a.id_penugasan=b.fk_penugasan AND a.id_penugasan=c.fk_penugasan AND a.id_penugasan=d.fk_penugasan AND a.id_penugasan=e.fk_penugasan AND a.fk_perusahaan_penunjuk=f.id_perusahaan_penunjuk) 
            AND (a.id_penugasan='".$id_penugasan."')";
    $result = $db->Execute($sql);
    if(!$result)
    {
        die($db->ErrorMsg());
    }
    $curr_data = $result->FetchRow();

    $SYSTEM_PARAMS = $_APP_PARAM['system_params'];
?>

<script type="text/javascript">
    var $input_form1=$('#form-edit-sspd1');
    var $input_form2=$('#form-edit-sspd2');
    var $input_form3=$('#form-edit-sspd3');
    var $input_form4=$('#form-edit-sspd4');
    var $input_form5=$('#form-edit-sspd5');
    var $input_form6=$('#form-edit-sspd6');

    var stat1=$input_form1.validate();
    var stat2=$input_form2.validate();
    var stat3=$input_form3.validate();
    var stat4=$input_form4.validate();
    var stat5=$input_form5.validate();
    var stat6=$input_form6.validate();

    var act_lbl='memperbaharui';

    $input_form1.submit(function(){
        if(stat1.checkForm())
        {
            ajax_manipulate.reset_object();
            ajax_manipulate.set_plugin_datatable(true)
                           .set_content('#list-of-data')
                           .set_loading('#preloadAnimation')
                           .enable_pnotify()
                           .set_form($input_form1)
                           .submit_ajax(act_lbl);
            return false;
        }
    });

    $input_form2.submit(function(){
        if(stat2.checkForm())
        {
            ajax_manipulate.reset_object();
            ajax_manipulate.set_plugin_datatable(true)
                           .set_content('#list-of-data')
                           .set_loading('#preloadAnimation')
                           .enable_pnotify()
                           .set_form($input_form2)
                           .submit_ajax(act_lbl);            
            return false;
        }
    });

    $input_form3.submit(function(){
        if(stat3.checkForm())
        {
            ajax_manipulate.reset_object();
            ajax_manipulate.set_plugin_datatable(true)
                           .set_content('#list-of-data')
                           .set_loading('#preloadAnimation')
                           .enable_pnotify()
                           .set_form($input_form3)
                           .submit_ajax(act_lbl);            
            return false;
        }
    });

    $input_form4.submit(function(){
        if(stat4.checkForm())
        {
            ajax_manipulate.reset_object();
            ajax_manipulate.set_plugin_datatable(true)
                           .set_content('')
                           .set_loading('#preloadAnimation')
                           .enable_pnotify()
                           .set_form($input_form4)
                           .submit_ajax(act_lbl);            
            return false;
        }
    });

    $input_form5.submit(function(){
        if(stat5.checkForm())
        {
            ajax_manipulate.reset_object();
            ajax_manipulate.set_plugin_datatable(true)
                           .set_content('')
                           .set_loading('#preloadAnimation')
                           .enable_pnotify()
                           .set_form($input_form5)
                           .submit_ajax(act_lbl);            
            return false;
        }
    });

    $input_form6.submit(function(){
        if(stat6.checkForm())
        {
            ajax_manipulate.reset_object();
            ajax_manipulate.set_plugin_datatable(true)
                           .set_content('')
                           .set_loading('#preloadAnimation')
                           .enable_pnotify()
                           .set_form($input_form6)
                           .submit_ajax(act_lbl);            
            return false;
        }
    });
</script>

<?php    
        
    echo "
    <div class='panel panel-default tabs'>
        <ul class='nav nav-tabs' role='tablist'>
            <li class='active'><a href='#tab_form_content1' role='tab' data-toggle='tab'>Objek Penilaian</a></li>
            <li><a href='#tab_form_content2' role='tab' data-toggle='tab'>Debitur</a></li>
            <li><a href='#tab_form_content3' role='tab' data-toggle='tab'>Penugasan</a></li>
            <li><a href='#tab_form_content4' role='tab' data-toggle='tab'>Hasil Pemeriksaan</a></li>
            <li><a href='#tab_form_content5' role='tab' data-toggle='tab'>Data Tanah</a></li>
            <li><a href='#tab_form_content6' role='tab' data-toggle='tab'>Laporan Penilaian</a></li>
        </ul>                            
        <div class='panel-body tab-content'>
            <div class='tab-pane active' id='tab_form_content1'>
                <form id='form-edit-sspd1' name='form_edit_sspd1' class='form-horizontal' method='POST' action='contents/".$fn."/manipulating.php'>
                    <input type='hidden' name='fk_penugasan' value='".$id_penugasan."'/>
                    <input type='hidden' name='menu_id' value='".$menu_id."'/>
                    <input type='hidden' name='fn' value='".$fn."'/>                    
                    <input type='hidden' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                    <input type='hidden' name='form_type' value='form1'/>
                    <div id='edit_form_content1'>";
                    include "edit_form_content1.php";
                    echo "
                    </div>
                </form>
            </div>
            <div class='tab-pane' id='tab_form_content2'>
                <form id='form-edit-sspd2' name='form_edit_sspd2' class='form-horizontal' method='POST' action='contents/".$fn."/manipulating.php'>
                    <input type='hidden' name='fk_penugasan' value='".$id_penugasan."'/>
                    <input type='hidden' name='menu_id' value='".$menu_id."'/>
                    <input type='hidden' name='fn' value='".$fn."'/>                    
                    <input type='hidden' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                    <input type='hidden' name='form_type' value='form2'/>
                    <div id='edit_form_content2'>";
                    include "edit_form_content2.php";
                    echo "
                    </div>
                </form>
            </div>
            <div class='tab-pane' id='tab_form_content3'>
                <form id='form-edit-sspd3' name='form_edit_sspd3' class='form-horizontal' method='POST' action='contents/".$fn."/manipulating.php'>
                    <input type='hidden' name='fk_penugasan' value='".$id_penugasan."'/>
                    <input type='hidden' name='menu_id' value='".$menu_id."'/>
                    <input type='hidden' name='fn' value='".$fn."'/>                    
                    <input type='hidden' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                    <input type='hidden' name='form_type' value='form3'/>
                    <div id='edit_form_content3'>";
                    include "edit_form_content3.php";
                    echo "
                    </div>
                </form>
            </div>
            <div class='tab-pane' id='tab_form_content4'>
                <form id='form-edit-sspd4' name='form_edit_sspd4' class='form-horizontal' method='POST' action='contents/".$fn."/manipulating.php'>
                    <input type='hidden' name='fk_penugasan' value='".$id_penugasan."'/>
                    <input type='hidden' name='menu_id' value='".$menu_id."'/>
                    <input type='hidden' name='fn' value='".$fn."'/>                    
                    <input type='hidden' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                    <input type='hidden' name='form_type' value='form4'/>
                    <div id='edit_form_content4'>";
                    include "edit_form_content4.php";
                    echo "
                    </div>
                </form>
            </div>
            <div class='tab-pane' id='tab_form_content5'>
                <form id='form-edit-sspd5' name='form_edit_sspd5' class='form-horizontal' method='POST' action='contents/".$fn."/manipulating.php'>
                    <input type='hidden' name='fk_penugasan' value='".$id_penugasan."'/>
                    <input type='hidden' name='menu_id' value='".$menu_id."'/>
                    <input type='hidden' name='fn' value='".$fn."'/>
                    <input type='hidden' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                    <input type='hidden' name='form_type' value='form5'/>
                    <input type='hidden' name='_luas_tanah' value='".$curr_data['luas_tanah']."'/>
                    <div id='edit_form_content5'>";
                    include "edit_form_content5.php";
                    echo "
                    </div>
                </form>
            </div>
            <div class='tab-pane' id='tab_form_content6'>
                <form id='form-edit-sspd6' name='form_edit_sspd6' class='form-horizontal' method='POST' action='contents/".$fn."/manipulating.php'>
                    <input type='hidden' name='fk_penugasan' value='".$id_penugasan."'/>
                    <input type='hidden' name='menu_id' value='".$menu_id."'/>
                    <input type='hidden' name='fn' value='".$fn."'/>                    
                    <input type='hidden' name='kunci_pencarian' value='".$kunci_pencarian."'/>
                    <input type='hidden' name='form_type' value='form6'/>
                    <div id='edit_form_content6'>";
                    include "edit_form_content6.php";
                    echo "
                    </div>
                </form>
            </div>
        </div>
    </div>";    
?>