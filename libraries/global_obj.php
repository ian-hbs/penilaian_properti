<?php
	class global_obj
	{
		protected $_db;
		public $_phpFileUploadErrors;

		public function __construct($db=null)
		{
			$this->_db=$db;
			$this->_phpFileUploadErrors = array(
							    0 => 'There is no error, the file uploaded with success',
							    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
							    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
							    3 => 'The uploaded file was only partially uploaded',
							    4 => 'No file was uploaded',
							    6 => 'Missing a temporary folder',
							    7 => 'Failed to write file to disk.',
							    8 => 'A PHP extension stopped the file upload.',
							);
		}

		function identify_regency($regency)
		{
			$x = explode(' ',$regency);			
			$name = '';
			for($i=1;$i<count($x);$i++)
				$name .= " ".$x[$i];
			
			return array($x[0],trim($name));
		}
		
		function real_escape_string($input)
		{
			$result = preg_replace("/'/i","\'",$input);			
			return $result;
		}

		function compress_image($source_url, $destination_url, $quality) 
		{
			$info = getimagesize($source_url);

	    	if ($info['mime'] == 'image/jpeg')
	        	$image = imagecreatefromjpeg($source_url);
	    	elseif ($info['mime'] == 'image/gif')
	        	$image = imagecreatefromgif($source_url);
	   		elseif ($info['mime'] == 'image/png')
	        	$image = imagecreatefrompng($source_url);

	    	imagejpeg($image, $destination_url, $quality);
			
			return $destination_url;
		}

		function get_new_valuation_number()
		{
			$new_number = date('ymd');

			$sql = "SELECT max(no_penilaian) as max FROM penugasan WHERE no_penilaian LIKE '".$new_number."%'";
			$result = $this->_db->Execute($sql);
			if(!$result)
			{
				echo($this->_db->ErrorMsg());
			}
			$last = 1;
			if($result->RowCount()>0)
			{
				$row = $result->FetchRow();
				$last = (int) substr($row['max'],6,3) + 1;				
			}
			$new_number .= sprintf("%03d",$last);

			return $new_number;
		}

		function get_new_bct_number()
		{
			$new_number = date('ymd');

			$sql = "SELECT max(no_bct) as max FROM perhitunganbkb_master WHERE no_bct LIKE '".$new_number."%'";
			$result = $this->_db->Execute($sql);
			if(!$result)
			{
				echo($this->_db->ErrorMsg());
			}
			
			$last = 1;

			if($result->RowCount()>0)
			{
				$row = $result->FetchRow();
				$last = (int) substr($row['max'],6,3) + 1;
			}
			$new_number .= sprintf("%03d",$last);

			return $new_number;
		}


	    function get_location_map_data($id_penugasan,$type)
	    {	        
	        $sql = "SELECT * FROM peta_lokasi WHERE fk_penugasan='".$id_penugasan."' and jenis='".$type."'";
	        $result = $this->_db->Execute($sql);

	        if (!$result)
	            echo $this->_db->ErrorMsg();

	        $n_map = $result->RecordCount();
	        
	        $map_row = array();

	        if($n_map>0)
	            $map_row = $result->FetchRow();

	        return array($n_map,$map_row);
	    }

		function get_property_detail($id)
		{
			$sql = "SELECT a.tgl_survei,b.nama as nm_penilai,c.nama as nm_debitur,d.alamat,d.kelurahan,d.kecamatan,d.kota,d.provinsi FROM penugasan as a,
					ref_penilai as b, debitur as c, properti as d WHERE (a.penilai1=b.id_penilai AND a.id_penugasan=c.fk_penugasan AND a.id_penugasan=d.fk_penugasan) 
					AND (a.id_penugasan='".$id."')";

			$result = $this->_db->Execute($sql);
			if(!$result)
				echo($this->_db->ErrorMsg());
			$row = $result->FetchRow();
			
			return $row;	
		}

		function print_property_detail($row,$lbl_width=15)
		{
			echo "<table class='table table-bordered'>
				<tbody>
					<tr><td width='".$lbl_width."%'>Nama Penilai</td><td>".$row['nm_penilai']."</td></tr>
					<tr><td>Tanggal Survei</td><td>".$row['tgl_survei']."</td></tr>
					<tr><td>Calon Debitur</td><td>".$row['nm_debitur']."</td></tr>
					<tr><td>Lokasi Properti</td><td>".$row['alamat'].", Kelurahan ".ucwords(strtolower($row['kelurahan'])).", Kecamatan ".ucwords(strtolower($row['kecamatan'])).", ".ucwords(strtolower($row['kota'])).", Provinsi ".ucwords(strtolower($row['provinsi']))."</td></tr>
				</tbody>
			</table>";
		}

		function get_province_name($id)
		{
			$sql = "SELECT name FROM ref_provinces WHERE(id='".$id."')";
			$name = $this->_db->getOne($sql);
			return $name;
		}

		function get_province_id($name)
		{
			$sql = "SELECT id FROM ref_provinces WHERE(LOWER(name)='".strtolower($name)."')";
			$id = $this->_db->getOne($sql);
			return $id;
		}

		function get_regency_name($id)
		{
			$sql = "SELECT name FROM ref_regencies WHERE(id='".$id."')";
			$name = $this->_db->getOne($sql);
			return $name;
		}

		function get_regency_id($name,$prov_id)
		{
			$sql = "SELECT id FROM ref_regencies WHERE(LOWER(name)='".strtolower($name)."') AND (province_id='".$prov_id."')";
			$id = $this->_db->getOne($sql);
			return $id;
		}		

		function get_district_name($id)
		{
			$sql = "SELECT name FROM ref_districts WHERE(id='".$id."')";
			$name = $this->_db->getOne($sql);			
			return $name;
		}

		function get_district_id($name,$reg_id)
		{
			$sql = "SELECT id FROM ref_districts WHERE(LOWER(name)='".strtolower($name)."') AND (regency_id='".$reg_id."')";
			$id = $this->_db->getOne($sql);
			return $id;
		}

		function get_village_name($id)
		{
			$sql = "SELECT name FROM ref_villages WHERE(id='".$id."')";
			$name = $this->_db->getOne($sql);			
			return $name;
		}
		
		function get_village_id($name,$dis_id)
		{
			$sql = "SELECT id FROM ref_villages WHERE(LOWER(name)='".strtolower($name)."') AND (district_id='".$dis_id."')";
			$id = $this->_db->getOne($sql);
			return $id;
		}

		function get_system_params()
		{
			$sql = "SELECT name,value FROM system_params";
			$result = $this->_db->Execute($sql);
			if(!$result)
			{
				echo($this->_db->ErrorMsg());
			}
			$system_params = array();
			while($row = $result->FetchRow())
			{
				$system_params[$row['name']]=$row['value'];
			}
			return $system_params;
		}

		function dropseparate_specialChar($str)
		{
			$result = array($str,'','');
			if(strlen($str)>1)
			{
				$arr_specialChar = array('`','~','!','@','#',
										 '$','%','^','&','*',
										 '(',')','_','-','+',
										 '=',',','.','<','>',
										 '/','?',';','\'',':',
										 '"','[',']','\\','|');
				$firstChar = substr($str,0,1);
				$lastChar = substr($str,-1,1);

				$x = array_search($firstChar,$arr_specialChar,true);
				$y = array_search($lastChar,$arr_specialChar,true);
							
				$str1 = ($x?substr($str,1,strlen($str)-1):$str);
				$str2 = ($y?substr($str1,0,strlen($str1)-1):$str);
				$result [0] = $str2;
				$result [1] = ($x?$arr_specialChar[$x]:'');
				$result [2] = ($y?$arr_specialChar[$y]:'');
			}			

			return $result;
		}

		function get_formatted_message($param)
		{
			//$param[0] //content of message
			//$param[1] //wr name
			//$param[2] //month bills
			//$param[3] //retribution
			//$param[4] //bill_status

			$content = $param[0];
			
			$ct_parsed = explode(' ',$content);
			
			$ct_formatted = "";
			$s = false;
			$str = "";
			$str_ = "";
			for($i=0;$i<count($ct_parsed);$i++)
			{
				$str = trim($ct_parsed[$i]);

				if($str=='') continue;

				$str_ = $this->dropseparate_specialChar($str);

				switch($str_[0])
				{
					case '{date}':$str=date('d-m-Y');break;
					case '{wr_name}':$str=$param[1];break;
					case '{month_bills}':$str=$param[2];break;
					case '{retribution}':$str=$param[3];break;
					case '{bill_status}':$str=$param[4];break;
					default : $str=$str_[0];
				}
				$formatted = ($s?' ':'').$str_[1].$str.$str_[2];
				
				$ct_formatted .= $formatted;
				$s = true;
			}
			return $ct_formatted;
		}

		function insert_logs($activity,$ip)
		{
			if(isset($_SESSION['user_id']))
			{
				$sql="INSERT INTO logs (user_fk,activity,activity_time,ip_address) 
					  VALUES ('".$_SESSION['user_id']."','".$activity."',NOW(),'".$ip."')";

				$result=$this->_db->Execute($sql);
				if (!$result)
				{
					echo($this->_db->ErrorMsg());
				}
			}
		}

		function get_incrementID($table,$pk)
		{
			$sql = "SELECT ".$pk." FROM ".$table." ORDER BY ".$pk." DESC";
			$result = $this->_db->Execute($sql);
			if(!$result)
			{
				echo($this->_db->ErrorMsg());
			}
			$new = 1;
			if($result->RecordCount()>0)	
			{
				$row = $result->FetchRow();
				$new = (int) $row[$pk] + 1;				
			}
			return $new;
		}
		
		function get_registerID($type_id)
		{
			$sql = "SELECT MAX(register_id) as last_regid FROM users WHERE type_fk='".$type_id."'";
			$result = $this->_db->Execute($sql);
			if(!$result)
				echo($this->_db->ErrorMsg());

			$order_num = 1;
			if($result->RecordCount()>0)
			{
				$row = $result->FetchRow();
				$order_num = (int) substr($row['last_regid'],11,4) + 1;
			}

			$sql = "SELECT name FROM user_types WHERE type_id='".$type_id."'";
			$result = $this->_db->Execute($sql);
			if(!$result)
				echo($this->_db->ErrorMsg());
			$row = $result->FetchRow();

			$regid = strtoupper(substr($row['name'],0,3)).date('Ymd').sprintf("%04s", $order_num);
			return $regid;
		}


		function get_full_value($val_type,$act,$id_perhitungan_bangunan,$fk_penugasan,$type,$_value)
	  	{
	  		$field = ($val_type=='1'?'total_floor_area':'market_value');
	  		  		
			$sql = "SELECT SUM(".$field.") as tot_value FROM perhitungan_bangunan WHERE(fk_penugasan='".$fk_penugasan."') AND (type='".$type."')";
			if($act=='edit')
				$sql .= " AND (id_perhitungan_bangunan<>'".$id_perhitungan_bangunan."')";  	  	
	  		
	  		$full_value = $_value;

	  		$result = $this->_db->Execute($sql);

	  		if(!$result)
	  			return false;	  		

	  		if($result->RecordCount()>0)
	  		{
		  		if($result->RecordCount()>0)
		  		{
		  			$row = $result->FetchRow();  			
		  			$full_value += $row['tot_value'];
		  		}
			}		  	

	  		return $full_value;
	  	}  		  	

	  	function get_active_conclusion($fk_penugasan)
	  	{
	  		$sql = "SELECT (SELECT no_urut FROM log_kesimpulan_rekomendasi as x WHERE(x.fk_kesimpulan_rekomendasi=a.id_kesimpulan_rekomendasi) AND (x.status='Y')) as active,
	  				(SELECT COUNT(1) FROM log_kesimpulan_rekomendasi as x WHERE(x.fk_kesimpulan_rekomendasi=a.id_kesimpulan_rekomendasi)) as n_data,
	  				(SELECT user_input FROM log_kesimpulan_rekomendasi as x WHERE(x.fk_kesimpulan_rekomendasi=a.id_kesimpulan_rekomendasi) AND (x.status='Y')) as user_input
	  				FROM kesimpulan_rekomendasi as a WHERE(a.fk_penugasan='".$fk_penugasan."')";

	  		$result = $this->_db->Execute($sql);
	  		if(!$result)
	  		{
	  			return false;
	  		}

	  		$active = '';
	  		$n_data = 0;
	  		$status = false;

	  		if($result->RecordCount()>0)
	  		{
	  			$row = $result->FetchRow();
	  			$active = $row['active'];
	  			$n_data = $row['n_data'];
	  			$user_input = $row['user_input'];
	  			$status = ($user_input!='-'?true:false);
	  		}

	  		return array(0=>$active,1=>$n_data,2=>$status);
	  	}

	  	function get_order_num_conclusion($id_kesimpulan_rekomendasi)
	  	{

	  		$sql = "SELECT no_urut FROM log_kesimpulan_rekomendasi WHERE(fk_kesimpulan_rekomendasi='".$id_kesimpulan_rekomendasi."') ORDER BY no_urut DESC";
	  		$result = $this->_db->Execute($sql);
	  		if(!$result)
			{				
				return false;
			}
			$new_conclusion = 1;
			if($result->RecordCount()>0)
			{
				$row = $result->FetchRow();				
				$new_conclusion = $row['no_urut']+1;
			}			
			return $new_conclusion;
	  	}

	  	

	  	function reconcile_comparison_land_valuation($fk_penugasan,$fk_objek_pembanding)
	  	{
	  		include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'DML.php';
	  		
			$sql = "SELECT a.id_perhitungan_tanah_pembanding,a.indicated_property_value,a.indicated_land_value,
					a.land_area,
					(SELECT x.total_floor_area FROM perhitungan_bangunan_pembanding x WHERE(a.fk_penugasan=x.fk_penugasan) AND (x.fk_objek_pembanding=a.fk_objek_pembanding)) as building_area,
					(SELECT x.market_value FROM perhitungan_bangunan_pembanding x WHERE(a.fk_penugasan=x.fk_penugasan) AND (x.fk_objek_pembanding=a.fk_objek_pembanding)) as building_market_value,
					a.weighted_percent,b.* 
					FROM perhitungan_tanah_pembanding as a
					LEFT JOIN (SELECT fk_perhitungan_tanah_pembanding,
							  time as time_adj, land_title as land_title_adj, land_area as land_area_adj, 
							  land_use as land_use_adj, land_shape as land_shape_adj, position as position_adj,
							  frontage as frontage_adj, location as location_adj, wide_road as wide_road_adj, 
							  elevasi as elevasi_adj, development_environment as development_environment_adj, economic_factor as economic_factor_adj,
							  security_facility as security_facility_adj FROM adjustment_tanah_pembanding) as b
					ON (a.id_perhitungan_tanah_pembanding=b.fk_perhitungan_tanah_pembanding) 
					WHERE(a.fk_penugasan='".$fk_penugasan."') AND (a.fk_objek_pembanding='".$fk_objek_pembanding."')";

				$DML1 = new DML('perhitungan_tanah_pembanding',$this->_db);
				$DML2 = new DML('adjustment_tanah_pembanding',$this->_db);

			$result = $this->_db->Execute($sql);

			if(!$result)
			{				
				return false;			
			}			
			
			if($result->RecordCount()>0)
			{
				$row = $result->FetchRow();
				
				$ipv = $row['indicated_property_value'];
				$ilv = $row['indicated_land_value'];
				$land_area = $row['land_area'];

				$building_area = (is_null($row['building_area'])?0:$row['building_area']);
				$building_market_value = (is_null($row['building_market_value'])?0:$row['building_market_value']);

				$ibmv = str_replace(',','',$building_market_value);
				$ibmv_sqm = ($building_area>0?$ibmv/$building_area:0);
								
				$ilv = $ipv - $ibmv;				
				$ilv_sqm = ($land_area>0?$ilv/$land_area:0);					
				$ipv_land = ($land_area>0?$ipv/$land_area:0);

				$arr_data1 = array('indicated_property_value'=>$ipv,'indicated_property_value_land'=>$ipv_land,
							   'indicated_building_market_value'=>$ibmv,'indicated_building_market_value_sqm'=>$ibmv_sqm);
				
				$tot_adjusted_amount = 0;
				$arr_data2 = array();

				$percentages = array('time','land_title','land_area',
								   'land_use','land_shape','position',
								   'frontage'=>'location','wide_road',
								   'elevasi','development_environment','economic_factor',
								   'security_facility');

				foreach($percentages as $val)
				{
					$amount = ($row[$val.'_adj']*$ilv_sqm)/100;
					$arr_data2[$val.'_amount'] = $amount;
					$tot_adjusted_amount += $amount;
				}
				
				$adj_ilv = $ilv_sqm + $tot_adjusted_amount;					
				$weighted_amount = ($row['weighted_percent']*$adj_ilv)/100;					

				$arr_data1['indicated_land_value'] = $ilv;
				$arr_data1['indicated_land_value_sqm'] = $ilv_sqm;
				$arr_data1['weighted_amount'] = $weighted_amount;

				$arr_data2['total_adjusted_amount'] = $tot_adjusted_amount;
				$arr_data2['indicated_land_value'] = $adj_ilv;				

				//update table adjustment_tanah_pembanding
				$cond = "fk_perhitungan_tanah_pembanding='".$row['id_perhitungan_tanah_pembanding']."'";
				$result = $DML2->update($arr_data2,$cond);
				if(!$result)
					return false;				
				// ===== //
				
				//update table perhitungan_tanah_pembanding
				$cond = "fk_penugasan='".$fk_penugasan."' AND fk_objek_pembanding='".$fk_objek_pembanding."'";
				$result = $DML1->update($arr_data1,$cond);
				if(!$result)
				{						
					return false;
				}
				// ====== //
			}
			return true;
	  	}

	  	function reconcile_main_land_valuation($fk_penugasan)
	  	{
	  		include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'DML.php';

	  		$DML = new DML('perhitungan_tanah',$this->_db);

	  		$sql = "SELECT a.land_area,a.liquidation_weight,
					(SELECT SUM(weighted_amount) FROM perhitungan_tanah_pembanding as x WHERE(x.fk_penugasan=a.fk_penugasan)) as indicated_land_value_amount,
					(SELECT SUM(total_floor_area) FROM perhitungan_bangunan as x WHERE(x.fk_penugasan=a.fk_penugasan) AND (type='building')) as building_area,
					(SELECT SUM(market_value) FROM perhitungan_bangunan as x WHERE(x.fk_penugasan=a.fk_penugasan) AND (type='building')) as building_market_value,
					(SELECT SUM(market_value) FROM perhitungan_bangunan as x WHERE(x.fk_penugasan=a.fk_penugasan) AND (type='site improvement')) as site_improvement_market_value
					FROM perhitungan_tanah as a WHERE(fk_penugasan='".$fk_penugasan."')";
			
			$result = $this->_db->Execute($sql);
			if(!$result)
			{				
				return false;
			}

			if($result->RecordCount()>0)
			{
				$row = $result->FetchRow();

				$land_area = $row['land_area'];
				$building_area = $row['building_area'];
				$liquidation_weight = $row['liquidation_weight'];
				$building_market_value = $row['building_market_value'];			
				$site_improvement_market_value = $row['site_improvement_market_value'];

				$indicated_land_value_amount = $row['indicated_land_value_amount'];
				$first_rounded = round($indicated_land_value_amount,-4);
				$total_land_value = $first_rounded * $land_area;
				$final_rounded = round($total_land_value,-6);					
				$liquidation_value = ($liquidation_weight*$final_rounded)/100;
				$liquidation_value = round($liquidation_value,-6);					
				
				$ipv = $final_rounded + $building_market_value + $site_improvement_market_value;
				$ipv_land = ($land_area>0?$ipv/$land_area:0);
				$ilv = $final_rounded;
				$ilv_sqm = $indicated_land_value_amount;
				$ibmv_sqm = ($building_area>0?$building_market_value/$building_area:0);

				$arr_data['indicated_property_value'] = $ipv;
				$arr_data['indicated_property_value_land'] = $ipv_land;
				$arr_data['indicated_land_value'] = $ilv;
				$arr_data['indicated_land_value_sqm'] = $ilv_sqm;
				$arr_data['indicated_building_market_value'] = $building_market_value;
				$arr_data['indicated_building_market_value_sqm'] = $ibmv_sqm;

				$arr_data['indicated_land_value_amount'] = $indicated_land_value_amount;
				$arr_data['first_rounded'] = $first_rounded;
				$arr_data['total_land_value'] = $total_land_value;
				$arr_data['final_rounded'] = $final_rounded;
				$arr_data['liquidation_value'] = $liquidation_value;

				$cond = "fk_penugasan = '".$fk_penugasan."'";
				$result = $DML->update($arr_data,$cond);
				if(!$result){
					return false;
				}
			}

			return true;

	  	}

	  	function reconcile_safetymargin_value($type,$fk_penugasan)
	  	{
	  		include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'DML.php';

	  		$DML = new DML('nilai_safetymargin',$this->_db);

	  		$safetymargin_sql = " SELECT x.prosentase FROM nilai_safetymargin as x WHERE(a.id_penugasan=x.fk_penugasan)";
	  		if($type=='1')
	  		{
	  			$jenis_objek = 'tanah';
	  			$safetymargin_sql .= " AND (x.jenis_objek='tanah')";
	  			$sql = "SELECT (SELECT x.total_land_value FROM perhitungan_tanah as x WHERE(a.id_penugasan=x.fk_penugasan)) as market_value,
	  					(".$safetymargin_sql.") as safetymargin_weight
	  					FROM penugasan as a WHERE(a.id_penugasan='".$fk_penugasan."')";
	  		}
	  		else if($type=='2')
	  		{
	  			$jenis_objek = 'bangunan';
	  			$safetymargin_sql .= " AND (x.jenis_objek='bangunan')";
	  			$sql = "SELECT (SELECT SUM(x.market_value) as market_value FROM perhitungan_bangunan as x WHERE(a.id_penugasan=x.fk_penugasan) AND (type='building')) as market_value,
	  					(".$safetymargin_sql.") as safetymargin_weight FROM penugasan as a WHERE(a.id_penugasan='".$fk_penugasan."')";
	  		}
	  		else
	  		{
	  			$jenis_objek = 'sarana_pelengkap';
	  			$safetymargin_sql .= " AND (x.jenis_objek='sarana_pelengkap')";
	  			$sql = "SELECT (SELECT SUM(x.market_value) as market_value FROM perhitungan_bangunan as x WHERE(a.id_penugasan=x.fk_penugasan) AND (type='site improvement')) as market_value,
	  					(".$safetymargin_sql.") as safetymargin_weight FROM penugasan as a WHERE(a.id_penugasan='".$fk_penugasan."')";	
	  		}	  		

	  		$result = $this->_db->Execute($sql);
	  		if(!$result)
	  		{	  			
	  			return false;
	  		}
	  		
	  		$safetymargin_value = 0;
	  		if($result->RecordCount()>0)
	  		{
	  			$row = $result->FetchRow();

	  			if(!is_null($row['safetymargin_weight']))	  			
	  				$safetymargin_value = ((100-$row['safetymargin_weight']) * $row['market_value'])/100;
	  			
				$cond = "fk_penugasan='".$fk_penugasan."' AND jenis_objek='".$jenis_objek."'";
				$arr_data = array('nilai'=>$safetymargin_value);

				$result = $DML->update($arr_data,$cond);
				if(!$result)
					return false;

	  		}
	  		return true;
	  	}

		function get_need_entry($id_penugasan,$checked_arr)
		{
			$tables = array(3=>'objek_pembanding',4=>'marketabilitas',5=>'pertumbuhan_agunan',6=>'daya_tarik_agunan',
							7=>'spesifikasi_bangunan',8=>'sarana_bangunan',9=>'perijinan_bangunan',10=>'luas_bangunan',
							11=>'foto_properti',12=>'foto_properti_pembanding',13=>'peta_lokasi',14=>'perhitungan_bangunan',
							15=>'perhitungan_tanah',16=>'catatan_penilai',17=>'kesimpulan_rekomendasi');

			$need_entry_arr = array();
			foreach($tables as $key=>$val)
			{
				if(in_array($key,$checked_arr))
				{					
					$sql = "SELECT count(1) n FROM ".$val." WHERE(fk_penugasan='".$id_penugasan."')";
					$n = $this->_db->getOne($sql);
					if($n==0)
						$need_entry_arr[] = $key;
					
				}
			}
			return array((count($need_entry_arr)==0?true:false),$need_entry_arr);
		}

		function check_valuation($type)
		{

		}


	}
?>