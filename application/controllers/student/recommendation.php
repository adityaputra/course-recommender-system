<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recommendation extends CI_Controller {
	
        function __construct(){
                parent::__construct();
                $m_mk = $this->load->model('student/m_mk');
                $m_mahasiswa = $this->load->model('student/m_mahasiswa');
                $m_rekomendasi = $this->load->model('student/m_rekomendasi');
        }
        function index()
        {
        	$this->cbf();
        }
        
        function checkLogin(){
        	if($this->session->userdata('student_logged_in') == TRUE){
        		return true;
        	}
        	else{
        		return false;
        	}
        }
        
        function cbf(){
        	$log = $this->checkLogin();
        	 
        	if($log == true){
        		$this->load->view('student/general/header');
        		$this->load->view('student/general/sidebar');
        		$this->load->view('student/recommendation/cbf/body');
        		$this->load->view('student/general/script');
        		$this->load->view('student/recommendation/cbf/script');
        		$this->load->view('student/general/footer');
        	}
        	else {
        		redirect('student/log');
        	}        	
        }
        
        function icf(){
        	$log = $this->checkLogin();
        	 
        	if($log == true){
        		$this->load->view('student/general/header');
        		$this->load->view('student/general/sidebar');
        		$this->load->view('student/recommendation/icf/body');
        		$this->load->view('student/general/script');
        		$this->load->view('student/recommendation/icf/script');
        		$this->load->view('student/general/footer');
        	}
        	else {
        		redirect('student/log');
        	}
        }
        
        function ajaxLoadTabelMK(){
        	
        	$html = '';
        	$html .= '<table class="table table-bordered table-hover table-striped datatable" id="table">';
            $html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th>Kode MK</th>';
			$html .= '<th>Tahun MK</th>';
			$html .= '<th>Nama MK</th>';
			$html .= '<th>Jurusan</th>';
			$html .= '<th>Fakultas</th>';
			$html .= '<th>Jenjang</th>';
			$html .= '<th>Actions</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			
			$data = $this->m_mk->getMK('x');
			
			foreach ($data as $key => $val){
				$html .= '<tr>';
				foreach ($val as $key2 => $val2){	
					$html .= '<td>'.$val2.'</td>';
				}
				$html .= "<td>".
							"<button class='btn btn-info btn-xs' onclick='editMK(".$val['K_MK'].", ".$val['THN_MK'].")'><i class='fa fa-fw fa-search'></i> Detail</button> ";
				$html .= "</td>";
				$html .= '</tr>';
			}
			
			                            /*
                                <tbody>
                                    <tr>
                                        <td>/index.html</td>
                                        <td>1265</td>
                                        */
			$html .= '</tbody>';
			$html .= '</table>';
			
			
			echo $html;
//         	print_r($data);exit;
        }
        
        //======================================================================================================================================
        //===================CONTENT BASED FILTERING==========================================================================================
        //======================================================================================================================================        
        
        function getCbRekomendasi(){
        	// param{
        	// in: nim
	        // }
	        //
	       	// get mhs info: fakultas-prodi
	       	$student = $this->getMhsInfo();
        	
        	// get MK yang bisa ditempuh, baik yang pernah diambil maupun belum.
        	// mhs join khs, menampilkan semua mata kuliah yang bisa diambil beserta data apakah mk tersebut ditempuh
//         	$mkKhs = $this->getCbMkKhs($student);
//         	print_r($mkKhs);exit;
//         	echo ($this->getGenericTable($mkKhs, 'table-mk-khs', 'Tabel Matriks MK - KHS'));

        	// filter untuk hanya mengambil MK yang sudah ditempuh
//         	$mkKhs = $this->getCbFilterMkTempuh($mkKhs, 1);
        	
        	// -----------------------------------------------------------------------------
        	
	       	// pembentukan matriks
	       	$mkKhsKomp = $this->getCbMkKhsKomp($student);
	       	$mkKhsKompTempuh = $this->getCbFilterMkTempuh($mkKhsKomp, 1);
// 	       	echo "mhskomp".count($mkKhsKomp)."<br/>"; print_r($mkKhsKomp);exit;
// 	       	print_r($mkKhsKompTempuh);
// 	       	exit;
	       	
	       	//membentuk decision tree
	       	$decisionTree = $this->getCbDecisionTree($mkKhsKomp);
// 	       	echo "decisionTree".count($decisionTree)."<br/>"; print_r($decisionTree);exit;
// 	       	echo ($this->getGenericTable($mkKhsKomp, 'table-mk-khs-komp', 'Tabel Matriks MK - KHS - KOMPETENSI'));
	       	
	       	// pembentukan rules
	       	$rules = $this->getCbRules($decisionTree);
// 	       	echo "rules".count($rules)."<br/>"; print_r($rules);exit;
	       	
			//simplifying rules with entropies and information gaining
	       	$entropyS = $this->getCbEntropy($rules, 'ALL');
	       	//ini untuk perhitungan entropi pada sample dan kolom tertentu, dilakukan filtering sample
// 	       	$sample = $this->getCbSampleColumnFilter($rules, 'U4', 1);
// 			$entropyS = $this->getCbEntropy($sample);
			echo "entropyS".count($entropyS)."<br/>"; print_r($entropyS); exit;
        	
        	
        }
        
        function getMhsInfo(){
        	return ($this->session->userdata('student_detail')) ;
        }
        
        function getCbMkKhs($student){
        	return $this->m_rekomendasi->getCbMkKhs($student);
        }
        
        function getCbFilterMkTempuh($mkKhs, $tempuh){
        	$data = array();
        	if($tempuh == 1){ // yang sudah ditempuh
	        	foreach ($mkKhs as $key => $val){ 
	        		if($val['K_NILAI'] != '0'){
	        			array_push($data, $val);
	        		}
	        	}
        	}
        	else if($tempuh == 0){ // belum ditempuh
        		foreach ($mkKhs as $key => $val){
        			if($val['K_NILAI'] == '0'){
        				array_push($data, $val);
        			}
        		}  
        	}
//         	print_r($mkKhs);exit;
//         	print_r($data);exit;
        	return $data;
        }
        
        function getCbMkKhsKomp($student){
        	return $this->m_rekomendasi->getCbMkKhsKomp($student);
        }
        
        function getCbDecisionTree($data){
        	/*
        	 * function get decision tre content based
        	 * merubah struktur data menjadi x-dimensional
        	 * nilai x bergantung pada jumlah kompetensi lulusan
        	 */
        	$tree1 = array();
        	$tree2 = array();
        	foreach ($data as $key => $val){
        		$temp = array();
        		$temp['U1'] = $val['U1'];
        		$temp['U2'] = $val['U2'];
        		$temp['U3'] = $val['U3'];
        		$temp['U4'] = $val['U4'];
        		$temp['U5'] = $val['U5'];
        		$temp['U6'] = $val['U6'];
        		$temp['U7'] = $val['U7'];
				$temp['IS_TEMPUH'] = $val['IS_TEMPUH'];
        		array_push($tree1, $temp);
        		
				$tree2[$val['IS_TEMPUH']][$val['U1']][$val['U2']][$val['U3']][$val['U4']][$val['U5']][$val['U6']][$val['U7']] = 0;
				
        	}
        	
        	foreach ($data as $key => $val){
        		$tree2[$val['IS_TEMPUH']][$val['U1']][$val['U2']][$val['U3']][$val['U4']][$val['U5']][$val['U6']][$val['U7']] = $tree2[$val['IS_TEMPUH']][$val['U1']][$val['U2']][$val['U3']][$val['U4']][$val['U5']][$val['U6']][$val['U7']] + 1;
        	}
        	
        	return $tree2;
        }
        
        function getCbRules($rules){
        	/*
        	 * function get rules content-based
        	 * melakukan sorting jumlah leaf, untuk menentukan rules yang paling dominan
        	 * melakukan merge kombinasi node dari tree dan 
        	 * menghitung jumlah leaf dari masing-masing kombinasi node yang sama
        	 */
        	
//			sorting, in fact it is no use, karena sudah ter sort otomatis         	
			ksort($rules);
// 			print_r($rules); exit;
			

			$denormalizedRules = array();
			
			foreach ($rules as $keynil => $nil){
				foreach ($nil as $key => $val){
					foreach ($val as $key2 => $val2){
						foreach ($val2 as $key3 => $val3){
							foreach ($val3 as $key4 => $val4){
								foreach ($val4 as $key5 => $val5){
									foreach ($val5 as $key6 => $val6){
										foreach ($val6 as $key7 => $val7){
											$tempArr["IS_TEMPUH"] = $keynil;
											$tempArr["U1"] = $key;
											$tempArr["U2"] = $key2;
											$tempArr["U3"] = $key3;
											$tempArr["U4"] = $key4;
											$tempArr["U5"] = $key5;
											$tempArr["U6"] = $key6;
											$tempArr["U7"] = $key7;
											$tempArr["COUNT"] = $val7;
											
											array_push($denormalizedRules, $tempArr);
										}
									}
								}
							}
						}
					}
				}
			}
			
			return ($denormalizedRules);
        }
        
		function getCbEntropy($sample){
			// rumus: entropy(s) = sigma(i,c) (-((peluang i).(log2 peluang i)))
// 			print_r($sample);exit;

			$entropyS = 0;
			
			$uniqueRules = count($sample); 


			$countS = 0;
			$countTarget0 = 0;
			$countTarget1 = 0;
			
			// menghitung total rules
			foreach ($sample as $key => $value){
				$countS = $countS + $value['COUNT'];
				if($value['IS_TEMPUH'] == 0) $countTarget0 = $countTarget0 + $value['COUNT'];
				if($value['IS_TEMPUH'] == 1) $countTarget1 = $countTarget1 + $value['COUNT'];
			}
// 			echo $countS."-".$countTarget0."-".$countTarget1;exit;			
			
			//menghitung entropi
// 			$countS = 11;
// 			$countTarget0 = 3;
// 			$countTarget1 = 8;
			$entropyS = - (($countTarget0 / $countS) * log(($countTarget0 / $countS), 2)) - (($countTarget1 / $countS) * log(($countTarget1 / $countS), 2));
			return $entropyS; 
			
			
		}
		
		function getCbSampleColumnFilter($sample, $targetColumn, $targetValue){
			
			$filteredSample = array();
			foreach ($sample as $key => $value){
				if($value[$targetColumn] == $targetValue){
					array_push($filteredSample, $value);
				}
			}
			return $filteredSample;
		}

		
		//======================================================================================================================================
		//===================COLLABORATIVE FILTERING============================================================================================		
		//======================================================================================================================================		
		function getIcfRekomendasi(){
			// param{
			// in: nim
			// }
			//
			// get mhs info: fakultas-prodi
			$student = $this->getMhsInfo();
			
			//tabel relasi mhs-mk-tempuh
			$mkKhsProdi = $this->getIcfMkKhsProdi($student);
// 			print_r($mkKhsProdi);exit;
			
			//transformasi array menjadi dua dimensi
			$deviasi = array();
			foreach ($mkKhsProdi as $key => $value){
// 				$tmp = array();
				$deviasi[$value['NIM']][$value['K_MK']][$value['THN_MK']]['IS_TEMPUH'] = $value['IS_TEMPUH'];
				
			}
			
// 			print_r($deviasi);exit;
			
			$arrSumCountAvg = array();
			
			foreach ($deviasi as $key=>$value){
				$sumRating = 0;
				$countRating = 0;
				foreach ($value as $key2 => $value2){
					foreach ($value2 as $key3 => $value3){
						$sumRating = $sumRating + $value3['IS_TEMPUH'];
						$countRating++;
					}
				}
				$arrSumCountAvg[$key]['SUMRATING'] = $sumRating;
				$arrSumCountAvg[$key]['COUNTRATING'] = $countRating;
				$arrSumCountAvg[$key]['AVGRATING'] = $sumRating / $countRating;
			}
// 			print_r($arrSumCountAvg);exit;

			foreach ($deviasi as $key=>$value){
				$sumRating = 0;
				$countRating = 0;
				foreach ($value as $key2 => $value2){
					foreach ($value2 as $key3 => $value3){
						$deviasi[$key][$key2][$key3]['AVGRATING'] = $arrSumCountAvg[$key]['AVGRATING'];
						$deviasi[$key][$key2][$key3]['DEVIASI'] = $value3['IS_TEMPUH'] - $arrSumCountAvg[$key]['AVGRATING'];
					}
				}
			}
			
			print_r($deviasi);exit;
			
		}
		
		function getIcfMkKhsProdi($student){
			return $this->m_rekomendasi->getIcfMkKhsProdi($student);
		}
		
		
		//======================================================================================================================================
		//===================GENERAL============================================================================================================		
		//======================================================================================================================================
		
        function getGenericTable($data, $id, $title){
        	$html = '';
        	$html .= '<hr/><h4>'.$title.'</h4><br/>';
        	$html .= '<table class="table table-bordered table-hover table-striped datatable" id="'.$id.'">';
        	$html .= '<thead>';
        	$html .= '<tr>';
        	foreach ($data[0] as $key => $val){
        		$html .= '<td>'.$key.'</td>';
        	}
        	$html .= '</tr>';
        	$html .= '</thead>';
        	
        	foreach ($data as $key => $val){
        		$html .= '<tr>';
        		foreach ($val as $key2 => $val2){
        			$html .= '<td>'.$val2.'</td>';
        		}
        		$html .= '</tr>';
        	}
        		
        	$html .= '</tbody>';
        	$html .= '</table>';
        		
        	return $html;
        }
        
}
?>