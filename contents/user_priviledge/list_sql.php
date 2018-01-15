<?php
	$list_sql = "SELECT a.priviledge_id,a.read_priv,a.add_priv,a.update_priv,a.delete_priv,b.name as jenis_user,c.title as menu FROM user_priviledges as a,
				 user_types as b, menus as c WHERE(a.type_fk=b.type_id AND a.menu_fk=c.menu_id)";
?>