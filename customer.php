<?php
/**
 * 
 */
class Customer
{	private $id;
	private $name;
	private $email;
	private $address;
	private $mobile;
	private $updatedBy;
	private $updatedOn;
	private $createdBy;
	private $createdOn;
	private $tableName = 'customers';
	private $dbConn;

	function setId($id){$this->id = $id;}
	function setName($name){$this->name = $name;}
	function setEmail($email){$this->email = $email;}
	function setAddress($address){$this->address = $address;}
	function setMobile($mobile){$this->mobile = $mobile;}
	function setUpdatedBy($updatedBy){$this->updatedBy = $updatedBy;}
	function setUpdatedOn($updatedOn){$this->updatedOn = $updatedOn;}
	function setCreatedBy($createdBy){$this->createdBy = $createdBy;}
	function setCreatedOn($createdOn){$this->createdOn = $createdOn;}

	function __construct(){
		$db = new DbConnect();
		$this->dbConn = $db->connect();
	}

	public function getAllCustomers(){
		$stmt = mysqli_query($this->dbConn, "SELECT * FROM " .$this->tableName);
		$customers = mysqli_fetch_assoc($stmt);
		return $customers;
	}
	public function insert(){
		$sql = "INSERT INTO `$this->tableName` SET `name` = '$this->name', `email` = '$this->email', `address` = '$this->address', `mobile` = '$this->mobile', `updatedBy` = '$this->updatedBy', `updatedOn` = '$this->updatedOn', `createdBy` = '$this->createdBy', `createdOn` = '$this->createdOn' ";
		$query = mysqli_query($this->dbConn, $sql);
		if($query){
			return true;
		}else{
			return false;
		}
	}
}

?>