<?php
    
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/DML.php";
    include_once "../../libraries/user_controller.php"; 

    $DML1 = new DML('foto_properti_pembanding',$db);
    $DML2 = new DML('objek_pembanding',$db);
    
    $id_penugasan = $_POST['id_penugasan'];
    $id_objek_pembanding = $_POST['id_objek_pembanding'];
    $kunci_pencarian = $_POST['kunci_pencarian'];    
    $menu_id = $_POST['menu_id'];
    $fn = $_POST['fn'];

    $id_name = 'id_foto_properti_pembanding';
    $id_value = $_POST['id'];
    $act = ($id_value==''?'add':'edit');
    $arr_field = array('file_foto','keterangan');
    $curr_data = $DML1->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'comparative-property-photo-form';    
?>

    <script type="text/javascript">
        var form_id = '<?php echo $form_id;?>';
        var $input_form = $('#'+form_id);
	</script>

    <form id="<?=$form_id?>" class="form-horizontal form-label-left" method="POST" action="contents/<?=$fn?>/manipulating.php" onsubmit="uploadFiles(event);" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?=$id_value?>"/>
        <input type="hidden" name="act" value="<?=$act?>"/>
        <input type="hidden" name="menu_id" value="<?=$menu_id?>"/>
        <input type="hidden" name="fn" value="<?=$fn?>"/>
        <input type="hidden" name="fk_penugasan" value="<?=$id_penugasan?>"/>
        <input type="hidden" name="kunci_pencarian" value="<?=$kunci_pencarian?>"/>
        <input type="hidden" name="_file_foto" value="<?=$curr_data['file_foto']?>"/>
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fk_objek_pembanding">Objek Pembanding <font color="red">*</font></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">                        
                        <select class="form-control autofill-bg" disabled>
                            <option value="" selected></option>
                            <?php
                                $data = $DML2->fetchDataBy('fk_penugasan',$id_penugasan);
                                foreach($data as $row)
                                {
                                    $selected = ($id_objek_pembanding==$row['id_objek_pembanding']?'selected':'');
                                    echo "<option value='".$row['id_objek_pembanding']."' ".$selected.">Data ".$row['no_urut']."</option>";
                                }
                            ?>
                        </select>
                        <input type="hidden" name="fk_objek_pembanding" value="<?=$id_objek_pembanding?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="file_foto">File Foto <font color="red">*</font></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <input type="file" id="file_foto" name="file_foto" onchange="prepareUpload(event);" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangan">Keterangan</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <input type="text" id="keterangan" name="keterangan" value="<?=$curr_data['keterangan']?>" class="form-control">
                    </div>
                </div>                
            </div>
        </div>

        <div class="row">            
            <div class="col-md-12" align="right">                
                <button type="button" class="btn btn-danger" onclick="close_form();">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>                    
            </div>
        </div>        
    </form>
