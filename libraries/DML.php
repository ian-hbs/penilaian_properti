<?php
	class DML
	{
		protected $_tablename;
		protected $_db;

		public function __construct($tablename,$db)
		{
			$this->_db=$db;
			$this->_tablename=$tablename;
		}

		public function connect()
		{
			$this->_db->connect();
		}

		public function close()
		{
			$this->_db->close();
		}
		function execute($sql)
		{
			$result = $this->_db->Execute($sql);
			return $result;
		}
		function save(array $data)
		{
			$sql="INSERT INTO `".$this->_tablename."` SET";
			foreach($data as $field => $value)
			{
				$sql.= " `".$field."`='".$value."',";
			}
			$sql=rtrim($sql,',');
			
			// echo $sql."<br /><br />";

			$result=$this->_db->Execute($sql);

			return $result;
		}

		function update(array $data,$where='')
		{
			$sql="UPDATE `".$this->_tablename."` SET";
			foreach($data as $field => $value)
			{
				$sql.= " `".$field."`='".$value."',";
			}
			$sql=rtrim($sql,',');			
			if($where)
			{
				$sql.= " WHERE ".$where;
			}

			// echo $sql."<br /><br />";

			$result=$this->_db->Execute($sql);
			
			return $result;
		}

		function updateBy(array $kondisi,array $data)
		{
			$where="";
			foreach($kondisi as $field => $value)
			{
				$where.=" `".$field."`='".$value."' AND";
			}
			$where=substr($where,0,strlen($where)-4);
			return $this->update($data,$where);
		}

		function delete($where='')
		{
			$sql="DELETE FROM `".$this->_tablename."`";
			if($where)
			{
				$sql.=" WHERE ".$where;
			}
			
			// echo $sql."<br /><br />";
						
			$result=$this->_db->Execute($sql);
			return $result;
		}

		function deleteBy($field,$value)
		{
			$where="`".$field."`='".$value."'";
			return $this->delete($where);
		}

		function fetchAllData()
		{
			include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'fetch_data.php';
			$sql="SELECT * FROM `".$this->_tablename."`";
			return new Fetch_data($sql,$this->_db);
		}
		function fetchDataBy($field,$value)
		{
			include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'fetch_data.php';
			$sql="SELECT * FROM `".$this->_tablename."`";
			$sql.=" WHERE `".$field."`='".$value."'";
			return new Fetch_data($sql,$this->_db);
		}
		function fetchData($sql)
		{
			include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'fetch_data.php';			
			return new Fetch_data($sql,$this->_db);
		}


		function getCurrentData($act,$arr_field,$id_name,$id_value)
		{	
			$result=array();
			if($act=='add')
			{
				foreach($arr_field as $val)
				{
					$result[$val] = '';
				}
			}
			else
			{				
				$data = $this->fetchDataBy($id_name,$id_value);
				$row = array();
				
				foreach($data as $val)
				{
					$row=$val;
				}

				foreach($row as $key => $val)
				{
					if(isset($key))
					{
						$result[$key] = $val;
					}	
				}				
			}			
			return $result;
		}

		function getFetchResult($obj)
		{
			$result = array();

			foreach($obj as $row)
				$result = $row;
			
			return $result;			
		}
	}

?>