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
	
	function getAllKhs($params){
		$statement = 'SELECT * FROM MHS_KHS WHERE NIM = "'.$params['NIM'].'"';
		$statement = 'SELECT * FROM MHS_KHS 
			WHERE NIM = "'.$params['NIM'].'"
			AND (K_MK, THN_MK) IN(
				SELECT K_MK, THN_MK FROM MATA_KULIAH
					WHERE K_JURUSAN = "'.$params['K_JURUSAN'].'"
					AND K_FAKULTAS = "'.$params['K_FAKULTAS'].'"
					AND K_JENJANG = "'.$params['K_JENJANG'].'"
			);';
// 		echo $statement; exit;
		$result = $this->db->query($statement);
		return $result->result_array();
	}
	
	
	
}
?>