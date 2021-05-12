<?php
	// require_once('php-jwt/src/BeforeValidException.php');
	// require_once('php-jwt/src/ExpiredException.php');
	// require_once('php-jwt/src/SignatureInvalidException.php');
	// require_once('php-jwt/src/JWT.php');

	/**
	 * 
	 */
	class Api extends Rest
	{
		public $dbConn;
		function __construct()
		{
			parent::__construct();
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		public function generateToken(){
			//print_r($this->param);
			$email = $this->validateParameter('email', $this->param['email'], STRING);
			//echo $email;
			$password = $this->validateParameter('pass', $this->param['pass'], STRING);
			//echo $password;
			try{
				$stmt = mysqli_query($this->dbConn, "SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$password'") or die(mysqli_error($this->dbConn));
				$user = mysqli_fetch_assoc($stmt);
				//print_r($user);
				if(!is_array($user)){
					$this->returnResponse(INVALID_USER_PASS, "Email or Passoword is incorrect.");
				}
				if($user['active'] == 0){
					$this->returnResponse(USER_NOT_ACTIVE, "User is not activated, please contact admin.");
				}
				$payload = [
					'iat'=>time(),
					'iss'=>'localhost',
					'exp'=>time() + (60 * 15),
					'userId'=>$user['id']
				];

				$token = JWT::encode($payload, SECRETE_KEY);
				$data = ['token'=> $token];
				$this->returnResponse(SUCCESS_RESPONSE, $data);
			}
			catch(Exception $e){
				$this->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
			}
		}

		public function addCustomer(){
			$name = $this->validateParameter('name', $this->param['name'], STRING, false);
			//echo $email;
			$email = $this->validateParameter('email', $this->param['email'], STRING);
			$address = $this->validateParameter('address', $this->param['address'], STRING, false);
			$mobile = $this->validateParameter('mobile', $this->param['mobile'], STRING, false);
			try{
				$token = $this->getBearerToken();
				$payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
				// print_r($payload);
				$userId = $payload->userId;
				$stmt = mysqli_query($this->dbConn, "SELECT * FROM `users` WHERE `id` = '$userId'") or die(mysqli_error($this->dbConn));
				$user = mysqli_fetch_assoc($stmt);
				//print_r($user);
				if(!is_array($user)){
					$this->returnResponse(INVALID_USER_PASS, "This user is not found in our database.");
				}
				if($user['active'] == 0){
					$this->returnResponse(USER_NOT_ACTIVE, "User is not activated, please contact admin.");
				}

				$cust = new Customer;
				$cust->setName($name);
				$cust->setEmail($email);
				$cust->setAddress($address);
				$cust->setMobile($mobile);
				$cust->setCreatedBy($userId);
				$cust->setCreatedOn(date('Y-m-d:H-i-s'));
				$booStatus = true;
				if(!$cust->insert()){
					$message = "Failed to insert.";
					$booStatus = false;
				}else{
					$message = "Inserted successfully.";
				}
				$this->returnResponse(SUCCESS_RESPONSE, $message);
			}
			catch(Exception $e){
				$this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
			}
		}
	}
?>