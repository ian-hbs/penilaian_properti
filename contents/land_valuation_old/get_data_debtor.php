<?php
	include_once "../../config/superglobal_var.php";
	include_once "../../config/db_connection.php";		

	$id_penugasan = $_POST['id_penugasan'];
	$sql = "SELECT a.id_penugasan,a.no_penugasan,b.alamat,b.kelurahan,b.kecamatan,b.kota,b.provinsi,c.nama,d.luas_tanah 
			FROM penugasan as a, properti as b, debitur as c, objek_tanah as d WHERE (a.id_penugasan=b.fk_penugasan AND a.id_penugasan=c.fk_penugasan AND a.id_penugasan=d.fk_penugasan)
			AND (a.id_penugasan='".$id_penugasan."')";

	$error = 'terjadi kesalahan saat mengambil data dari serverss!';

	$result = $db->Execute($sql);
	if(!$result)
		die('ERROR : '.$error);

	$main_row = $result->FetchRow();

	//open table
	$adjustment_table = "<table border=1 cellspacing=0>";
	//Eof open table

	//thead table
	$adjustment_table .= "<thead><tr><td class='tableHead' width='20%' colspan='2'>Description</td><td class='tableHead'>Property Appraised</td>";

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

	$arr_no_urut = array();
	foreach($arr_comparative as $row)
	{
		$arr_no_urut[$row['no_urut']]=$row['no_urut'];
	}

	foreach($arr_no_urut as $key=>$val)	
		$adjustment_table .= "<td class='tableHead' colspan='2'>Data ".$val."</td>";	

	$adjustment_table .="</tr></thead>";
	//Eof thead table

	$property_location = $main_row['alamat'].', Kelurahan '.$main_row['kelurahan'].', Kecamatan '.$main_row['kecamatan'].', Kota '.$main_row['kota'].', '.$main_row['provinsi'];
	
	$adjustment_table .= "<tbody>";

	$colspan = (count($arr_comparative)*2)+3;

	//Data Comparison fill box		
	$adjustment_table  .= "<tr><td colspan='".$colspan."' class='tableHead'>Data Comparison</td></tr>";

	$arr_field = array('alamat'=>'Address','pemberi_data'=>'Contact Person','status'=>'Title of Person','no_tlp'=>'Phone',
					   'jenis_objek'=>'Type of Property Data','jarak_dari_properti'=>'Distance of Property');
	$i = 0;
	foreach($arr_field as $key => $val)
	{
		if($i==0)
		{
			$adjustment_table .= "<tr><td colspan='2'>".$val."</td><td rowspan='".count($arr_field)."'>".$property_location."</td>";
			foreach($arr_comparative as $row)
				$adjustment_table .= "<td colspan='2'>Data ".$row[$key]."</td>";
		}
		else
		{
			$adjustment_table .=  "<tr><td colspan='2'>".$val."</td>";
			foreach($arr_comparative as $row)
				$adjustment_table .= "<td colspan='2'>".$row[$key]."</td>";
		}
		$adjustment_table .= "</tr>";
		$i++;
	}
	//Eof Data Comparison fill box
	

	//Market Value fill box (temporary)	
	$adjustment_table  .= "<tr><td colspan='".$colspan."' class='tableHead'>Support Data (<font color='red'>temporar</font>)</td></tr>";
	$adjustment_table .= "<tr><td colspan='2'>Market Value</td><td></td>";
	foreach($arr_comparative as $row)
	{
		$nu = $row['no_urut'];
		$id = 'market_value'.$nu;
		$arr_param = array('market_value'.$nu,'building_area'.$nu,'indicated_property_value'.$nu);
		$adjustment_table .= "<td align='center' colspan='2'><input type='text' name='".$id."' id='".$id."'/></td>";
	}
	$adjustment_table .= "</tr>";
	//Eof Market Value fill box (temporary)	

	//Existing Data fill box
	$adjustment_table  .= "<tr><td colspan='".$colspan."' class='tableHead'>Existing Data</td></tr>";

	unset($arr_field);
	unset($arr_have_function);	

	$arr_have_function = array('land_area','building_area','offering_price','transaction_price','discount');
	
	$arr_field_value_from_db = array('land_area','building_area','year_built','crn_of_building_per_sqm');

	$arr_read_only_field = array('total_price','indicated_property_value','indicated_building_market_value_sqm','indicated_building_market_value',
								 'indicated_land_value','indicated_property_value_land','indicated_land_value_sqm');

	$arr_read_only_field2 = array_merge($arr_read_only_field,array('land_area'));

	$arr_thousand_format_field = array('land_area','building_area','crn_of_building_per_sqm','economic_life','frontage','wide_road_access','elevation',
									  'offering_price','transaction_price');

	$arr_only_number_field = array_merge(array('year_built'),$arr_thousand_format_field);



	$arr_field = array('land_area'=>'Land Area','land_title'=>'Land Title','building_area'=>'Building Area','year_built'=>'Year Built','condition'=>'Condition',
						'crn_of_building_per_sqm'=>'CRN of Building per sqm','construction'=>'Construction','economic_life'=>'Economic Life of Building','frontage'=>'Frontage (m)','wide_road_access'=>'Wide Road Access (m)',
						'elevation'=>'Elevation (cm)','land_shape'=>'Land Shape','location'=>'Location','position'=>'Position','offering_price'=>'Offering Price',
						'transaction_price'=>'Transaction Price','time'=>'Time','discount'=>'Discount','total_price'=>'Total Price','indicated_property_value'=>'Indicated Property Value',
						'indicated_building_market_value_sqm'=>'Indicated Building Market Value/sqm','indicated_building_market_value'=>'Indicated Building Market Value',
						'indicated_land_value'=>'Indicated Land Value','indicated_property_value_land'=>'Indicated Property Value/Land','indicated_land_value_sqm'=>'Indicated Land Value/sqm');

	foreach($arr_field as $key => $val)
	{		
		$input_value = "";
		$onkeypress1 = "";
		$onkeypress2 = "";
		$onkeyup1 = "";
		$onkeyup2 = "";
		$readonly1 = "";
		$readonly2 = "";
		$textalign = "left";

		if(in_array($key,$arr_field_value_from_db))
		{
			$input_value = get_field_value_from_db($key);			
		}

		if(in_array($key, $arr_read_only_field))
		{
			$readonly1 = "class='autofill-bg' readonly";
			$textalign = "right";
		}
		if(in_array($key, $arr_read_only_field2))
		{
			$readonly2 = "class='autofill-bg' readonly";
		}
		if(in_array($key, $arr_only_number_field))
		{
			$onkeypress1 = "onkeypress=\"return only_number(event,this)\"";
			$onkeypress2 = "onkeypress=\"return only_number(event,this)\"";			
		}
		if(in_array($key, $arr_thousand_format_field))
		{
			$onkeyup1 = "onkeyup=\"thousand_format(this)\"";
			$onkeyup2 = "onkeyup=\"thousand_format(this)";			
		}

		$adjustment_table .= "<tr><td colspan='2'>".$val."</td><td align='center'><input type='text' name='".$key."0' id='".$key."0' value='".$input_value."' 
							  style='text-align:".$textalign."' ".$onkeypress1." ".$onkeyup1." ".$readonly2."/></td>";
		$onkeypress2 .= "\"";

		foreach($arr_no_urut as $key2=>$val2)
		{
			$nu = $val2;
			$id = $key.$nu;
			$onkeyup = $onkeyup2;
			$arr_param = array();

			if(in_array($key,$arr_have_function))
			{
				$arr_param = array('offering_price'.$nu,'discount'.$nu,'total_price'.$nu,'transaction_price'.$nu,'indicated_property_value'.$nu,
									   'building_area'.$nu,'market_value'.$nu,'indicated_building_market_value_sqm'.$nu,
									   'indicated_building_market_value'.$nu,'indicated_land_value'.$nu,'land_area'.$nu,'indicated_property_value_land'.$nu,
									   'indicated_land_value_sqm'.$nu);
				$onkeyup .= ($onkeyup2==''?"onkeyup=\"":";").generate_js_function('get_existing_data_value',$arr_param,'');
								
			}
			$onkeyup .= ($onkeyup==''?"":"\"");
			$adjustment_table .= "<td align='center' colspan='2'><input type='text' name='".$id."' id='".$id."' style='text-align:".$textalign."' 
								  ".$onkeyup." ".$onkeypress2." ".$readonly1."/></td>";
		}

		$adjustment_table .="</tr>";
	}
	//Eof Existing Data fill box	

	//Adjustment fill box
	$adjustment_table .= "<tr><td colspan='".$colspan."' class='tableHead'>Adjustment</td></tr>";
	$adjustment_table .= "<tr><td class='tableHead'>No.</td><td class='tableHead'>Adjustment Factor</td><td></td>";

	for($i=0;$i<count($arr_no_urut);$i++)
		$adjustment_table .= "<td class='tableHead'>%</td><td class='tableHead'>Amount</td>";
	
	$adjustment_table .= "</tr>";

	unset($arr_field);

	$arr_field = array('time'=>'Time','land_title'=>'Land Title','land_area'=>'Land Area','land_use'=>'Land Use',
					   'land_shape'=>'Land Shape','position'=>'Position','frontage'=>'Frontage','location'=>'Location',
					   'wide_road'=>'Wide Road','elevasi'=>'Elevasi');

	$no=0;
	foreach($arr_field as $key => $val)
	{
		$no++;
		$adjustment_table .= "<tr><td align='center'>".$no."</td><td>".$val."</td><td></td>";
		foreach($arr_no_urut as $key2=>$val2)
		{
			$nu = $val2;
			$id1 = $key.'_percent'.$nu;
			$id2 = $key.'_amount'.$nu;
			$id3 = 'weigthed_percent'.$nu;
			$id4 = 'weigthed_amount'.$nu;

			$arr_param = array($id1,'indicated_land_value_sqm'.$nu,$id2,'total_adjusted_percent'.$nu,'total_adjusted_amount'.$nu,'indicated_land_value_amount'.$nu);
			$support_value1 = get_support_value($arr_field,$key,'','_percent'.$nu);
			$support_value2 = get_support_value($arr_field,$key,'','_amount'.$nu);
			$arr_support = array($support_value1,$support_value2);
			$arr_param = array_merge($arr_param,$arr_support);

			$arr_param2 = array($id3,'indicated_land_value_amount'.$nu,'land_area0',$id4,'weigthed_percent_final','indicated_land_value_final','pembulatan_final','total_land_value_final','rounded_to_final');
			$arr_param = array_merge($arr_param,$arr_param2);
			$support_value1 = get_support_value($arr_no_urut,$nu,'weigthed_percent','');
			$support_value2 = get_support_value($arr_no_urut,$nu,'weigthed_amount','');
			$arr_support = array($support_value1,$support_value2);
			$arr_param = array_merge($arr_param,$arr_support);

			$onkeyup = "onkeyup=\"".generate_js_function('adjustment_value',$arr_param)."\"";
			$adjustment_table .= "<td align='center'><input size=5 type='text' id='".$id1."' ".$onkeyup." style='text-align:right' onkeypress=\"return only_number(event,this);\"/></td>
								  <td align='center'><input type='text' name='".$id2."' id='".$id2."' class='autofill-bg' style='text-align:right' readonly/></td>";
		}
		$adjustment_table .="</tr>";
	}
	$adjustment_table .= "<tr><td></td><td><b>Total Adjusted</b></td><td></td>";
	
	foreach($arr_no_urut as $key=>$val)
	{
		$nu = $val;
		$id1 = 'total_adjusted_percent'.$nu;
		$id2 = 'total_adjusted_amount'.$nu;
		$adjustment_table .= "<td align='center'><input size=5 type='text' name='".$id1."' id='".$id1."' style='text-align:right;font-weight:bold' class='autofill-bg' readonly/></td>
							  <td align='center'><input type='text' name='".$id2."' id='".$id2."' style='text-align:right;font-weight:bold' class='autofill-bg' readonly/></td>";
	}

	$adjustment_table .= "<tr><td></td><td><b>Indicated Land Value</b></td><td></td>";
	foreach($arr_no_urut as $key=>$val)
	{
		$nu = $val;
		$id = 'indicated_land_value_amount'.$nu;
		$adjustment_table .= "<td></td>
							  <td align='center'><input type='text' name='".$id."' id='".$id."' style='text-align:right;font-weight:bold' class='autofill-bg' readonly/></td>";
	}
	//Eof Adjustment fill box

	//Indicative Value Table
	$colspan2 = 5;
	$colspan3 = $colspan-$colspan2;
	$adjustment_table .= "<tr><td colspan='".$colspan2."' class='tableHead'>Indicative Value</td><td colspan='".$colspan3."'></td></tr>";

	foreach($arr_no_urut as $key => $val)
	{		
		$id1 = 'weigthed_percent'.$key;
		$id2 = 'weigthed_amount'.$key;

		$arr_param = array($id1,'indicated_land_value_amount'.$key,'land_area0',$id2,'weigthed_percent_final','indicated_land_value_final','pembulatan_final','total_land_value_final','rounded_to_final');
		$support_value1 = get_support_value($arr_no_urut,$key,'weigthed_percent','');
		$support_value2 = get_support_value($arr_no_urut,$key,'weigthed_amount','');
		$arr_support = array($support_value1,$support_value2);
		$arr_param = array_merge($arr_param,$arr_support);

		$onkeyup = "onkeyup=\"".generate_js_function('indicative_value',$arr_param)."\"";
		$adjustment_table .= "<tr><td colspan='2'>DATA ".$nu."</td>
							  <td align='center'><input type='text' id='".$id1."' name='".$id1."' style='text-align:right' onkeypress=\"return only_number(event,this);\" ".$onkeyup."/> % </td>
							  <td align='center'>Rp.</td><td align='center'><input type='text' id='".$id2."' name='".$id2."' style='text-align:right' class='autofill-bg' readonly/></td>
							  <td colspan='".$colspan3."'></td></tr>";
	}

	$adjustment_table .= "<tr><td colspan='2'><b>Indicated Land Value</b></td>
						  <td align='center'><input type='text' id='weigthed_percent_final' name='weigthed_percent_final' style='text-align:right;font-weight:bold' class='autofill-bg' readonly/> % </td>
						  <td align='center'><b>Rp.</b></td>
						  <td align='center'><input type='text' id='indicated_land_value_final' name='indicated_land_value_final' style='text-align:right;font-weight:bold' class='autofill-bg' readonly/></td>
						  <td colspan='".$colspan3."'></td></tr>";
	$adjustment_table .= "<tr><td colspan='3'><b>Pembulatan</b></td>
						  <td align='center'><b>Rp.</b></td>
						  <td align='center'><input type='text' id='pembulatan_final' name='pembulatan_final' style='text-align:right;font-weight:bold' class='autofill-bg' readonly/></td>
						  <td colspan='".$colspan3."'></td></tr>";
	$adjustment_table .= "<tr><td colspan='3'><b>Total Land Value</b></td>
						  <td align='center'><b>Rp.</b></td>
						  <td align='center'><input type='text' id='total_land_value_final' name='total_land_value_final' style='text-align:right;font-weight:bold' class='autofill-bg' readonly/></td>
						  <td colspan='".$colspan3."'></td></tr>";
	$adjustment_table .= "<tr><td colspan='3'><b>Rounded To</b></td>
						  <td align='center'><b>Rp.</b></td>
						  <td align='center'><input type='text' id='rounded_to_final' name='rounded_to_final' style='text-align:right;font-weight:bold' class='autofill-bg' readonly/></td>
						  <td colspan='".$colspan3."'></td></tr>";
	//Eof Indicative Value Table


	$adjustment_table .="</tbody>";

	echo $adjustment_table;


	function generate_js_function($field,$arr_param,$prefix='get_')
	{
		$function = "";

		$function = $prefix.$field."(";
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
	function get_support_value($arr_field,$existing_value,$prefix='',$sufix='')
	{
		$result = "[";
		$s=false;
		foreach($arr_field as $key=>$val)
		{
			if($key!=$existing_value)
			{
				$result .= ($s?",'".$prefix.$key.$sufix."'":"'".$prefix.$key.$sufix."'");
				$s = true;
			}
		}
		$result .= "]";
		return $result;
	}

	function get_field_value_from_db($key)
	{
		global $main_row;
		$result = '';
		switch($key)
		{
			case 'land_area':$result = $main_row['luas_tanah'];break;
			default:$result = '';
		}
		return $result;
	}
?>