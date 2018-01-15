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
    
    //get passed ajax $_POST var
    $id_penugasan = $_POST['id_penugasan'];
        
    
    $menu_id = $_POST['menu_id'];
    $fn = $_POST['fn'];
    $kunci_pencarian = $_POST['kunci_pencarian'];
	  //Eof --

    //initialize object
    $DML1 = new DML('perhitungan_tanah',$db);
    $DML2 = new DML('ref_jenis_sertifikat',$db);
    $global = new global_obj($db);
    //Eof --

    //decide wether appending action or editing action
    $sql = "SELECT count(1) as tot_rec FROM perhitungan_tanah WHERE fk_penugasan='".$id_penugasan."'";
    $tot_rec = $db->getOne($sql);
    
    $act = ($tot_rec==0?'add':'edit');
    $act_lbl = ($act=='add'?'menambah':'memperbaharui');
    //Eof --

    $error = 'terjadi kesalahan saat mengambil data dari server!';

    //get main data
    $sql = "SELECT a.id_penugasan,a.no_penugasan,b.alamat,b.kelurahan,b.kecamatan,b.kota,b.provinsi,c.nama,d.luas_tanah,
            (SELECT sum(total_floor_area) FROM perhitungan_bangunan as x WHERE (x.fk_penugasan=a.id_penugasan) AND (x.type='building')) as luas_bangunan,
            (SELECT sum(market_value) FROM perhitungan_bangunan as x WHERE (x.fk_penugasan=a.id_penugasan) and (x.type='building')) as building_mv, 
            (SELECT sum(market_value) FROM perhitungan_bangunan as x WHERE (x.fk_penugasan=a.id_penugasan) and (x.type='site improvement')) as site_improvement_mv,
            d.fk_jenis_sertifikat,e.built_year,e.construction,e.eco_use_life,e.cond_on_inspec,e.cost_sqm1,f.bentuk_tanah,g.jenis as jenis_perusahaan_penunjuk
            FROM penugasan as a, properti as b, debitur as c, objek_tanah as d, 
            (SELECT fk_penugasan,built_year,construction,eco_use_life,cond_on_inspec,cost_sqm1 FROM perhitungan_bangunan WHERE (main_building='Y')) as e,            
            daya_tarik_agunan as f,
            (SELECT id_perusahaan_penunjuk,jenis FROM ref_perusahaan_penunjuk) as g
            WHERE (a.id_penugasan=b.fk_penugasan AND a.id_penugasan=c.fk_penugasan AND a.id_penugasan=d.fk_penugasan 
                   AND a.id_penugasan=e.fk_penugasan AND a.id_penugasan=f.fk_penugasan AND a.fk_perusahaan_penunjuk=g.id_perusahaan_penunjuk)
            AND (a.id_penugasan='".$id_penugasan."')";

    
    $result = $db->Execute($sql);
    if(!$result)
      die('ERROR : '.$error);

    $main_row = $result->FetchRow();
    //Eof --

    //get comparison data
    $sql = "SELECT a.*,b.jenis_objek FROM objek_pembanding as a LEFT JOIN ref_jenis_objek as b ON (a.fk_jenis_objek=b.id_jenis_objek) WHERE a.fk_penugasan ='".$id_penugasan."'";
    $result = $db->Execute($sql);
    if(!$result)
      die('ERROR : '.$error);
    
    $arr_comparative = array();
    $i = 0;
    while($row = $result->FetchRow())
    {
      foreach($row as $key => $val){
          $arr_comparative[$i][$key] = $val;
      }
      $i++;
    }
    //Eof --

    //create order number of comparison data as an array
    $arr_no_urut = array();
    foreach($arr_comparative as $row){
      $arr_no_urut[$row['no_urut']]=$row['id_objek_pembanding'];
    }
    //Eof --

    //get comparison building valuation
    $sql = "SELECT a.no_urut,b.total_floor_area,b.built_year,b.cond_on_inspec,b.eco_use_life,b.cost_sqm1,b.construction,b.market_value 
            FROM objek_pembanding as a INNER JOIN perhitungan_bangunan_pembanding as b
            ON (a.id_objek_pembanding=b.fk_objek_pembanding) AND (a.fk_penugasan=b.fk_penugasan) WHERE(a.fk_penugasan='".$id_penugasan."')";
    $result = $db->Execute($sql);

    if(!$result)
      die('ERROR : '.$error);

    $arr_obj_comparison = array();    
    while($row = $result->FetchRow())
    {
      $nu = $row['no_urut'];
      foreach($row as $key => $val){
          $arr_obj_comparison[$nu][$key] = $val;
      }
    }
    //Eof --    

    $form_status = (count($arr_comparative)==count($arr_obj_comparison) && ($main_row['building_mv']!=null || $main_row['building_mv']!='')?true:false);
    
    if($form_status)
    {
      //get input element default value depending on the kind of action
      $id_name = 'fk_penugasan';
      $id_value = ($act=='edit'?$id_penugasan:'');
      $arr_field = array('land_area','land_title','building_area','built_year','condition','crn_of_building_per_sqm','construction',
                         'economic_life_of_building','frontage','wide_road_access','elevation','land_shape','position','time',
                         'topography','security_facility','indicated_property_value',
                         'indicated_building_market_value_sqm','indicated_building_market_value','indicated_land_value',
                         'indicated_property_value_land','indicated_land_value_sqm','indicated_land_value_weighted',
                         'indicated_land_value_amount','first_rounded','total_land_value','final_rounded',
                         'liquidation_weight','liquidation_value');

      $curr_data1 = $DML1->getCurrentData($act,$arr_field,$id_name,$id_value);
      //Eof --


      if($act=='edit')
      {
        $curr_data2 = array();
        $curr_data3 = array();

        if($main_row['jenis_perusahaan_penunjuk']=='1')
        {
          $x = array('time','land_title','land_area','land_use','land_shape','position','frontage','location','wide_road',
                     'elevasi');
        }
        else
        {
          $x = array('location','land_title','land_area','land_shape','frontage','wide_road','position','elevasi','development_environment',
                     'economic_factor','land_use','security_facility');
        }

        $y = array();
        
        foreach($x as $val)
          $y[$val] = $val.'_percent';
        foreach($x as $val)
          $y[$val.'_amount'] = $val.'_amount';
        
        $y = array_merge($y,array('total_adjusted_percent'=>'total_adjusted_percent','total_adjusted_amount'=>'total_adjusted_amount',
                                  'indicated_land_value'=>'indicated_land_value','id_adjustment_tanah_pembanding'=>'id_adjustment_tanah_pembanding'));

        foreach($arr_no_urut as $key1=>$val1)  
        {
          $sql = "SELECT * FROM perhitungan_tanah_pembanding WHERE(fk_penugasan='".$id_penugasan."' and fk_objek_pembanding='".$val1."')";
          $result1 = $db->Execute($sql);
          if($result1->RecordCount()>0)
          {
            $row1 = $result1->FetchRow();
            foreach($row1 as $key2=>$val2)
            {
              $curr_data2[$key1][$key2] = $val2;
            }

            $sql = "SELECT * FROM adjustment_tanah_pembanding WHERE(fk_perhitungan_tanah_pembanding='".$row1['id_perhitungan_tanah_pembanding']."')";
            $result2 = $db->Execute($sql);
            $row2 = $result2->FetchRow();
            foreach($y as $key2=>$val2)
            {
              $curr_data3[$key1][$val2] = $row2[$key2];
            }
          }
        }
      }

      //helper function
      function generate_js_function($function_name,$arr_param)
      {
        $function = "";

        $function = $function_name."(";
        $s = false;
        foreach($arr_param as $key=>$val)
        {
          $val_ = (strpos($val,'[')>-1?$val:"'".$val."'");
          $function .= ($s?",".$val_:$val_);
          $s=true;
        }
        
        $function .= ")";

        return $function;
      }

      function get_support_value($arr_field,$prefix='',$sufix='')
      {
        $result = "[";
        $s=false;
        foreach($arr_field as $key=>$val)
        {        
            $result .= ($s?",'".$prefix.$key.$sufix."'":"'".$prefix.$key.$sufix."'");
            $s = true;        
        }
        $result .= "]";
        return $result;
      }
      function get_field_value_from_db($key,$type='1',$no_urut_pembanding='')
      {
        $result = '';
        if($type=='1')
        {
          global $main_row;
          
          switch($key)
          {
            case 'land_area':$result = $main_row['luas_tanah'];break;
            case 'building_area':$result = $main_row['luas_bangunan'];break;
            case 'built_year':$result = $main_row['built_year'];break;
            case 'condition':$result = $main_row['cond_on_inspec'];break;
            case 'crn_of_building_per_sqm':$result = ($main_row['cost_sqm1']!=''?number_format($main_row['cost_sqm1']):'');break;
            case 'construction':$result = $main_row['construction'];break;
            case 'economic_life_of_building':$result = $main_row['eco_use_life'];break;
            case 'indicated_building_market_value_sqm':$result = number_format(($main_row['luas_bangunan']==0?0:$main_row['building_mv']/$main_row['luas_bangunan']));break;
            case 'indicated_building_market_value':$result = number_format($main_row['building_mv']);break;
            default:$result = '';
          }
        }
        else
        {
          global $arr_obj_comparison;
          
          $comparison_row = $arr_obj_comparison[$no_urut_pembanding];

          switch($key)
          {          
            case 'building_area':$result = $comparison_row['total_floor_area'];break;
            case 'built_year':$result = $comparison_row['built_year'];break;
            case 'condition':$result = $comparison_row['cond_on_inspec'];break;
            case 'crn_of_building_per_sqm':$result = ($comparison_row['cost_sqm1']!=''?number_format($comparison_row['cost_sqm1']):'');break;
            case 'construction':$result = $comparison_row['construction'];break;
            case 'economic_life_of_building':$result = $comparison_row['eco_use_life'];break;
            default:$result = '';
          } 
        }
        return $result;
      }
      //Eof --
    }

    //page variabel
    $property_location = $main_row['alamat'].', Kelurahan '.$main_row['kelurahan'].', Kecamatan '.$main_row['kecamatan'].', Kota '.$main_row['kota'].', '.$main_row['provinsi'];
    $form_id = 'land-valuation-form';
    //Eof --
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

<style type="text/css">
  table.tableForm{width:100%;border:3px solid #c5c5c5;}
  table.tableForm tr{border-bottom:1px solid #c5c5c5;}
  table.tableForm tbody > tr:last-child{border-bottom:none;}
  table.tableForm td{border-right:1px solid #c5c5c5;padding:3px;}
  table.tableForm td:last-child{border-right:none;}
  table.tableForm tbody > tr:hover{background:#eaeaea;}
</style>
<?php    
    
    $pd = $global->get_property_detail($id_penugasan);
    $pd['tgl_survei'] = indo_date_format($pd['tgl_survei'],'longDate');

    $global->print_property_detail($pd);
    
    if($form_status)
    {
      $colspan = (count($arr_comparative)*2)+3;
      echo "
      <form class='form-horizontal' id='".$form_id."' method='POST' action='contents/".$fn."/land_valuation_manipulating.php'>
        <input type='hidden' name='fk_penugasan' value='".$id_penugasan."'/>
        <input type='hidden' name='menu_id' value='".$menu_id."'/>
        <input type='hidden' name='fn' value='".$fn."'/>
        <input type='hidden' name='act' value='".$act."'/>
        <input type='hidden' name='kunci_pencarian' value='".$kunci_pencarian."'/>
        <input type='hidden' id='building_mv' value='".$main_row['building_mv']."'/>
        <input type='hidden' id='_building_area' value='".$main_row['luas_bangunan']."'/>
        <input type='hidden' id='site_improvement_mv' value='".$main_row['site_improvement_mv']."'/>
        <input type='hidden' name='jenis_perusahaan_penunjuk' value='".$main_row['jenis_perusahaan_penunjuk']."'/>";

            echo "
        <div class='row'>
          <div class='col-md-12' style='overflow:auto;'>
                  
            <table border=1 cellspacing=5 class='tableForm' cellpadding=5>
              <thead><tr><td class='tableHead' width='20%' colspan='2'>Description</td><td class='tableHead'>Property Appraised</td>";
              
              foreach($arr_no_urut as $key=>$val)
              {
                echo "
                <td class='tableHead' colspan='2'>
                  Data ".$key."
                  <input type='hidden' id='comparison_mv".$key."' value = '".$arr_obj_comparison[$key]['market_value']."'/>";
                  if($act=='edit')
                  {
                    $id_perhitungan_tanah_pembanding = (isset($curr_data2[$key])?$curr_data2[$key]['id_perhitungan_tanah_pembanding']:'');
                    $id_adjustment_tanah_pembanding = (isset($curr_data3[$key])?$curr_data3[$key]['id_adjustment_tanah_pembanding']:'');

                      echo "
                    <input type='hidden' name='id_perhitungan_tanah_pembanding".$key."' value='".$id_perhitungan_tanah_pembanding."'/>
                    <input type='hidden' name='id_adjustment_tanah_pembanding".$key."' value='".$id_adjustment_tanah_pembanding."'/>";
                  }
                echo "</td>";
              }

            echo "</tr></thead><tbody><tr><td colspan='".$colspan."' class='tableHead'>Data Comparison</td></tr>";

            $arr_field = array('alamat'=>'Address','pemberi_data'=>'Contact Person','status'=>'Title of Person','no_tlp'=>'Phone',
                       'jenis_objek'=>'Type of Property Data','jarak_dari_properti'=>'Distance of Property');
            
            $i = 0;
            foreach($arr_field as $key => $val)
            {
              if($i==0)
              {
                echo "<tr><td colspan='2'>".$val."</td><td rowspan='".count($arr_field)."' valign='top'>".$property_location."</td>";
                foreach($arr_comparative as $row)
                  echo "<td colspan='2'>".$row[$key]."</td>";
              }
              else
              {
                echo "<tr><td colspan='2'>".$val."</td>";
                foreach($arr_comparative as $row)
                  echo "<td colspan='2'>".$row[$key]."</td>";
              }
              echo "</tr>";
              $i++;
            }

            unset($arr_field);

            $arr_field1_1 = array('land_area'=>'Land Area','land_title'=>'Land Title','building_area'=>'Building Area','built_year'=>'Built Year','condition'=>'Condition',
                                'crn_of_building_per_sqm'=>'CRN of Building per sqm','construction'=>'Construction','economic_life_of_building'=>'Economic Life of Building','frontage'=>'Frontage (m)','wide_road_access'=>'Wide Road Access (m)',
                                'elevation'=>'Elevation (cm)','land_shape'=>'Land Shape','location'=>'Location','position'=>'Position','offering_price'=>'Offering Price',
                                'transaction_price'=>'Transaction Price','time'=>'Time');

            $arr_field1_2 = array('discount'=>'Discount','total_price'=>'Total Price','indicated_property_value'=>'Indicated Property Value',
                                'indicated_building_market_value_sqm'=>'Indicated Building Market Value/sqm','indicated_building_market_value'=>'Indicated Building Market Value',
                                'indicated_land_value'=>'Indicated Land Value','indicated_property_value_land'=>'Indicated Property Value/Land','indicated_land_value_sqm'=>'Indicated Land Value/sqm');
            

            if($main_row['jenis_perusahaan_penunjuk']=='1')
            {
              $arr_field1 = array_merge($arr_field1_1,$arr_field1_2);        

              $arr_field2 = array('time'=>'Time','land_title'=>'Land Title','land_area'=>'Land Area','land_use'=>'Land Use',
                                  'land_shape'=>'Land Shape','position'=>'Position','frontage'=>'Frontage','location'=>'Location',
                                  'wide_road'=>'Road Width','elevasi'=>'Elevation');
            }
            else
            {
              $arr_field1 = array_merge($arr_field1_1,array('topography'=>'Topography','security_facility'=>'Security Facility'),$arr_field1_2);

              $arr_field2 = array('location'=>'Location','land_title'=>'Land Title','land_area'=>'Land Area','land_shape'=>'Land Shape','frontage'=>'Frontage',
                                  'wide_road'=>'Road Width','position'=>'Position','elevasi'=>'Elevation','development_environment'=>'Development Environment',
                                  'economic_factor'=>'Economic Factor','land_use'=>'Land Use','security_facility'=>'Security Facility');

            }


            //Sof Existing Data fill box                
            $arr_have_function = array('land_area','offering_price','transaction_price','discount');
            
            $arr_field_value_from_db = array('land_area','building_area','built_year','condition','crn_of_building_per_sqm','construction','economic_life_of_building','indicated_building_market_value');

            $arr_read_only_field2 = array('building_area','built_year','condition','crn_of_building_per_sqm','economic_life_of_building','construction','total_price','indicated_property_value','indicated_building_market_value_sqm','indicated_building_market_value',
                                          'indicated_land_value','indicated_property_value_land','indicated_land_value_sqm');

            $arr_read_only_field1 = array_merge($arr_read_only_field2,array('land_area'));

            $arr_thousand_format_field = array('offering_price','transaction_price');
            
            $arr_thousand_formatted = array('crn_of_building_per_sqm','offering_price','transaction_price','total_price','indicated_property_value',
                                            'indicated_building_market_value_sqm','indicated_building_market_value',
                                            'indicated_land_value','indicated_property_value_land','indicated_land_value_sqm');

            $arr_only_number_field = array('built_year','land_area','building_area','crn_of_building_per_sqm','economic_life','frontage','wide_road_access','elevation',
                                          'offering_price','transaction_price');

            $arr_right_alignment_field = array('land_area','building_area','crn_of_building_per_sqm','economic_life_of_building','frontage','wide_road_access','elevation',
                                               'offering_price','transaction_price','discount','total_price','indicated_property_value','indicated_building_market_value_sqm',
                                               'indicated_building_market_value','indicated_land_value','indicated_property_value_land','indicated_land_value_sqm');

            $arr_readonlybg_field = array('total_price','indicated_property_value','indicated_building_market_value_sqm','indicated_building_market_value',
                                          'indicated_land_value','indicated_property_value_land','indicated_land_value_sqm');

            $arr_autofillbg_field = array('land_area','building_area','built_year','condition','crn_of_building_per_sqm','construction','economic_life_of_building');

            echo "<tr><td colspan='".$colspan."' class='tableHead'>Existing Data</td></tr>";

            foreach($arr_field1 as $key => $val)
            {   
              $input_value = "";
              $onkeypress1 = "";
              $onkeypress2 = "";
              $onkeyup1 = "";
              $onkeyup2 = "";
              $readonly1 = "";
              $readonly2 = "";
              $textalign = "left";
              $readonly_bg = "";
              $autofill_bg = "";

              if(in_array($key,$arr_field_value_from_db))          
                $input_value = get_field_value_from_db($key);

              if(in_array($key, $arr_read_only_field1))          
                $readonly1 = "readonly";            
              
              if(in_array($key, $arr_read_only_field2))          
                $readonly2 = "readonly";
              
              if(in_array($key, $arr_only_number_field))
              {
                $onkeypress1 = "onkeypress=\"return only_number(event,this)\"";
                $onkeypress2 = "onkeypress=\"return only_number(event,this)\"";     
              }
              if(in_array($key, $arr_thousand_format_field))
              {
                $onkeyup1 = "onkeyup=\"thousand_format(this);\"";
                $onkeyup2 = "onkeyup=\"thousand_format(this)";
              }
              if(in_array($key,$arr_right_alignment_field))          
                $textalign = "right";
              
              if(in_array($key,$arr_readonlybg_field))
                $readonly_bg = "readonly-bg";

              if(in_array($key,$arr_autofillbg_field))
                $autofill_bg = "autofill-bg";


              echo "
              <tr>
                <td colspan='2'>".$val."</td>
                <td align='center'>";
                if($key=='land_title')
                {
                  echo "<select name='".$key."0' id='".$key."0' required>
                    <option value='' selected></option>";
                    $data = $DML2->fetchAllData();
                    foreach($data as $row)
                    {
                        if($act=='add')
                          $selected = ($main_row['fk_jenis_sertifikat']==$row['id_jenis_sertifikat']?'selected':'');
                        else
                          $selected = ($curr_data1['land_title']==$row['akronim']?'selected':'');
                        
                        echo "<option value='".$row['akronim']."' ".$selected.">".$row['id_jenis_sertifikat']." - ".$row['akronim']."</option>";
                    }
                  echo "</select>";
                }
                else if($key=='land_shape')
                {
                    echo "<select name='".$key."0' id='".$key."0' required>
                    <option value='' selected></option>";
                    $arr_opt = array('Beraturan','Tidak beraturan','Trapesium','Letter L');
                    foreach($arr_opt as $opt_key=>$opt_val)
                    {
                        if($act=='add')
                          $selected = ($main_row['bentuk_tanah']==$opt_val?'selected':'');
                        else
                          $selected = ($curr_data1['land_shape']==$opt_val?'selected':'');
                        
                        echo "<option value='".$opt_val."' ".$selected.">".$opt_val."</option>";
                    }
                    echo "</select>";
                }
                else
                {
                  if($key!='offering_price' && $key!='transaction_price' && $key!='discount' && $key!='total_price')
                  {
                    $placeholder = ($key=='time'?"Placeholder='Format : dd-mm-yyyy'":"");
                    $_input_value = $input_value;
                    if($act=='edit')
                    {
                        $arr_x = array('land_area','building_area','built_year','condition',
                                        'crn_of_building_per_sqm','construction','economic_life_of_building');
                        
                        if(!in_array($key,$arr_x))
                        {
                            if(in_array($key,$arr_thousand_formatted))
                                $_input_value = number_format($curr_data1[$key]);
                            else if($key=='time')
                                $_input_value = indo_date_format($curr_data1[$key],'shortDate');
                            else
                                $_input_value = $curr_data1[$key];
                        } 
                    }
                    
                    echo "<input type='text' name='".$key."0' id='".$key."0' value='".$_input_value."' 
                          style='text-align:".$textalign."' class='".$readonly_bg." ".$autofill_bg."' ".$onkeypress1." ".$onkeyup1." ".$placeholder." ".$readonly1." required/>";
                  }
                }
                echo "</td>";
              
              foreach($arr_no_urut as $key2=>$val2)
              {
                $nu = $key2;
                $id = $key.$nu;
                $onkeyup = $onkeyup2;
                $arr_param = array();
                if(in_array($key,$arr_field_value_from_db))
                {
                  $input_value = get_field_value_from_db($key,'2',$nu);
                }
                if(in_array($key,$arr_have_function))
                {
                  $support_value1 = get_support_value($arr_field2,'','_percent'.$nu);
                  $support_value2 = get_support_value($arr_field2,'','_amount'.$nu);
                  $arr_support1 = array($support_value1,$support_value2);
                                
                  $support_value1 = get_support_value($arr_no_urut,'weighted_percent','');
                  $support_value2 = get_support_value($arr_no_urut,'weighted_amount','');
                  $arr_support2 = array($support_value1,$support_value2);              

                  if($key=='land_area')
                  {
                    $arr_param = array('indicated_property_value'.$nu,'land_area'.$nu,'indicated_property_value_land'.$nu,'indicated_land_value'.$nu,
                                       'indicated_land_value_sqm'.$nu);
                    
                    $arr_param = array_merge($arr_param,$arr_support1,array('total_adjusted_amount'.$nu,'indicated_land_value_amount'.$nu));
                    $arr_param = array_merge($arr_param,$arr_support2,array('weighted_percent'.$nu,'weighted_amount'.$nu,'indicated_land_value_final'));
                    
                    $onkeyup .= ($onkeyup2==''?"onkeyup=\"":";").generate_js_function('mix_function1',$arr_param);
                  }              
                  else if($key=='offering_price' || $key=='discount')
                  {
                    $arr_param = array('offering_price'.$nu,'discount'.$nu,'total_price'.$nu,'transaction_price'.$nu,
                                       'indicated_property_value'.$nu,'building_area'.$nu,'comparison_mv'.$nu,'indicated_building_market_value_sqm'.$nu,
                                       'indicated_building_market_value'.$nu,'indicated_land_value'.$nu,
                                       'land_area'.$nu,'indicated_property_value_land'.$nu,'indicated_land_value_sqm'.$nu);
                    
                    $arr_param = array_merge($arr_param,$arr_support1,array('total_adjusted_amount'.$nu,'indicated_land_value_amount'.$nu));
                    $arr_param = array_merge($arr_param,$arr_support2,array('weighted_percent'.$nu,'weighted_amount'.$nu,'indicated_land_value_final'));

                    $onkeyup .= ($onkeyup2==''?"onkeyup=\"":";").generate_js_function('mix_function2',$arr_param);
                  }
                }
                $onkeyup .= ($onkeyup==''?"":"\"");
                $class = "";

                if($key!='land_area')
                  $class = "class='".$readonly_bg." ".$autofill_bg."'";

                echo "<td align='center' colspan='2'>";
                
                if($key=='land_title')
                {
                  echo "<select name='".$id."' id='".$id."' required>
                    <option value='' selected></option>";
                    $data = $DML2->fetchAllData();
                    foreach($data as $row)
                    {
                        $selected = '';
                        if($act=='edit')
                          $selected = ($curr_data2[$nu][$key]==$row['akronim']?'selected':'');

                        echo "<option value='".$row['akronim']."' ".$selected.">".$row['id_jenis_sertifikat']." - ".$row['akronim']."</option>";
                    }
                  echo "</select>";
                }
                else if($key=='land_shape')
                {
                    echo "<select name='".$id."' id='".$id."' required>
                    <option value='' selected></option>";
                    $arr_opt = array('Beraturan','Tidak beraturan','Trapesium','Letter L');
                    foreach($arr_opt as $opt_key=>$opt_val)
                    {
                        $selected = '';
                        if($act=='edit')
                          $selected = ($curr_data2[$nu][$key]==$opt_val?'selected':'');

                        echo "<option value='".$opt_val."' ".$selected.">".$opt_val."</option>";
                    }
                    echo "</select>";
                }
                else
                {
                    $placeholder = ($key=='time'?"Placeholder='Format : dd-mm-yyyy'":"");
                    $_input_value = $input_value;
                    if($act=='edit')
                    {
                        $arr_x = array('building_area','built_year','condition','crn_of_building_per_sqm','construction','economic_life_of_building');
                        
                        if(!in_array($key,$arr_x))
                        {
                            $v = (isset($curr_data2[$nu])?$curr_data2[$nu][$key]:'');

                            if(in_array($key,$arr_thousand_formatted))
                                $_input_value = ($v!=''?number_format($curr_data2[$nu][$key]):'');
                            else if($key=='time')
                                $_input_value = ($v!=''?indo_date_format($v,'shortDate'):'');
                            else
                                $_input_value = $v;
                        }
                    }                

                    echo "<input type='text' name='".$id."' id='".$id."' value='".$_input_value."' style='text-align:".$textalign."' 
                        ".$class." ".$onkeyup." ".$onkeypress2." ".$placeholder." ".$readonly2." required/>";
                }
                echo "</td>";
              }

              echo "</tr>";
            }
            //Eof Existing Data fill box  

            //Adjustment fill box
            echo "<tr><td colspan='".$colspan."' class='tableHead'>Adjustment</td></tr>
                  <tr><td class='tableHead'>No.</td><td class='tableHead'>Adjustment Factor</td><td></td>";

            for($i=0;$i<count($arr_no_urut);$i++)
              echo "<td class='tableHead'>%</td><td class='tableHead'>Amount</td>";
            
            echo "</tr>";        

            $no=0;
            foreach($arr_field2 as $key => $val)
            {
              $no++;
              echo "<tr><td align='center'>".$no."</td><td>".$val."</td><td></td>";
              foreach($arr_no_urut as $key2=>$val2)
              {
                $nu = $key2;
                $id1 = $key.'_percent'.$nu;
                $id2 = $key.'_amount'.$nu;
                $id3 = 'weighted_percent'.$nu;
                $id4 = 'weighted_amount'.$nu;            

                $arr_param = array($id1,'indicated_land_value_sqm'.$nu,$id2,'total_adjusted_percent'.$nu,'total_adjusted_amount'.$nu,'indicated_land_value_amount'.$nu);
                $support_value1 = get_support_value($arr_field2,'','_percent'.$nu);
                $support_value2 = get_support_value($arr_field2,'','_amount'.$nu);
                $arr_support = array($support_value1,$support_value2);
                $arr_param = array_merge($arr_param,$arr_support);

                $arr_param2 = array($id3,$id4,'weighted_percent_final','indicated_land_value_final');
                $arr_param = array_merge($arr_param,$arr_param2);
                $support_value1 = get_support_value($arr_no_urut,'weighted_percent','');
                $support_value2 = get_support_value($arr_no_urut,'weighted_amount','');
                $arr_support = array($support_value1,$support_value2);
                $arr_param = array_merge($arr_param,$arr_support);

                $onkeyup = "onkeyup=\"".generate_js_function('mix_function3',$arr_param)."\"";

                $percent = (isset($curr_data3[$nu])?number_format($curr_data3[$nu][$key.'_percent'],2,'.',','):'');
                $amount = (isset($curr_data3[$nu])?number_format($curr_data3[$nu][$key.'_amount'],2,'.',','):'');

                $input_value1 = ($act=='add'?'':$percent);
                $input_value2 = ($act=='add'?'':$amount);
                echo "<td align='center'><input size=5 type='text' name='".$id1."' id='".$id1."' ".$onkeyup." value='".$input_value1."' style='text-align:right' onkeypress=\"return only_number(event,this);\"/></td>
                      <td align='center'><input type='text' name='".$id2."' id='".$id2."' class='readonly-bg' value='".$input_value2."' style='text-align:right' readonly/></td>";
              }
              echo "</tr>";
            }
            echo "<tr><td></td><td><b>Total Adjusted</b></td><td></td>";
              
            foreach($arr_no_urut as $key=>$val)
            {
              $nu = $key;
              $id1 = 'total_adjusted_percent'.$nu;
              $id2 = 'total_adjusted_amount'.$nu;
              
              $total_adjusted_percent = (isset($curr_data3[$nu])?number_format($curr_data3[$nu]['total_adjusted_percent'],2,'.',','):'');
              $total_adjusted_amount = (isset($curr_data3[$nu])?number_format($curr_data3[$nu]['total_adjusted_amount'],2,'.',','):'');

              $input_value1 = ($act=='add'?'':$total_adjusted_percent);
              $input_value2 = ($act=='add'?'':$total_adjusted_amount);
              echo "<td align='center'><input size=5 type='text' name='".$id1."' id='".$id1."' value='".$input_value1."' style='text-align:right;font-weight:bold' class='readonly-bg' readonly required/></td>
                        <td align='center'><input type='text' name='".$id2."' id='".$id2."' value='".$input_value2."' style='text-align:right;font-weight:bold' class='readonly-bg' readonly required/></td>";
            }

            echo "<tr><td></td><td><b>Indicated Land Value</b></td><td></td>";
            foreach($arr_no_urut as $key=>$val)
            {
              $nu = $key;
              $id = 'indicated_land_value_amount'.$nu;

              $indicated_land_value = (isset($curr_data3[$nu])?number_format($curr_data3[$nu]['indicated_land_value']):'');

              $input_value = ($act=='add'?'':$indicated_land_value);
              echo "<td></td><td align='center'><input type='text' name='".$id."' id='".$id."' value='".$input_value."' style='text-align:right;font-weight:bold' class='readonly-bg' readonly required/></td>";
            }
            //Eof Adjustment fill box

            //Indicative Value Table
            $colspan2 = 5;
            $colspan3 = $colspan-$colspan2;
            echo "<tr><td colspan='".$colspan2."' class='tableHead'>Indicative Value</td><td colspan='".$colspan3."'></td></tr>";

            foreach($arr_no_urut as $key => $val)
            {   
              $nu = $key;
              $id1 = 'weighted_percent'.$key;
              $id2 = 'weighted_amount'.$key;

              $arr_param = array($id1,'indicated_land_value_amount'.$key,$id2,'weighted_percent_final','indicated_land_value_final');
              $support_value1 = get_support_value($arr_no_urut,'weighted_percent','');
              $support_value2 = get_support_value($arr_no_urut,'weighted_amount','');
              $arr_support = array($support_value1,$support_value2);
              $arr_param = array_merge($arr_param,$arr_support);

              $weighted_percent = (isset($curr_data2[$nu])?$curr_data2[$nu]['weighted_percent']:'');
              $weighted_amount = (isset($curr_data2[$nu])?number_format($curr_data2[$nu]['weighted_amount'],2,'.',','):0);

              $input_value1 = ($act=='add'?'':$weighted_percent);
              $input_value2 = ($act=='add'?'':$weighted_amount);
              $onkeyup = "onkeyup=\"".generate_js_function('mix_function4',$arr_param)."\"";
              echo "<tr><td colspan='2'>DATA ".$nu."</td>
                          <td align='center'><input type='text' id='".$id1."' name='".$id1."' value='".$input_value1."' style='text-align:right' onkeypress=\"return only_number(event,this);\" ".$onkeyup."/> % </td>
                          <td align='center'>Rp.</td><td align='center'><input type='text' id='".$id2."' name='".$id2."' value='".$input_value2."' style='text-align:right' class='readonly-bg' readonly required/></td>
                          <td colspan='".$colspan3."'></td></tr>";
            }

            echo "<tr><td colspan='2'><b>Indicated Land Value</b></td>
                  <td align='center'><input type='text' id='weighted_percent_final' name='weighted_percent_final' value='".$curr_data1['indicated_land_value_weighted']."' style='text-align:right;font-weight:bold' class='readonly-bg' readonly/> % </td>
                  <td align='center'><b>Rp.</b></td>
                  <td align='center'><input type='text' id='indicated_land_value_final' name='indicated_land_value_final' value='".($act=='add'?'':number_format($curr_data1['indicated_land_value_amount'],2,'.',','))."'  style='text-align:right;font-weight:bold' class='readonly-bg' readonly/></td>
                  <td colspan='".$colspan3."'></td></tr>
                  <tr><td colspan='3'><b>Rounded I</b></td>
                  <td align='center'><b>Rp.</b></td>
                  <td align='center'><input type='text' id='rounded1_final' name='rounded1_final' value='".($act=='add'?'':number_format($curr_data1['first_rounded']))."' style='text-align:right;font-weight:bold' class='readonly-bg' readonly/></td>
                  <td colspan='".$colspan3."'></td></tr>
                  <tr><td colspan='3'><b>Total Land Value</b></td>
                  <td align='center'><b>Rp.</b></td>
                  <td align='center'><input type='text' id='total_land_value_final' name='total_land_value_final' value='".($act=='add'?'':number_format($curr_data1['total_land_value']))."' style='text-align:right;font-weight:bold' class='readonly-bg' readonly/></td>
                  <td colspan='".$colspan3."'></td></tr>
                  <tr><td colspan='3'><b>Rounded II</b></td>
                  <td align='center'><b>Rp.</b></td>
                  <td align='center'><input type='text' id='rounded2_final' name='rounded2_final' value='".($act=='add'?'':number_format($curr_data1['final_rounded']))."' style='text-align:right;font-weight:bold' class='readonly-bg' readonly/></td>
                  <td colspan='".$colspan3."'></td></tr>
                  <tr><td colspan='3'><b>Liquidation Weight</b></td>
                  <td align='center'><b>%</b></td>
                  <td align='center'><input type='text' id='liquidation_weight' name='liquidation_weight' value='".$curr_data1['liquidation_weight']."' style='text-align:right;' onkeypress=\"return only_number(event,this);\" onkeyup=\"mix_function5();\" required/></td>
                  <td colspan='".$colspan3."'></td></tr>
                  <tr><td colspan='3'><b>Liquidation Value</b></td>
                  <td align='center'><b>Rp.</b></td>
                  <td align='center'><input type='text' id='liquidation_value' name='liquidation_value' value='".($act=='add'?'':number_format($curr_data1['liquidation_value']))."' style='text-align:right;font-weight:bold' class='readonly-bg' readonly required/></td>
                  <td colspan='".$colspan3."'></td></tr>";
                  //Eof Indicative Value Table

            echo "</tbody>
            </table>
          </div>
        </div>

        <br />

        <div class='ln_solid'></div>
        <div class='form-group'>
            <div class='col-md-12 col-sm-12 col-xs-12' align='center'>
                <button type='button' class='btn btn-danger' id='close-modal-form' data-dismiss='modal'>Batal</button>
                <button type='submit' class='btn btn-success'>Simpan</button>
            </div>
        </div>
      </form>";
    }
    else
    {
      echo "
        <div class='alert alert-warning' role='alert'>
          <button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
          Silahkan lengkapi data Perhitungan Bangunan terlebih dahulu!
        </div>";
    }
?>