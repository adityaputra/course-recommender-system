<?php
class M_Mahasiswa extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function getMhs($param){
		if($param == 'x' || $param == 'X'){
			$statement = 'SELECT * FROM MHS';
		}
		else{
			$statement = 'SELECT * FROM MHS WHERE NIM = "'.$param.'"';
		}
		$result = $this->db->query($statement);
		return $result->result_array();
	}
	
	
	
}
?>