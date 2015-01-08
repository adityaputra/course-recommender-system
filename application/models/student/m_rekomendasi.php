<?php
class M_Rekomendasi extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function getCbMkKhs($params){
		$statement = '
				SELECT TBL_MK.K_MK, TBL_MK.THN_MK, TBL_MK.K_JURUSAN, TBL_MK.K_FAKULTAS, TBL_MK.K_JENJANG,
							(coalesce(case TBL_KHS.M_NILAI_K_NILAI when NULL then 0 ELSE TBL_KHS.M_NILAI_K_NILAI end,"0")) as K_NILAI 
						FROM 
							(
								SELECT * FROM MATA_KULIAH 
									WHERE K_JURUSAN = "'.$params['K_JURUSAN'].'" 
									AND K_FAKULTAS = "'.$params['K_FAKULTAS'].'" 
									AND K_JENJANG = "'.$params['K_JENJANG'].'"
							)
							AS TBL_MK 
						LEFT JOIN 
							(
								SELECT * FROM MHS_KHS 
								WHERE NIM = "'.$params['NIM'].'"
							)
							AS TBL_KHS 
						ON TBL_MK.K_MK = TBL_KHS.K_MK 
						AND TBL_MK.THN_MK = TBL_KHS.THN_MK 
				;
				';
// 		$statement = 'SELECT * FROM MATA_KULIAH MK LEFT JOIN MHS_KHS KHS
// 						ON MK.K_MK = KHS.K_MK
// 						AND MK.THN_MK = KHS.THN_MK
// 						AND MK.K_JURUSAN = "'.$params['K_JURUSAN'].'"
// 						AND MK.K_FAKULTAS = "'.$params['K_FAKULTAS'].'"
// 						AND MK.K_JENJANG = "'.$params['K_JENJANG'].'"
// 						AND KHS.NIM = "'.$params['NIM'].'"';
// 		echo $statement;exit;
		$result = $this->db->query($statement);
		return $result->result_array();
	}
	
	function getCbMkKhsKomp($params){
		$statement = '
				SELECT 
				 MK.K_MK,
				 MK.THN_MK, 
				 KOMP.U1,
				 KOMP.U2,
				 KOMP.U3,
				 KOMP.U4,
				 KOMP.U5,
				 KOMP.U6,
				 KOMP.U7,
				 MK.K_NILAI,
				 MK.K_JURUSAN AS MK_K_JURUSAN,
				 MK.K_FAKULTAS AS MK_K_FAKULTAS,
				 MK.K_JENJANG AS MK_K_JENJANG,
				 KOMP.K_PROG_STUDI AS KOMP_K_PROG_STUDI,
				 KOMP.K_JURUSAN AS KOMP_K_JURUSAN,
				 KOMP.K_FAKULTAS AS KOMP_K_FAKULTAS,
				 KOMP.K_JENJANG AS KOMP_K_JENJANG
				FROM 
					(
						SELECT TBL_KHS.NIM, TBL_MK.K_MK, TBL_MK.THN_MK, TBL_MK.K_JURUSAN, TBL_MK.K_FAKULTAS, TBL_MK.K_JENJANG,
							(coalesce(case TBL_KHS.M_NILAI_K_NILAI when NULL then 0 ELSE TBL_KHS.M_NILAI_K_NILAI end,"0")) as K_NILAI 
						FROM 
							(
								SELECT * FROM MATA_KULIAH 
									WHERE K_JURUSAN = "'.$params['K_JURUSAN'].'" 
									AND K_FAKULTAS = "'.$params['K_FAKULTAS'].'" 
									AND K_JENJANG = "'.$params['K_JENJANG'].'"
							)
							AS TBL_MK 
						LEFT JOIN 
							(
								SELECT * FROM MHS_KHS 
								WHERE NIM = "'.$params['NIM'].'"
							)
							AS TBL_KHS 
						ON TBL_MK.K_MK = TBL_KHS.K_MK 
						AND TBL_MK.THN_MK = TBL_KHS.THN_MK 
						
					) AS MK
					
					LEFT JOIN (
						SELECT K_MK, THN_MK, K_PROG_STUDI, K_JURUSAN, K_FAKULTAS, K_JENJANG,
							max(coalesce(case k_kompetensi when "U1" then is_kompetensi end,"-")) as U1,
							max(coalesce(case k_kompetensi when "U2" then is_kompetensi end,"-")) as U2,
							max(coalesce(case k_kompetensi when "U3" then is_kompetensi end,"-")) as U3,
							max(coalesce(case k_kompetensi when "U4" then is_kompetensi end,"-")) as U4,
							max(coalesce(case k_kompetensi when "U5" then is_kompetensi end,"-")) as U5,
							max(coalesce(case k_kompetensi when "U6" then is_kompetensi end,"-")) as U6,
							max(coalesce(case k_kompetensi when "U7" then is_kompetensi end,"-")) as U7
						FROM MK_KOMPETENSI
						WHERE K_PROG_STUDI = "'.$params['K_PROG_STUDI'].'"
						AND K_JURUSAN = "'.$params['K_JURUSAN'].'"
						AND K_FAKULTAS = "'.$params['K_FAKULTAS'].'"
						AND K_JENJANG = "'.$params['K_JENJANG'].'"
						group by (k_mk)
					) AS KOMP
						ON MK.K_MK = KOMP.K_MK
						AND MK.THN_MK = KOMP.THN_MK		
				';
// 		echo $statement;exit;
		$result = $this->db->query($statement);
		return $result->result_array();
	}
	
	
	
}
?>