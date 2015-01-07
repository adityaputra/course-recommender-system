<?php
class M_Rekomendasi extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function getCbMkKhs($params){
		$statement = 'SELECT * FROM MATA_KULIAH MK LEFT JOIN MHS_KHS KHS
						ON MK.K_MK = KHS.K_MK
						AND MK.THN_MK = KHS.THN_MK
						AND MK.K_FAKULTAS = (SELECT K_FAKULTAS FROM MHS WHERE MHS.NIM = "105060804111008"")
						AND KHS.NIM = "105060804111008"';
		
		$result = $this->db->query($statement);
		return $result->result_array();
	}
	
	
	
}
?>