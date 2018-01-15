<?php
    
    function print_row($row,$x,$no)
    {
        global $editAccess, $deleteAccess, $kunci_pencarian, $fk_penugasan, $menu_id, $fn, $data_type, $jenis_perusahaan_penunjuk;

        foreach($row as $key => $val){
                $key=strtolower($key);
                $$key=$val;
        }        
        echo "<tr>
        <td>".$description."</td>
        <td align='center'>".$qty."</td>
        <td align='center'>".$built_year."</td>
        <td align='center'>".$renov_year."</td>
        <td align='center'>".$construction."</td>
        <td align='center'>".$eco_use_life."</td>
        <td align='center'>".$cond_on_inspec."</td>
        <td align='center'>".number_format($maintenance,2,'.',',')."</td>
        <td align='center'>".number_format($phys_deter,2,'.',',')."</td>
        <td align='center'>".number_format($func_obsc,2,'.',',')."</td>
        <td align='center'>".number_format($eco_obsc,2,'.',',')."</td>
        <td align='center'>".number_format($location_index,2,'.',',')."</td>
        <td align='center'>".number_format($floor_area,2,'.',',')."</td>
        <td align='center'>".number_format($total_floor_area,2,'.',',')."</td>
        <td align='right'>".number_format($cost_sqm2,0,'.',',')."</td>
        <td align='right'>".number_format($crn,0,'.',',')."</td>
        <td align='center'>".$remain."</td>
        <td align='right'>".number_format($market_value,0,'.',',')."</td>
        <td align='right'>".number_format($liquidation_value,0,'.',',')."</td>
        <td>";
            $id = ($data_type=='1'?$id_perhitungan_bangunan:$id_perhitungan_bangunan_pembanding);
            if($editAccess)
                echo "<button type='button' title='Edit' class='btn btn-xs btn-default' id='edit_".$x."_".$no."' onclick=\"load_form_content(this.id);\">";
            else
                echo "<button type='button' title='Edit' class='btn btn-xs btn-default' onclick=\"alert('Anda tidak memiliki akses untuk mengedit data!');\">";
            echo "                              
              <input type='hidden' id='ajax-req-dt' name='id' value='".$id."'/>
              <input type='hidden' id='ajax-req-dt' name='id_penugasan' value='".$fk_penugasan."'/>
              <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>
              <input type='hidden' id='ajax-req-dt' name='act' value='edit'/>
              <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
              <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
              <input type='hidden' id='ajax-req-dt' name='data_type' value='".$data_type."'/>
              <input type='hidden' id='ajax-req-dt' name='jenis_perusahaan_penunjuk' value='".$jenis_perusahaan_penunjuk."'/>
              <i class='fa fa-edit'></i>
            </button>&nbsp;";

            if($deleteAccess)
                echo "<button type='button' title='Hapus' class='btn btn-xs btn-default' id='delete_".$x."_".$no."' onclick=\"if(confirm('Anda yakin?')){exec_delajax(this.id)}\">";
            else
                echo "<button type='button' title='Hapus' class='btn btn-xs btn-default' onclick=\"alert('Anda tidak memiliki akses untuk menghapus data!');\">";
            
            $type = ($data_type=='1'?$type:'');
            $fk_objek_pembanding = ($data_type=='1'?'':$fk_objek_pembanding);
            echo "
            <input type='hidden' id='ajax-req-dt' name='id' value='".$id."'/>
            <input type='hidden' id='ajax-req-dt' name='fk_penugasan' value='".$fk_penugasan."'/>
            <input type='hidden' id='ajax-req-dt' name='kunci_pencarian' value='".$kunci_pencarian."'/>
            <input type='hidden' id='ajax-req-dt' name='act' value='delete'/>
            <input type='hidden' id='ajax-req-dt' name='menu_id' value='".$menu_id."'/>
            <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
            <input type='hidden' id='ajax-req-dt' name='data_type' value='".$data_type."'/>
            <input type='hidden' id='ajax-req-dt' name='type' value='".$type."'/>
            <input type='hidden' id='ajax-req-dt' name='jenis_perusahaan_penunjuk' value='".$jenis_perusahaan_penunjuk."'/>
            <input type='hidden' id='ajax-req-dt' name='fk_objek_pembanding' value='".$fk_objek_pembanding."'/>
            <i class='fa fa-trash-o'></i></button>
        </td>
        </tr>";
    }

    echo "
    <table class='table table-bordered'>
        <thead>
            <tr>
                <td align='center'>Description</td>
                <td align='center'>Qty</td>
                <td align='center'>Build<br />Year</td>
                <td align='center'>Renov<br />Year</td>
                <td align='center'>Const.</td>
                <td align='center'>Eco.<br />Use.<br />Life</td>
                <td align='center'>Cond.<br />on<br />Inspec</td>
                <td align='center'>Maint.</td>
                <td align='center'>Phys.<br />Deter.</td>
                <td align='center'>Func.<br />Obsc.</td>
                <td align='center'>Eco<br />Obsc.</td>
                <td align='center'>Loc.<br />Index</td>
                <td align='center'>Floor<br />Area</td>
                <td align='center'>Total<br />Floor<br />Area</td>
                <td align='center'>Cost/<br />sqm<br />Rp.</td>
                <td align='center'>CRN<br />Rp.</td>
                <td align='center'>2016<br />Remain</td>
                <td align='center'>Market<br />Value Rp.</td>
                <td align='center'>Liquidation<br />Value</td>
                <td align='center'>Aksi</td>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan='20'><b>".($data_type=='1'?'BUILDINGS':'DATA MARKET')."</b></td>
        </tr>";        
        $total1_1 = 0;
        $total1_2 = 0;
        $total1_3 = 0;
        $total1_4 = 0;
        $total1_5 = 0;
        $total1_6 = 0;

        $total2_1 = 0;
        $total2_2 = 0;
        $total2_3 = 0;
        $total2_4 = 0;
        $total2_5 = 0;
        $total2_6 = 0;

        $no = 0;
        $x = 1;
        while($row = $list_of_data2->FetchRow())
        {
            $no++;
            print_row($row,$x,$no);
            $total1_1 += $row['floor_area'];
            $total1_2 += $row['total_floor_area'];
            $total1_3 += $row['cost_sqm2'];
            $total1_4 += $row['crn'];
            $total1_5 += $row['market_value'];
            $total1_6 += $row['liquidation_value'];
        }

        if($data_type=='1')
        {
            echo "
            <tr>
                <td colspan='12' align='right'><b>TOTAL BUILDINGS</b></td>
                <td align='center'><b>".number_format($total1_1,2,'.',',')."</b></td>
                <td align='center'><b>".number_format($total1_2,2,'.',',')."</b></td>
                <td align='right'><b>".number_format($total1_3,0,'.',',')."</b></td>
                <td align='right'><b>".number_format($total1_4,0,'.',',')."</b></td>
                <td></td>
                <td align='right'><b>".number_format($total1_5,0,'.',',')."</b></td>
                <td align='right'><b>".number_format($total1_6,0,'.',',')."</b></td>
                <td></td>
            </tr>";

            if($jenis_perusahaan_penunjuk=='1')
            {
                echo "<tr>
                    <td colspan='20'><b>SITE IMPROVEMENT</b></td>
                </tr>";
                
                $no = 0;
                $x = 2;
                while($row = $list_of_data2_2->FetchRow())
                {
                    $no++;
                    print_row($row,$x,$no);
                    $total2_1 += $row['floor_area'];
                    $total2_2 += $row['total_floor_area'];
                    $total2_3 += $row['cost_sqm2'];
                    $total2_4 += $row['crn'];
                    $total2_5 += $row['market_value'];
                    $total2_6 += $row['liquidation_value'];
                }            
                echo "
                <tr>
                    <td colspan='12' align='right'><b>TOTAL SITE IMPROVEMENT</b></td>
                    <td align='center'><b>".number_format($total2_1,2,'.',',')."</b></td>
                    <td align='center'><b>".number_format($total2_2,2,'.',',')."</b></td>
                    <td align='right'><b>".number_format($total2_3,0,'.',',')."</b></td>
                    <td align='right'><b>".number_format($total2_4,0,'.',',')."</b></td>
                    <td></td>
                    <td align='right'><b>".number_format($total2_5,0,'.',',')."</b></td>
                    <td align='right'><b>".number_format($total2_6,0,'.',',')."</b></td>
                    <td></td>
                </tr>";
            }

        }
        
        echo "
        </tbody>";
        if($data_type=='1' && $jenis_perusahaan_penunjuk=='1')
        {
            $gtotal1 = $total1_1+$total2_1;
            $gtotal2 = $total1_2+$total2_2;
            $gtotal3 = $total1_3+$total2_3;
            $gtotal4 = $total1_4+$total2_4;
            $gtotal5 = $total1_5+$total2_5;
            $gtotal6 = $total1_6+$total2_6;
            echo "
            <tfoot>
            <tr>
                <td colspan='12' align='right'><b>TOTAL BUILDINGS & OTHER SITE IMPROVEMENT (BOSI)</b></td>
                <td align='center'><b>".number_format($gtotal1,2,'.',',')."</b></td>
                <td align='center'><b>".number_format($gtotal2,2,'.',',')."</b></td>
                <td align='right'><b>".number_format($gtotal3,0,'.',',')."</b></td>
                <td align='right'><b>".number_format($gtotal4,0,'.',',')."</b></td>
                <td></td>
                <td align='right'><b>".number_format($gtotal5,0,'.',',')."</b></td>
                <td align='right'><b>".number_format($gtotal6,0,'.',',')."</b></td>
                <td></td>
            </tr>   
            </tfoot>";
        }
    echo "</table>";
?>