<?php
class M_Khs extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function getKhs($param){
		if($param == 'x' || $param == 'X'){
			$statement = 'SELECT * FROM MHS_KHS';
		}
		else{
			$statement = 'SELECT * FROM MHS_KHS WHERE NIM = "'.$param['in-nim'].'"
							 AND SEMESTER = "'.$param['in-semester'].'"';
		}
// 		echo $statement;exit;
		$result = $this->db->query($statement);
		return $result->result_array();
	}
	
	
	
}
?>