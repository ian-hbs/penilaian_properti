<?php
	$list_sql = "SELECT a.user_id,a.fullname,a.username,a.register_id,a.modifiable,
				(CASE a.blocked WHEN '0' THEN 'no' ELSE 'yes' END) as blocked, 
				b.name as user_type,
				IF(a.modified_time IS NULL,a.created_time,a.modified_time) as modified_time FROM users as a LEFT JOIN user_types as b ON (a.type_fk=b.type_id)";
?>