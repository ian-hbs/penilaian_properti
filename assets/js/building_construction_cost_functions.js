
function get_unit_price_value(index)
{
	var $jkb = $('#fk_jenis_komponen_bangunan'+index), $v = $('#volume'+index),
		$ikk = $('#provinsi'), $jl = $('#jumlah_lantai');
	var nol = '0.00';
	var up = '';

	if(typeof(building_construction_values[$jkb.val()])!='undefined')
	{

		if($ikk.val()!='' && $jl.val()!='' && $v.val()!='')
		{
			var x_ikk = $ikk.val().split('_');
			var jl = ($jl.val()=='>4'?'n':$jl.val());
			up = building_construction_values[$jkb.val()][jl];
			
			up = parseFloat(x_ikk[1]) * ((parseFloat($v.val()) * parseFloat(up))/100);
		}
		else
		{			
			up = nol;
		}
	}
	else
	{		
		up = nol;		
	}
	
	return up;
}

function get_whole_unit_price()
{
	var $ni = $('#n_index'), $index = '', $hs = '', $tnk = $('#total_nilai_komponen');
		
	var total = 0;
	var up = 0;	
	
	var n_index = parseInt($ni.val());

	var decimal = '000000000'; 
	var num_decimal_places = 2;
	var thousand_separator = ',';
	var dec_separator = '.';

	for(i=1;i<=n_index;i++)
	{
		$index = $('#index'+i);
		index = $index.val();
		
		$hs = $('#harga_satuan'+index);

		up = get_unit_price_value(index);		

		total += parseFloat(up);

		number_str = up.toString();
		negatif = (number_str.indexOf('-')>-1?'-':'');

		number = Math.abs(number_str);
		number = number.toString().split(".");

		if(number.length==2)
		    numberDec=(number[1]+decimal).substring(0,num_decimal_places);
		else
		    numberDec= decimal.substring(0,num_decimal_places);
		  
		mainNumber = number[0];
		strdigit='';
		k = 0;

		for(j=(mainNumber.length-1);j>=0;j--)
		{
		    if(k % 3 == 0 && k != 0)
		      strdigit = thousand_separator + strdigit;

		    strdigit = mainNumber.charAt(j) + strdigit;
		    k++;
		}

		up_formatted = negatif + strdigit + (num_decimal_places > 0 ? dec_separator + numberDec : '');  

		$hs.val(up_formatted);
	}	

	total = number_format(total,2,'.',',');
	$tnk.val(total);
}

function get_unit_price(index)
{
	var $hs = $('#harga_satuan'+index);

	up = get_unit_price_value(index);

	up = number_format(up,2,'.',',');
	$hs.val(up);
}


function get_total_unit_price()
{
	var $ni = $('#n_index'), $index = '', $hs = '', $tnk = $('#total_nilai_komponen');
	var total = 0;	

	for(i=1;i<=$ni.val();i++)
	{
		$index = $('#index'+i);

		index = $index.val();

		$hs = $('#harga_satuan'+index);
		hs = replaceall($hs.val(),',','');
		total += parseFloat(hs);
	}
	total = number_format(total,2,'.',',');
	$tnk.val(total);
}

function get_overhead()
{
	var $tnk = $('#total_nilai_komponen'), $ohp = $('#overhead_persen'), $ohn = $('#overhead_nilai');
	var tnk = replaceall($tnk.val(),',','');
	var ohp = ($ohp.val()==''?0:$ohp.val());

	var oh = (tnk * parseFloat(ohp))/100;

	oh = number_format(oh,2,'.',',');
	$ohn.val(oh);
}

function get_contractor_fee()
{
	var $tnk = $('#total_nilai_komponen'), $fkp = $('#fee_kontraktor_persen'), $fkn = $('#fee_kontraktor_nilai');	
	var tnk = replaceall($tnk.val(),',','');
	var fk = (tnk * $fkp.val())/100;	

	fk = number_format(fk,2,'.',',');
	$fkn.val(fk);
}

function get_consultant_fee()
{
	var $tnk = $('#total_nilai_komponen'), $fkp = $('#fee_konsultan_persen'), $fkn = $('#fee_konsultan_nilai');
	var tnk = replaceall($tnk.val(),',','');
	var fkp = ($fkp.val()==''?0:$fkp.val());

	var fk = (tnk * parseFloat(fkp))/100;

	fk = number_format(fk,2,'.',',');
	$fkn.val(fk);
}

function get_imb()
{
	var $imb = $('#biaya_imb'), $jl = $('#jumlah_lantai');
	var imb = '0.00';

	if($jl.val()!='' && typeof(imb_values)=='object')
		imb = imb_values[$jl.val()];

	$imb.val(imb);
}

function get_direct_cost()
{
	var $tnk = $('#total_nilai_komponen'), $ohn = $('#overhead_nilai'), $fktn = $('#fee_kontraktor_nilai'), $fksn = $('#fee_konsultan_nilai'), $imb = $('#biaya_imb'), $tbl = $('#total_biaya_langsung');	
	var tnk = replaceall($tnk.val(),',',''), ohn = replaceall($ohn.val(),',',''), fktn = replaceall($fktn.val(),',',''), fksn = replaceall($fksn.val(),',',''), imb = replaceall($imb.val(),',','');
	var tbl = parseFloat(tnk) + parseFloat(ohn) + parseFloat(fktn) + parseFloat(fksn) + parseFloat(imb);

	tbl = number_format(tbl,2,'.',',');
	$tbl.val(tbl);
}

function get_ppn()
{
	var $tnk = $('#total_nilai_komponen'), $ppn_p = $('#ppn_persen'), $ppn_n = $('#ppn_nilai');	
	var tnk = replaceall($tnk.val(),',','');
	var ppn = (tnk * $ppn_p.val())/100;

	ppn = number_format(ppn,2,'.',',');
	$ppn_n.val(ppn);
}

function get_additional_cost()
{
	var $tbl = $('#total_biaya_langsung'), $blp = $('#biaya_lain_persen'), $bln = $('#biaya_lain_nilai');	
	var tbl = replaceall($tbl.val(),',','');
	var bl = (tbl * $blp.val())/100;

	bl = number_format(bl,2,'.',',');
	$bln.val(bl);
}

function get_idc()
{
	var $tbl = $('#total_biaya_langsung'), $idcp = $('#idc_bunga_konstruksi_persen'), $idcn = $('#idc_bunga_konstruksi_nilai');	
	var tbl = replaceall($tbl.val(),',','');
	var idc = (tbl * $idcp.val())/100;

	idc = number_format(idc,2,'.',',');
	$idcn.val(idc);
}

function get_indirect_cost()
{
	var $ppn_n = $('#ppn_nilai'), $bln = $('#biaya_lain_nilai'), $ibkn = $('#idc_bunga_konstruksi_nilai'), $tbtl = $('#total_biaya_tidak_langsung');	
	var ppn = replaceall($ppn_n.val(),',',''), bln = replaceall($bln.val(),',',''), idc = replaceall($ibkn.val(),',','');
	var tbtl = parseFloat(ppn) + parseFloat(bln) + parseFloat(idc);

	tbtl = number_format(tbtl,2,'.',',');
	$tbtl.val(tbtl);
}

function get_total_building_cost()
{
	var $tbl = $('#total_biaya_langsung'), $tbtl = $('#total_biaya_tidak_langsung'), $tbb = $('#total_biaya_bangunan');	
	var tbl = replaceall($tbl.val(),',',''), tbtl = replaceall($tbtl.val(),',','');
	var tbb = parseFloat(tbl) + parseFloat(tbtl);

	tbb = number_format(tbb,2,'.',',');
	$tbb.val(tbb);
}

function get_total_building_cost2()
{
	var $tbb = $('#total_biaya_bangunan'), $jbb = $('#jumlah_biaya_bangunan');
	var tbb = replaceall($tbb.val(),',','');
	var jbb = parseFloat(tbb) * 1000;

	jbb = number_format(jbb,2,'.',',');
	$jbb.val(jbb);
}

function get_rounded()
{
	var $jbb = $('#jumlah_biaya_bangunan'), $r = $('#rounded');
	var jbb = replaceall($jbb.val(),',','');
	var r = round(jbb,-5);

	r = number_format(r,2,'.',',');
	$r.val(r);
}

function check_range_value(v,min,max)
{
	if(v!='')
	{
		v = parseFloat(v);
		min = parseFloat(min);
		max = parseFloat(max);

		if(v>=min && v<=max)
			return true;
	}
	else
		return true;

	return false;
}

function mix_function1()
{
	get_whole_unit_price();
	get_overhead();
	get_contractor_fee();
	get_consultant_fee();
	get_imb();
	get_direct_cost();

	get_ppn();
	get_additional_cost();
	get_idc();
	get_indirect_cost();

	get_total_building_cost();
	get_total_building_cost2();
	get_rounded();
}

function mix_function2(index)
{	
	get_unit_price(index);
	get_total_unit_price();
	get_overhead();
	get_contractor_fee();
	get_consultant_fee();
	get_direct_cost();

	get_ppn();
	get_additional_cost();
	get_idc();
	get_indirect_cost();

	get_total_building_cost();
	get_total_building_cost2();
	get_rounded();
}

function mix_function3()
{
	var $ohp = $('#overhead_persen'), $ohp2 = $('#overhead_persen2'), $min_ohp = $('#min_overhead_persen'), $max_ohp = $('#max_overhead_persen');	
	var crv =  check_range_value($ohp.val(),$min_ohp.val(),$max_ohp.val());

	if(crv)
	{
		$ohp2.val($ohp.val());		

		get_overhead();
		get_direct_cost();
		get_additional_cost();
		get_idc();
		get_indirect_cost();
		get_total_building_cost();
		get_total_building_cost2();
		get_rounded();
	}
	else
	{
		alert('nilai yang dimasukkan tidak boleh di luar range!');
		$ohp.val($ohp2.val());
	}
}

function mix_function4()
{
	get_contractor_fee();
	get_direct_cost();
	get_additional_cost();
	get_idc();
	get_indirect_cost();
	get_total_building_cost();
	get_total_building_cost2();
	get_rounded();
}

function mix_function5()
{
	var $fksp = $('#fee_konsultan_persen'), $fksp2 = $('#fee_konsultan_persen2'), $min_fksp = $('#min_fee_konsultan_persen'), $max_fksp = $('#max_fee_konsultan_persen');	
	var crv =  check_range_value($fksp.val(),$min_fksp.val(),$max_fksp.val());

	if(crv)
	{
		$fksp2.val($fksp.val());		

		get_consultant_fee();
		get_direct_cost();
		get_additional_cost();
		get_idc();
		get_indirect_cost();
		get_total_building_cost();
		get_total_building_cost2();
		get_rounded();
	}
	else
	{
		alert('nilai yang dimasukkan tidak boleh di luar range!');
		$fksp.val($fksp2.val());
	}
}

function mix_function6()
{
	get_ppn();
	get_indirect_cost();
	get_total_building_cost();
	get_total_building_cost2();
	get_rounded();
}

function mix_function7(){
	get_additional_cost();
	get_indirect_cost();
	get_total_building_cost();
	get_total_building_cost2();
	get_rounded();
}

function mix_function8(){
	get_idc();
	get_indirect_cost();
	get_total_building_cost();
	get_total_building_cost2();
	get_rounded();
}
