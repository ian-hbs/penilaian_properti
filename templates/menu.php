<?php
	include_once "libraries/menu_management.php";	

	$menuObj = new menu_management();	

	$active_ct=(isset($_GET['ct'])?$_GET['ct']:'');
	
	$sql="SELECT b.menu_id,b.reference,b.title,b.url,b.description,b.image FROM user_priviledges as a INNER JOIN (SELECT * FROM menus WHERE showed='1') as b ON (a.menu_fk=b.menu_id)
		  WHERE(a.type_fk='".$_SESSION['user_type_id']."') ORDER BY a.priviledge_id ASC";

	$result=$db->Execute($sql);
    if(!$result)
        die($db->ErrorMsg());

    $arr_menu=array();
    $tree=array();    

    while($row=$result->FetchRow())
    {        
        $ct='';
        $x=explode('?',$row['url']);

        if(count($x)>1)
        {
            $y=explode('=',$x[1]);
            $ct=(count($y)>1?$y[1]:'');        
        }
        
        $arr_menu[$row['menu_id']]=array('tit'=>$row['title'],'url'=>$row['url'],'des'=>$row['description'],'img'=>$row['image'],'ct'=>$ct);
        $tree[$row['menu_id']]=($row['reference']==0?null:$row['reference']);
    }

    //get the parent of active menu
    $sql="SELECT menu_id,reference FROM menus WHERE(url='index.php?ct=".$active_ct."')";
    $result=$db->Execute($sql);
    if(!$result)
    {
        die($db->ErrorMsg());
    }
    $data=$result->FetchRow();
    $ref_active_ct=$data['reference'];
    $id_active_ct=$data['menu_id'];

    $arr_menu_active=array($id_active_ct);

    $rs_parsTree = $menuObj->parseTree($tree);
    $menuObj->getActiveMenu($tree,$ref_active_ct);    

	//$active=(!isset($_GET['ct'])?"class='active'":"");	
	//echo "<li ".$active."><a href='index.php'><i class='fa fa-home'></i> <span>Dashboard</span></a></li>";

	$menuObj->printTree($rs_parsTree,$arr_menu_active,'master',1);
?>