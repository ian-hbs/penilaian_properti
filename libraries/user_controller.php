<?php
	
	class user_controller
	{
		protected $_db;


		function __construct($db){
			$this->_db=$db;
		}

		function login_process($username,$password,$ip)
		{
			$sql = "SELECT a.*,b.name as type_name FROM users as a LEFT JOIN user_types as b ON (a.type_fk=b.type_id)
					WHERE(username='".$username."' and password='".$password."')";
	        
	        
			$result = $this->_db->Execute($sql);
			if(!$result)
			{
				return 'failed';				
			}

			if($result->RecordCount()>0)
			{
				$data = $result->FetchRow();
				$user_id = $data['user_id'];

				$sec1 = microtime();
			    mt_srand((double)microtime()*1000000);
			    $sec2 = mt_rand(1000,9999);
			    $session_id = md5($sec2.$sec2);

				//delete session data for current username
				$sql_manipulating = "DELETE FROM sessions WHERE(user_fk='".$user_id."')";
				$result = $this->_db->Execute($sql_manipulating);
			    if (!$result) 
			    {
			      return 'failed';
			    }
			  	// ===== //

			  	//save new session data for current username
			  	$time= explode(" ", microtime());
		    	$last_access= (double) $time[1];
			    $sql_manipulating = "INSERT INTO sessions (session_id,user_fk,last_access) 
			    		   VALUES ('".$session_id."','".$user_id."','".$last_access."')";
			    $result = $this->_db->Execute($sql_manipulating);
			    if (!$result)
			    {
			    	return 'failed';
			    }
			  	// ===== //

			  	//save new login history for current username
			  	$ip_address = $ip;
			  	$login_time = date('Y-m-d H:i:s');
			  	$sql_manipulating = "INSERT INTO login_histories (user_id,ip_address,last_login_time)
			  						 VALUES('".$user_id."','".$ip_address."','".$login_time."')";
				$result = $this->_db->Execute($sql_manipulating);
			    if (!$result)
			    {
			    	return 'failed';
			    }			      
			  	// ===== //

			  	$_SESSION['session_id']     = $session_id;
			  	$_SESSION['user_id']       	= $data['user_id'];
			  	$_SESSION['user_type_id']   = $data['type_fk'];
			    $_SESSION['user_type']      = $data['type_name'];
			    $_SESSION['username']       = $username;
			    $_SESSION['login_time']		= $login_time;	    
			    if($data['type_fk']=='4' || $data['type_fk']=='5')
			    {
			    	$_SESSION['village_id'] = $data['village_fk'];
			    }

				return 'success';
			}
			else
			{
				return 'failed';
			}
		}

		function logout_process()
		{			
			$sql_manipulating = "DELETE from sessions WHERE session_id='".$_SESSION['session_id']."'";
			
			$result = $this->_db->Execute($sql_manipulating);

			if (!$result) 
				echo $this->_db->ErrorMsg();
				   
			session_destroy();

			header("location:login.php");
		}

		function check_access()
		{
			if(!isset($_SESSION['session_id']) or (isset($_SESSION['session_id']) and empty($_SESSION['session_id'])))
			{
				echo "<script type='text/javascript'>					
					document.location.href='login.php';
				</script>";				
				exit();
			}
			
			//Execute the SQL Statement (Get Username)
			$sql = "SELECT user_fk,last_access from sessions WHERE session_id='".$_SESSION['session_id']."'";
			$result	= $this->_db->Execute($sql);
			if(!$result)
				die($this->_db->ErrorMsg());			

			if ($result->RecordCount() == 0)
			{
				echo "
					<script type='text/javascript'>
						alert('Ada pengguna lain yang menggunakan login anda atau session anda telah expired, silahkan login kembali');
						document.location.href='logout_process.php';
					</script>";
				exit();
			}

			$data = $result->FetchRow();
			$user_fk = $data['user_fk'];
			$last_access = $data['last_access'];

			/*=====================================================
			AUTO LOG-OFF 15 MINUTES
			======================================================*/

			//Update last access!
			$time= explode(" ", microtime());
			$usersec= (double) $time[1];
			
			$diff   = $usersec-$last_access;
			$limit  = 60*30; //harusnya 15 menit, tapi sementara pasang 60 menit/1 jam dahulu, biar gak shock
			
			if($diff>$limit)
			{				
	      		echo "
					<script type='text/javascript'>
						alert('Maaf status anda idle lebih dari 30 menit dan session Anda telah expired, silahkan login kembali');
						document.location.href='logout_process.php';
					</script>";
	      		exit();
			}
			else
			{
			    $sql="update sessions set last_access='".$usersec."' where user_fk='".$user_fk."'";
			    $result = $this->_db->Execute($sql);
			    if (!$result) 
			    	echo $this->_db->ErrorMsg();
			}

		}

		function check_priviledge($restriction="all",$menu_id)
		{			
			$access_granted=false;

			$type_fk = $_SESSION['user_type_id'];

			if($restriction != 'all')
			{
				$_restriction=strtolower($restriction."_priv");

				$sql="select ".$_restriction." as check_access from user_priviledges where type_fk='".$type_fk."' and menu_fk='".$menu_id."'";
				
				$result = $this->_db->Execute($sql);
				if (!$result)
					die($this->_db->ErrorMsg());
				
				$data = $result->FetchRow();
				
				$access_granted=($data['check_access']==1?true:false);
			}
			else
			{
				$access_granted=true;
			}
			$result->Close();

			return $access_granted;
		}

		function get_menu_id($key,$value)
		{
			$sql = "SELECT menu_id FROM menus WHERE(".$key."='".$value."')";
			$result = $this->_db->Execute($sql);
			if (!$result)
				die($this->_db->ErrorMsg());
			
			if($result->RecordCount()>0)
			{
				$data = $result->FetchRow();
				return $data['menu_id'];
			}
			else return "";

		}
	}
?>