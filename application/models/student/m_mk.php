<?php
class M_MK extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function getMK($param){
		if($param == 'x' || $param == 'X'){
			$statement = 'SELECT * FROM MATA_KULIAH';
		}
		else{
			$statement = 'SELECT * FROM MATA_KULIAH WHERE K_MK = "'.$param.'"';
		}
		$result = $this->db->query($statement);
		return $result->result_array();
	}
	
	function getMKKompetensi($param){
		if($param == 'x' || $param == 'X'){
			$statement = "SELECT K_MK, THN_MK, K_PROG_STUDI, K_JURUSAN, K_FAKULTAS, K_JENJANG,
						max(coalesce( case k_kompetensi when 'U1' then is_kompetensi end,'-')) as U1,
						max(coalesce(case k_kompetensi when 'U2' then is_kompetensi end,'-')) as U2,
						max(coalesce(case k_kompetensi when 'U3' then is_kompetensi end,'-')) as U3,
						max(coalesce(case k_kompetensi when 'U4' then is_kompetensi end,'-')) as U4,
						max(coalesce(case k_kompetensi when 'U5' then is_kompetensi end,'-')) as U5,
						max(coalesce(case k_kompetensi when 'U6' then is_kompetensi end,'-')) as U6,
						max(coalesce(case k_kompetensi when 'U7' then is_kompetensi end,'-')) as U7
						
						
						FROM MK_KOMPETENSI
						group by (k_mk)";
		}
		else{
			$statement = '';
		}
		$result = $this->db->query($statement);
		return $result->result_array();
		
	}
	
}
?>