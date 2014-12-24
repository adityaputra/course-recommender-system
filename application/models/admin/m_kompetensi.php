<?php
class M_Kompetensi extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function getKompetensi($param){
		if($param == 'x' || $param == 'X'){
			$statement = 'SELECT * FROM M_KOMPETENSI';
		}
		else{
			$statement = 'SELECT * FROM M_KOMPETENSI';
		}
		$result = $this->db->query($statement);
		return $result->result_array();
	}
	
}
?>