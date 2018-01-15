
function gnv(val)
{
    return (val==''?'0':val);
}


function get_indicated_land_value_sqm(ilv_id,land_area_id,ilv_sqm_id)
{
    var $ilv = $('#'+ilv_id), $la = $('#'+land_area_id), $ilv_sqm = $('#'+ilv_sqm_id);
    var ilv = gnv($ilv.val()), la = gnv($la.val()), ilv_sqm = '';

    ilv = replaceall(ilv,',','');
    la = replaceall(la,',','');
    
    ilv_sqm = (la==0?0:parseFloat(ilv)/parseFloat(la));
    ilv_sqm = number_format(ilv_sqm,0,'.',',');    
    $ilv_sqm.val(ilv_sqm);
}

function get_indicated_property_value_land(ipv_id,land_area_id,ipv_land_id)
{
    var $ipv = $('#'+ipv_id), $la = $('#'+land_area_id), $ipv_land = $('#'+ipv_land_id);
    var ipv = gnv($ipv.val()), la = gnv($la.val()), ipv_land = '';

    ipv = replaceall(ipv,',','');
    la = replaceall(la,',','');

    ipv_land = (la==0?0:parseFloat(ipv)/parseFloat(la));    
    ipv_land = number_format(ipv_land,0,'.',',');    
    $ipv_land.val(ipv_land);
}

function get_indicated_land_value(ipv_id,ibmv_id,ilv_id)
{
    var $ipv = $('#'+ipv_id), $ibmv = $('#'+ibmv_id), $ilv = $('#'+ilv_id);
    var ipv = gnv($ipv.val()), ibmv = gnv($ibmv.val()), ilv = '';

    ipv = replaceall(ipv,',','');
    ibmv = replaceall(ibmv,',','');

    ilv = parseFloat(ipv)-parseFloat(ibmv);
    ilv = number_format(ilv,0,'.',',');
    $ilv.val(ilv);
}

function get_indicated_building_market_value(ibmv_sqm_id,building_area_id,ibmv_id)
{
    var $ibmv_sqm = $('#'+ibmv_sqm_id), $ba = $('#'+building_area_id), $ibmv = $('#'+ibmv_id);
    var ibmv_sqm = gnv($ibmv_sqm.val()), ba = gnv($ba.val()), ibmv = '';

    ibmv_sqm = replaceall(ibmv_sqm,',','');
    ba = replaceall(ba,',','');

    ibmv = parseFloat(ibmv_sqm)*parseFloat(ba);
    ibmv = number_format(ibmv,0,'.',',');
    $ibmv.val(ibmv);
}

function get_indicated_building_market_value_sqm(building_area_id,market_value_id,ibmv_sqm_id)
{
    var $ba = $('#'+building_area_id), $mv = $('#'+market_value_id), $ibmv_sqm = $('#'+ibmv_sqm_id);
    var ba = gnv($ba.val()), mv = gnv($mv.val()), ibmv_sqm = '';

    ba = replaceall(ba,',','');
    mv = replaceall(mv,',','');

    ibmv_sqm = (ba==0?0:parseFloat(mv)/parseFloat(ba));
    ibmv_sqm = number_format(ibmv_sqm,0,'.',',');
    $ibmv_sqm.val(ibmv_sqm);
}

function get_indicated_property_value(offering_id,transaction_id,total_id,ipv_id)
{
    var $o = $('#'+offering_id), $t = $('#'+transaction_id), $_t = $('#'+total_id), $i = $('#'+ipv_id);
    var o = gnv($o.val()), t = gnv($t.val()), _t = gnv($_t.val()), i = '';
    o = replaceall(o,',','');
    i = (o==0?t:_t);
    $i.val(i);
}

function get_total_price(price_id,discount_id,total_id)
{
    var $p = $('#'+price_id), $d = $('#'+discount_id), $t = $('#'+total_id);
    var p = gnv($p.val()), d = gnv($d.val()), t = '';    
    p = replaceall(p,',','');    
    t = parseFloat(p)-(parseFloat(d)*parseFloat(p)/100);
    t = number_format(t,0,'.',',');
    $t.val(t);
}

function get_indicated_property_value_main()
{
    var $bmv = $('#building_mv'), $simv = $('#site_improvement_mv'), $rtf = $('#rounded2_final'), $ipv = $('#indicated_property_value0');
    var bmv = gnv($bmv.val()), simv = gnv($simv.val()), rtf = gnv($rtf.val()), ipv = '';
    bmv = replaceall(bmv,',','');
    simv = replaceall(simv,',','');
    rtf = replaceall(rtf,',','');
    ipv = parseFloat(bmv) + parseFloat(simv) + parseFloat(rtf);
    ipv = number_format(ipv,0,'.',',');
    $ipv.val(ipv);
}

function get_indicated_building_market_value_sqm_main()
{
    var $bmv = $('#building_mv'), $_ba = $('#_building_area'), $ibmv_sqm = $('#indicated_building_market_value_sqm0');
    var bmv = gnv($bmv.val()), _ba = gnv($_ba.val()), ibmv_sqm='';
    bmv = replaceall(bmv,',','');
    _ba = replaceall(_ba,',','');
    ibmv_sqm = parseFloat(bmv)/parseFloat(_ba);
    ibmv_sqm = number_format(ibmv_sqm,0,'.',',');
    $ibmv_sqm.val(ibmv_sqm);
}


function get_indicated_land_value_main()
{
    var $rtf = $('#rounded2_final'), $ilv = $('#indicated_land_value0');
    var rtf = gnv($rtf.val());
    $ilv.val(rtf);
}

function get_indicated_property_value_land_main()
{
    var $ipv = $('#indicated_property_value0'), $la = $('#land_area0'), $ipvl = $('#indicated_property_value_land0');
    var ipv = gnv($ipv.val()), la = gnv($la.val()), ipvl = '';
    ipv = replaceall(ipv,',','');
    la = replaceall(la,',','');
    ipvl = (la==0?0:parseFloat(ipv)/parseFloat(la));
    ipvl = number_format(ipvl,0,'.',',');
    $ipvl.val(ipvl);
}

function get_indicated_land_value_sqm_main()
{
    var $ilvf = $('#indicated_land_value_final'), $ilv_sqm = $('#indicated_land_value_sqm0');
    var ilvf = gnv($ilvf.val());
    ilvf = replaceall(ilvf,',','');
    ilvf = number_format(ilvf,0,'.',',');
    $ilv_sqm.val(ilvf);
}

function get_percent_result(percent_id,base_id,amount_id)
{
    
    var $p = $('#'+percent_id), $b = $('#'+base_id), $a = $('#'+amount_id);
    var p = gnv($p.val()), b = gnv($b.val()), a = '';
    
    b = replaceall(b,',','');
    a = parseFloat(p)*parseFloat(b)/100;
    a = (a==0?0:number_format(a,2,'.',','));
    
    $a.val(a);
}

function get_sum(data,sum_id)
{
    var $sum = $('#'+sum_id), $val = '';
    var sum = 0, val = 0;
    for(i=0;i<data.length;i++)
    {
        $val = $('#'+data[i]);
        val = gnv($val.val());
        val = replaceall(val,',','');        
        sum += parseFloat(val);
    }
    sum = number_format(sum,2,'.',',');
    $sum.val(sum);
}

function get_indicated_land_value_amount(ilv_sqm_id,taa_id,ilva_id)
{    
    var $ilv_sqm = $('#'+ilv_sqm_id), $taa = $('#'+taa_id), $ilva = $('#'+ilva_id);
    var ilv_sqm = gnv($ilv_sqm.val()), taa = gnv($taa.val()), ilva = '';
    ilv_sqm = replaceall(ilv_sqm,',','');
    taa = replaceall(taa,',','');
    ilva = parseFloat(ilv_sqm) + parseFloat(taa);
    ilva = number_format(ilva,0,'.',',');
    $ilva.val(ilva);
}

function get_rounded1()
{
    var $ilvf = $('#indicated_land_value_final'), $r1f = $('#rounded1_final');
    var ilvf = gnv($ilvf.val()), r1f = '';
    ilvf = replaceall(ilvf,',','');
    r1f = round(ilvf,-4);
    r1f = number_format(r1f,0,'.',',');
    $r1f.val(r1f);
}

function get_rounded2()
{
    var $tlvf = $('#total_land_value_final'), $r2f = $('#rounded2_final');
    var tlvf = gnv($tlvf.val()), r2f = '';
    tlvf = replaceall(tlvf,',','');
    r2f = round(tlvf,-6);
    r2f = number_format(r2f,0,'.',',');
    $r2f.val(r2f);
}

function get_total_land_value()
{
    var $r1f = $('#rounded1_final'), $la = $('#land_area0'), $tlvf = $('#total_land_value_final');
    var r1f = gnv($r1f.val()), la = gnv($la.val()), tlvf = '';
    r1f = replaceall(r1f,',','');
    la = replaceall(la,',','');
    tlvf = parseFloat(r1f) * parseFloat(la);
    tlvf = number_format(tlvf,0,'.',',');
    $tlvf.val(tlvf);
}

function get_liquidation_value()
{
    var $r2f = $('#rounded2_final'), $lw = $('#liquidation_weight'), $lv = $('#liquidation_value');
    var r2f = gnv($r2f.val()), lw = gnv($lw.val()), lv = '';
    r2f = replaceall(r2f,',','');
    lw = replaceall(lw,',','');
    lv = (parseFloat(r2f) * parseFloat(lw))/100;    
    lv = round(lv,-6);
    lv = number_format(lv,0,'.',',');
    $lv.val(lv);
}

function mix_function1(ipv,land_area,ipv_land,ilv,ilv_sqm,arr_pw1,arr_a1,taa,ilva,arr_pw2,arr_a2,pw2,a2,ilvf)
{

    get_indicated_property_value_land(ipv,land_area,ipv_land);
    get_indicated_land_value_sqm(ilv,land_area,ilv_sqm);
    
    get_percent_result(arr_pw1[0],ilv_sqm,arr_a1[0]);
    get_percent_result(arr_pw1[1],ilv_sqm,arr_a1[1]);
    get_percent_result(arr_pw1[2],ilv_sqm,arr_a1[2]);
    get_percent_result(arr_pw1[3],ilv_sqm,arr_a1[3]);
    get_percent_result(arr_pw1[4],ilv_sqm,arr_a1[4]);
    get_percent_result(arr_pw1[5],ilv_sqm,arr_a1[5]);
    get_percent_result(arr_pw1[6],ilv_sqm,arr_a1[6]);
    get_percent_result(arr_pw1[7],ilv_sqm,arr_a1[7]);
    get_percent_result(arr_pw1[8],ilv_sqm,arr_a1[8]);
    get_percent_result(arr_pw1[9],ilv_sqm,arr_a1[9]);
    
    if(typeof(arr_pw1[10])!='undefined' && typeof(arr_pw1[11])!='undefined')
    {
        get_percent_result(arr_pw1[10],ilv_sqm,arr_a1[10]);
        get_percent_result(arr_pw1[11],ilv_sqm,arr_a1[11]);
    }

    get_sum(arr_a1,taa);
    get_indicated_land_value_amount(ilv_sqm,taa,ilva);

    get_percent_result(pw2,ilva,a2);
    get_sum(arr_a2,ilvf);
    get_rounded1();
    get_total_land_value();
    get_rounded2();
    get_liquidation_value();

    get_indicated_property_value_main();
    get_indicated_building_market_value_sqm_main();
    get_indicated_land_value_main();
    get_indicated_property_value_land_main();
    get_indicated_land_value_sqm_main()
}


function mix_function2(offering_price,discount,total_price,transaction_price,ipv,building_area,market_value,ibmv_sqm,ibmv,ilv,land_area,ipv_land,ilv_sqm,arr_pw1,arr_a1,taa,ilva,arr_pw2,arr_a2,pw2,a2,ilvf)
{
    get_total_price(offering_price,discount,total_price);
    get_indicated_property_value(offering_price,transaction_price,total_price,ipv);    
    get_indicated_building_market_value_sqm(building_area,market_value,ibmv_sqm);

    get_indicated_building_market_value(ibmv_sqm,building_area,ibmv);
    get_indicated_land_value(ipv,ibmv,ilv);
    get_indicated_property_value_land(ipv,land_area,ipv_land);
    get_indicated_land_value_sqm(ilv,land_area,ilv_sqm);
    
    get_percent_result(arr_pw1[0],ilv_sqm,arr_a1[0]);
    get_percent_result(arr_pw1[1],ilv_sqm,arr_a1[1]);
    get_percent_result(arr_pw1[2],ilv_sqm,arr_a1[2]);
    get_percent_result(arr_pw1[3],ilv_sqm,arr_a1[3]);
    get_percent_result(arr_pw1[4],ilv_sqm,arr_a1[4]);
    get_percent_result(arr_pw1[5],ilv_sqm,arr_a1[5]);
    get_percent_result(arr_pw1[6],ilv_sqm,arr_a1[6]);
    get_percent_result(arr_pw1[7],ilv_sqm,arr_a1[7]);
    get_percent_result(arr_pw1[8],ilv_sqm,arr_a1[8]);
    get_percent_result(arr_pw1[9],ilv_sqm,arr_a1[9]);

    if(typeof(arr_pw1[10])!='undefined' && typeof(arr_pw1[11])!='undefined')
    {
        get_percent_result(arr_pw1[10],ilv_sqm,arr_a1[10]);
        get_percent_result(arr_pw1[11],ilv_sqm,arr_a1[11]);
    }

    get_sum(arr_a1,taa);
    get_indicated_land_value_amount(ilv_sqm,taa,ilva);

    get_percent_result(pw2,ilva,a2);
    get_sum(arr_a2,ilvf);

    get_rounded1();
    get_total_land_value();
    get_rounded2();
    get_liquidation_value();

    get_indicated_property_value_main();
    get_indicated_building_market_value_sqm_main();
    get_indicated_land_value_main();
    get_indicated_property_value_land_main();
    get_indicated_land_value_sqm_main()
}

function mix_function3(pw1,ilv_sqm,a1,tap,taa,ilva,arr_pw1,arr_a1,pw2,a2,wpf,ilvf,arr_pw2,arr_a2)
{
    get_percent_result(pw1,ilv_sqm,a1);
    get_sum(arr_pw1,tap);
    get_sum(arr_a1,taa);
    get_indicated_land_value_amount(ilv_sqm,taa,ilva);

    get_percent_result(pw2,ilva,a2);
    get_sum(arr_pw2,wpf);
    get_sum(arr_a2,ilvf);

    get_rounded1();
    get_total_land_value();
    get_rounded2();
    get_liquidation_value();

    get_indicated_property_value_main();
    get_indicated_building_market_value_sqm_main();
    get_indicated_land_value_main();
    get_indicated_property_value_land_main();
    get_indicated_land_value_sqm_main()
}

function mix_function4(pw1,ilva,a1,wpf,ilvf,arr_pw1,arr_a1)
{
    get_percent_result(pw1,ilva,a1);
    get_sum(arr_pw1,wpf);
    get_sum(arr_a1,ilvf);

    get_rounded1();
    get_total_land_value();
    get_rounded2();
    get_liquidation_value();

    get_indicated_property_value_main();
    get_indicated_building_market_value_sqm_main();
    get_indicated_land_value_main();
    get_indicated_property_value_land_main();
    get_indicated_land_value_sqm_main()
}

function mix_function5()
{
    get_liquidation_value();
    get_indicated_property_value_main();
    get_indicated_building_market_value_sqm_main();
    get_indicated_property_value_land_main();
}

function set_number_format(number)
{
    return (number!='' || number!='0'?number_format(number,0,'.',','):'');
}


