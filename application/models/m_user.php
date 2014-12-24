<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_User extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	
	function login($username, $password){
		$statement = "SELECT USERNAME, LEVEL FROM USER WHERE USERNAME='".$username."' AND PASSWORD='".$password."'";
// 		echo $statement;exit;
		$result = $this->db->query($statement);
		return $result->result_array();
	}
	
}
?>