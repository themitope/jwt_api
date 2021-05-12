<?php
	/**
	 * 
	 */
	class DbConnect
	{
		private $server = 'localhost';
		private $db_name = 'jwt_api';
		private $user = 'root';
		private $password = '';
		public function connect()
		{
			$conn = mysqli_connect($this->server,  $this->user, $this->password, $this->db_name);
			if(mysqli_connect_error()){
				die("Database Connection Failed" . mysqli_connect_error() . mysqli_connect_errno());
			}
			return $conn;
		}
	}
	$db = new DbConnect();
	$db->connect();
?>