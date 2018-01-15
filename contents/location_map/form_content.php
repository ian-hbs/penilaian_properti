<?php
    
    session_start();
    include_once "../../config/superglobal_var.php";
    include_once "../../config/db_connection.php";
    include_once "../../libraries/DML.php";
    include_once "../../libraries/user_controller.php"; 

    $DML = new DML('peta_lokasi',$db);
    
    $id_penugasan = $_POST['id_penugasan'];
    $jenis = $_POST['jenis'];
    $kunci_pencarian = $_POST['kunci_pencarian'];    
    $menu_id = $_POST['menu_id'];
    $fn = $_POST['fn'];

    $id_name = 'id_peta';
    $id_value = $_POST['id'];
    $act = ($id_value==''?'add':'edit');
    $arr_field = array('file_foto','keterangan');
    $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'location-map-form';
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
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis">Jenis <font color="red">*</font></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">                        
                        <select class="form-control autofill-bg">
                            <option value="" selected></option>
                            <?php
                                $arr_opt = array('peta1'=>'Peta 1','peta2'=>'Peta 2','peletakan_tanah'=>'Peletakan Tanah','peletakan_bangunan'=>'Peletakan Bangunan');
                                foreach($arr_opt as $key=>$val)
                                {
                                    $selected = ($jenis==$key?'selected':'');
                                    echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                }
                            ?>
                        </select>
                        <input type="hidden" name="jenis" value="<?=$jenis?>"/>
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