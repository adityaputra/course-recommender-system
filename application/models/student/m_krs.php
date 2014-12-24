<?php
class M_Krs extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function getKrs($param){
		if($param == 'x' || $param == 'X'){
			$statement = 'SELECT * FROM MHS';
		}
		else{
			$statement = 'SELECT * FROM MHS_KRS_KELAS WHERE MHS_NIM = "'.$param['in-nim'].'"
							 AND IS_PENDEK = "'.$param['in-pendek'].'"
							 AND TAHUN = "'.$param['in-tahun'].'"
							 AND IS_GANJIL = "'.$param['in-ganjil'].'"';
		}
// 		echo $statement;exit;
		$result = $this->db->query($statement);
		return $result->result_array();
	}
	
	
	
}
?>