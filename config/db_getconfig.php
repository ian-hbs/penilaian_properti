<?php	
	class db_getconfig
	{
		protected static $_config=array();

		public static function getConfig($key)
		{
			if(!self::$_config)
			{
				$filename=dirname(__FILE__).DIRECTORY_SEPARATOR.'db_param_local.ini';
				if(file_exists($filename))
				{
					$config=parse_ini_file($filename);
					if(false === $config)
					{
						throw new Exception('Gagal membaca file konfigurasi');
					}
				}
				else{
					throw new Exception('File config.ini tidak ditemukan');
				}
				self::$_config=$config;
			}

			if(isset(self::$_config[$key])){
				return self::$_config[$key];	
			}
		}
	}

	/* End of file config.php */
	/* Location: ./config/config.php */
?>