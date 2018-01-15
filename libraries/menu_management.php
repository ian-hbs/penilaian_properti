<?php
	class menu_management{

		function __construct(){

		}

		function parseTree($tree,$root = null)
		{
		    $return = array();
		    $parent_=false;
		    $masterRoot=false;        

		    # Traverse the tree and search for direct children of the root
		    foreach($tree as $child => $parent) 
		    {
		        # A direct child is found
		        if($parent == $root) 
		        {
		            $masterRoot=($root==null?true:false);                

		            # Remove item from tree (we don't need to traverse this again)
		            unset($tree[$child]);        
		            
		            $parent_=(in_array($child,$tree)?true:false);

		            # Append the child into result array and parse its children
		            $return[] = array(
		                'name' => $child,
		                'parent'=>$parent_,
		                'masterRoot'=>$masterRoot,
		                'children' => $this->parseTree($tree,$child)
		            );            
		        }
		    }        
		    return empty($return) ? null : $return;    
		}

		function getActiveMenu($tree,$ref_active_ct)
		{
		    global $arr_menu_active;
		    $status=false;
		    foreach($tree as $child => $parent)
		    {
		        if($child == $ref_active_ct)
		        {               
		            $status=true;
		            $arr_menu_active[]=$child;
		            $ref_active_ct=$parent;
		        }
		    }        
		    if($status)
		    {        
		        $this->getActiveMenu($tree,$ref_active_ct);
		    }
		    // return $arr_menu_active;
		}

		function printTree($tree,$arr_menu_active,$type,$n)
		{
		    global $arr_menu;
		    if(!is_null($tree) && count($tree) > 0) 
		    {
		        $ulClass=($type=='master'?" class='x-navigation'":" class='treeview-menu'");

				if($n>1)
				{
					echo "<ul".$ulClass.">";
				}
				
		        foreach($tree as $node) 
		        {
		            $id=$node['name'];
		            
		            $liClass1=($node['parent']?"treeview":"");
		            $liClass2=(in_array($id,$arr_menu_active)?"active":"");
		            $href=$arr_menu[$id]['url'];
		            echo "<li class='".$liClass1." ".$liClass2."'><a href='".$href."' title='".$arr_menu[$id]['des']."'><span class='".$arr_menu[$id]['img']."'></span> ";
		            echo ($node['masterRoot']?"<span class='xn-text'>".$arr_menu[$id]['tit']."</span>":$arr_menu[$id]['tit'])."</a>";
		            $this->printTree($node['children'],$arr_menu_active,'child',$n+1);
		            echo "</li>";
		        }

		        if($type!='master')
		        	echo "</ul>";
		    }
		}
	}
?>