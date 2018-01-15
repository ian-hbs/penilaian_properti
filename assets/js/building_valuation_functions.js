function main_building_controller(type)
{
    var $mbc = $('#main_building_container');    
    var $by1 = $('#built_year1');
    var $by2 = $('#built_year2');
    var $aby = $('#active_built_year');

    if(type=='building')
    {
        $aby.val('#built_year2');
        $mbc.show();
        $by1.attr('required',false);
        $by1.attr('disabled',true);
        $by1.hide();
        $by2.attr('required',true);
        $by2.attr('disabled',false);        
        $by2.show();
    }
    else
    {
        $aby.val('#built_year1');
        $mbc.hide();
        $by1.attr('required',true);
        $by1.attr('disabled',false);
        $by1.show();
        $by2.attr('required',false);
        $by2.attr('disabled',true);
        $by2.hide();
    }
}

function gnv(val)
{
    return (val==''?'0':val);
}

function get_phys_act_age1_adj()
{
    var aby = $('#active_built_year').val();
    var $ry = $('#remain_year'), $by = $(aby), $paa1 = $('#phys_act_age1'), $paa1_val = $('#phys_act_age1_val');
    var ry = gnv($ry.val()), by = gnv($by.val());
    var paa1 = parseInt(ry) - parseInt(by);
    $paa1.html(paa1);
    $paa1_val.val(paa1);
}

function get_phys_deter_year_adj()
{            
    var $eul = $('#eco_use_life'), $pdy = $('#phys_deter_year'), $pdy_val = $('#phys_deter_year_val');
    var eul = $eul.val(), pdy = '';
    if(eul!='' || eul!='0')
    {
        pdy = (1/parseInt(eul))*100;
        pdy = number_format(pdy,2,'.',',');
    }
    $pdy.html(pdy);
    $pdy_val.val(pdy);
}

function get_phys_deter1_adj()
{
    var $paa1 = $('#phys_act_age1'), $pdy = $('#phys_deter_year'), $pd1 = $('#phys_deter1'), $pd1_val = $('#phys_deter1_val');
    var paa1 = gnv($paa1.html()), pdy = gnv($pdy.html());
    paa1 = replaceall(paa1,',','');
    pdy = replaceall(pdy,',','');
    pd1 = parseFloat(paa1) * parseFloat(pdy);
    pd1 = number_format(pd1,2,'.',',');
    $pd1.html(pd1);
    $pd1_val.val(pd1);
}

function get_phys_deter2_adj()
{
    var $pd1 = $('#phys_deter1'), $pd2 = $('#phys_deter2'), $pd2_val = $('#phys_deter2_val');
    var pd1 = gnv($pd1.html()), pd2 = '';
    pd2 = (parseFloat(pd1)>=95?'Ls':number_format(pd1,2,'.',','));    
    $pd2.html(pd2);
    $pd2_val.val(pd2);
}

function get_phys_deter3_adj()
{
    var $pd2 = $('#phys_deter2'), $pd3 = $('#phys_deter3'), $pd3_val = $('#phys_deter3_val');
    var pd2 = gnv($pd2.html()), pd3 = '';
    pd3 = (pd2=='Ls'?'0':pd2);
    pd3 = number_format(pd3,2,'.',',');
    $pd3.html(pd3);
    $pd3_val.val(pd3);
}

function get_func_obsc1_adj()
{
    var $fo = $('#func_obsc'), $fo1 = $('#func_obsc1'), $fo1_val = $('#func_obsc1_val');
    var fo = gnv($fo.val()), fo1 = '';
    fo1 = (fo=='Ls'?'0':(parseFloat(fo)>=0?number_format(fo,2,'.',','):'false'));
    $fo1.html(fo1);
    $fo1_val.val(fo1);
}

function get_eco_obsc1_adj()
{
    var $eo = $('#eco_obsc'), $eo1 = $('#eco_obsc1'), $eo1_val = $('#eco_obsc1_val');
    var eo = gnv($eo.val()), eo1 = '';
    
    eo1 = (eo=='Ls'?'0':(parseFloat(eo)>=0?number_format(eo,2,'.',','):'false'));    
    $eo1.html(eo1);
    $eo1_val.val(eo1);
}

function get_func_obsc2_adj()
{
    var $pd2 = $('#phys_deter2'); $fo1 = $('#func_obsc1'), $fo2 = $('#func_obsc2'), $fo2_val = $('#func_obsc2_val');
    var pd2 = gnv($pd2.html()), fo1 = gnv($fo1.html()), fo2 = '';

    if(pd2=='Ls')    
        fo2 = fo1;
    else if(parseFloat(pd2)>=0)
    {
        pd2 = replaceall(pd2,',','');
        fo1 = replaceall(fo1,',','');
        fo2 = ((100-parseFloat(pd2))*parseFloat(fo1))/100;
        fo2 = number_format(fo2,2,'.',',');
    }
    else
        fo2 = 'false';
    
    $fo2.html(fo2);
    $fo2_val.val(fo2);
}

function get_eco_obsc2_adj()
{
    var $pd2 = $('#phys_deter2'); $eo1 = $('#eco_obsc1'), $eo2 = $('#eco_obsc2'), $eo2_val = $('#eco_obsc2_val');
    var pd2 = gnv($pd2.html()), eo1 = gnv($eo1.html()), eo2 = '';
    if(pd2=='Ls')    
        eo2 = eo1;
    else if(parseFloat(pd2)>=0)
    {
        pd2 = replaceall(pd2,',','');
        eo1 = replaceall(eo1,',','');
        eo2 = ((100-parseFloat(pd2))*parseFloat(eo1))/100;
        eo2 = number_format(eo2,2,'.',',');
    }
    else
        eo2 = 'false';
    
    $eo2.html(eo2);
    $eo2_val.val(eo2);
}

function get_phys_act_age2_adj()
{
    var $ry = $('#remain_year'), $_ry = $('#renov_year'), $paa2 = $('#phys_act_age2'), $paa2_val = $('#phys_act_age2_val');
    var ry = gnv($ry.val()), _ry = gnv($_ry.val());
    var paa2 = parseInt(ry) - parseInt(_ry);
    $paa2.html(paa2);
    $paa2_val.val(paa2);
}

function get_phys_deter4_adj()
{
    var $paa2 = $('#phys_act_age2'), $pdy = $('#phys_deter_year'), $pd4 = $('#phys_deter4'), $pd4_val = $('#phys_deter4_val');
    var paa2 = gnv($paa2.html()), pdy = gnv($pdy.html());
    paa2 = replaceall(paa2,',','');
    pdy = replaceall(pdy,',','');
    pd4 = parseFloat(paa2) * parseFloat(pdy);    
    pd4 = number_format(pd4,2,'.',',');
    $pd4.html(pd4);
    $pd4_val.val(pd4);
}

function get_remain_act_adj()
{
    var $coi = $('#cond_on_inspec'), $pd1 = $('#phys_deter1'), $fo2 = $('#func_obsc2'), $eo2 = $('#eco_obsc2'), $ra = $('#remain_act'), $ra_val = $('#remain_act_val');
    var coi = $coi.val(), pd1 = gnv($pd1.html()), fo2 = gnv($fo2.html()), eo2 = gnv($eo2.html()), ra = '', x = 0;
    if(coi!='')
    {
        switch(coi)
        {
            case 'B':x = 100;break;
            case 'C':x = 97.5;break;
            case 'K':x = 95;break;
        }
        pd1 = replaceall(pd1,',','');
        fo2 = replaceall(fo2,',','');
        eo2 = replaceall(eo2,',','');
        ra = x-parseFloat(pd1)-parseFloat(fo2)-parseFloat(eo2);
        
        ra = number_format(ra,2,'.',',');
    }

    $ra.html(ra);
    $ra_val.val(ra);
}

function get_remain_rebuild_adj()
{
    var $coi = $('#cond_on_inspec'), $pd4 = $('#phys_deter4'), $fo2 = $('#func_obsc2'), $eo2 = $('#eco_obsc2'), $rr = $('#remain_rebuild'), $rr_val = $('#remain_rebuild_val');
    var coi = $coi.val(), pd4 = gnv($pd4.html()), fo2 = gnv($fo2.html()), eo2 = gnv($eo2.html()), rr = '', x = 0;
    if(coi!='')
    {
        switch(coi)
        {
            case 'B':x = 85;break;
            case 'C':x = 82.5;break;
            case 'K':x = 80;break;
        }
        pd4 = replaceall(pd4,',','');
        fo2 = replaceall(fo2,',','');
        eo2 = replaceall(eo2,',','');
        rr = x-parseFloat(pd4)-parseFloat(fo2)-parseFloat(eo2);
        rr = number_format(rr,2,'.',',');
    }
    
    $rr.html(rr);
    $rr_val.val(rr);
}

function get_first_remain_adj()
{
    var aby = $('#active_built_year').val();
    var $by = $(aby), $ry = $('#renov_year'), $ra = $('#remain_act'), $rr = $('#remain_rebuild'), $fr = $('#first_remain'), $fr_val = $('#first_remain_val');
    var by = $by.val(), ry = $ry.val(), ra = gnv($ra.html()), rr = gnv($rr.html()), fr = '';
    var by_ = parseInt(by), ry_ = parseInt(ry);
    if(by_==ry_)
        fr = ra;
    else if(ry_==0)
        fr = ra;
    else if(ry_>by)
        fr = rr;
    else
        fr = 'false';

    $fr.html(fr);
    $fr_val.val(fr);
}

function get_mv_per_sq_adj()
{
    var $r = $('#remain'), $cs = $('#cost_sqm2'), $mv = $('#mv_per_sqr'), $mv_val = $('#mv_per_sqr_val');
    var r = gnv($r.val()), cs = gnv($cs.val()), mv = '';
    r = replaceall(r,',','');
    cs = replaceall(cs,',','');
    mv = round((parseInt(r)*parseInt(cs))/100,-5);
    mv = number_format(mv,0,'.',',');
    $mv.html(mv);
    $mv_val.val(mv);
}

function get_maintenance_adj()
{
    var aby = $('#active_built_year').val();
    var $by = $(aby), $_ry = $('#renov_year'), $ry = $('#remain_year'), $m = $('#maintenance'), $m_ = $('#maintenance_'), $m_val = $('#maintenance_val');
    var by = gnv($by.val()), _ry = gnv($_ry.val()), ry = $ry.val(), m = gnv($m.val()), m_ = '';
    
    if(parseInt(_ry)>parseInt(by))
    {        
        m = replaceall(m,',','');
        m_ = (parseInt(ry)-parseInt(_ry))*m;
        m_ = number_format(m_,2,'.',',');
    }
    else            
    {        
        m = replaceall(m,',','');
        m_ = (parseInt(ry)-parseInt(by))*m;
        m_ = number_format(m_,2,'.',',');        
    }

    $m_.html(m_);
    $m_val.val(m_);
}

function get_total_adj(){
    var $m_ = $('#maintenance_'), $fr = $('#first_remain'), $t = $('#total_adjustment'), $t_val = $('#total_adjustment_val');
    var m_ = gnv($m_.html()), fr = gnv($fr.html()), t = '';
    m_ = replaceall(m_,',','');
    fr = replaceall(fr,',','');
    t = parseFloat(m_) + parseFloat(fr);
    t = number_format(t,2,'.',',');
    $t.html(t);
    $t_val.val(t);
}




function get_total_floor_area()
{
    var $qty = $('#qty'), $fa = $('#floor_area'), $tfa = $('#total_floor_area');
    var qty = gnv($qty.val()), fa = gnv($fa.val());
    qty = replaceall(qty,',','');
    fa = replaceall(fa,',','');
    var tfa = parseFloat(qty) * parseFloat(fa);
    $tfa.val(number_format(tfa,2,'.',','));
}

function get_crn()
{
    var $cs = $('#cost_sqm2'), $tfa = $('#total_floor_area'), $crn = $('#crn');
    var cs = gnv($cs.val()), tfa = gnv($tfa.val()), crn = '0';
    cs = replaceall(cs,',','');
    tfa = replaceall(tfa,',','');
    crn = round(tfa*cs,-5);
    crn = number_format(crn,0,'.',',');
    $crn.val(crn);
}

function get_market_value()
{
    var $r = $('#remain'), $crn = $('#crn'), $mv = $('#market_value');
    var r = gnv($r.val()), crn = gnv($crn.val()), mv = '';
    r = replaceall(r,',','');
    crn = replaceall(crn,',','');
    mv = round((r*crn)/100,-5);
    mv = number_format(mv,0,'.',',');
    $mv.val(mv);
}

function get_liquidation_value()
{
    var $mv = $('#market_value'), $lw = $('#liquidation_weight'), $lv = $('#liquidation_value');
    var mv = gnv($mv.val()), lw = gnv($lw.val()), lv = '';
    mv = replaceall(mv,',','');
    lw = replaceall(lw,',','');

    lv = round((lw*mv)/100,-5);
    lv = number_format(lv,0,'.',',');
    $lv.val(lv);
}

function get_eco_use_life()
{
    var $c = $('#construction'), $eul = $('#eco_use_life');
    var x = $c.val().split('_');    
    $eul.val((x.length==2?x[1]:''));
}

function get_phys_deter()
{
    var aby = $('#active_built_year').val();
    var $by = $(aby), $ry = $('#renov_year'), $pd2 = $('#phys_deter2'), $pd4 = $('#phys_deter4'), $pd = $('#phys_deter');
    var by = gnv($by.val()), ry = gnv($ry.val()), pd = '';
    if(by!='' && ry!='')
    {
        if(by==ry)
        {            
            pd = $pd2.html();
        }
        else if(ry=='0')
        {            
            pd = $pd2.html();
        }
        else
        {            
            pd = $pd4.html();
        }
    }            
    
    $pd.val(pd);
}

function get_cost_sqm2()
{
    var $psu = $('#price_sqm_usd'), $li = $('#location_index'), $psua = $('#price_sqm_usd_abs'), $cs1 = $('#cost_sqm1'), $cs2 = $('#cost_sqm2');
    var psu = gnv($psu.val()), li = gnv($li.val()), psua = gnv($psua.val()), cs1 = gnv($cs1.val()), cs2 = '';
    psu = replaceall(psu,',','');
    psua = replaceall(psua,',','');
    cs1 = replaceall(cs1,',','');

    cs2 = round((psu>=100?psu*li*psua:cs1*li),-5);
    cs2 = number_format(cs2,0,'.',',');
    $cs2.val(cs2);
}

function get_remain()
{
    var $fr = $('#first_remain'), $m_ = $('#maintenance_'), $r = $('#remain');
    var fr = gnv($fr.html()), m_ = gnv($m_.html()), x = '', r = '';
    fr = replaceall(fr,',','');
    m_ = replaceall(m_,',','');
    x = parseFloat(fr) + parseFloat(m_);
    r = (x>4?x:4);
    $r.val(r);
}

function mix_function1()
{
    get_total_floor_area();
    get_crn();
    get_market_value();
    get_liquidation_value();    
}

function mix_function2()
{
    get_phys_act_age1_adj();
    get_phys_deter1_adj();
    get_phys_deter2_adj();
    get_phys_deter3_adj();
    get_func_obsc2_adj();
    get_eco_obsc2_adj();
    get_remain_act_adj();
    get_remain_rebuild_adj();
    get_first_remain_adj();
    get_phys_deter();    
    get_maintenance_adj();
    get_remain();
    get_mv_per_sq_adj();
    get_total_adj();
    get_market_value();
    get_liquidation_value();
}

function mix_function2_2()
{
    get_phys_act_age2_adj();
    get_phys_deter4_adj();
    get_first_remain_adj();    
    get_phys_deter();
    get_maintenance_adj();    
    get_remain();    
    get_mv_per_sq_adj();
    get_total_adj();
    get_market_value();
    get_liquidation_value();
}

function mix_function3()
{
    get_cost_sqm2();
    get_crn();
    get_market_value();
    get_liquidation_value();
    get_mv_per_sq_adj();
}

function mix_function4()
{

    get_eco_use_life();
    get_phys_deter_year_adj();
    get_phys_deter1_adj();
    get_phys_deter2_adj();
    get_phys_deter3_adj();
    get_func_obsc2_adj();
    get_eco_obsc2_adj();

    get_phys_deter4_adj();
    get_phys_deter();

    get_remain_act_adj();
    get_remain_rebuild_adj();
    get_first_remain_adj();
    get_total_adj();
    get_remain();
    get_market_value();
    get_liquidation_value();
    get_mv_per_sq_adj();
}

function mix_function5()
{
    get_remain_act_adj();
    get_remain_rebuild_adj();
    get_first_remain_adj();
    get_total_adj();
    get_remain();
    get_mv_per_sq_adj();
    get_market_value();
    get_liquidation_value();
}

function mix_function6()
{
    get_maintenance_adj();
    get_total_adj();
    get_remain();
    get_mv_per_sq_adj();
    get_market_value();
    get_liquidation_value();
}

function mix_function7()
{
    get_func_obsc1_adj();
    get_func_obsc2_adj();
    get_remain_act_adj();
    get_remain_rebuild_adj();
    get_first_remain_adj();
    get_total_adj();
    get_remain();
    get_mv_per_sq_adj();
    get_market_value();
    get_liquidation_value();
}

function mix_function8()
{
    get_eco_obsc1_adj();
    get_eco_obsc2_adj();
    get_remain_act_adj();
    get_remain_rebuild_adj();
    get_first_remain_adj();
    get_total_adj();
    get_remain();
    get_mv_per_sq_adj();
    get_market_value();
    get_liquidation_value();
}

function mix_function9()
{    
    get_phys_act_age1_adj();
    get_phys_deter1_adj();
    get_phys_deter2_adj();
    get_phys_deter3_adj();
    get_func_obsc2_adj();
    get_eco_obsc2_adj();
    get_phys_act_age2_adj();
    get_phys_deter4_adj();
    get_phys_deter();
    get_remain_act_adj();
    get_remain_rebuild_adj();
    get_first_remain_adj();
    get_maintenance_adj();
    get_total_adj();
    get_remain();
    get_mv_per_sq_adj();
    get_market_value()
    get_liquidation_value();
}