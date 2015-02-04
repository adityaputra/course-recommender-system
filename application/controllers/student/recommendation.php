<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recommendation extends CI_Controller {
	
        function __construct(){
                parent::__construct();
                $m_mk = $this->load->model('student/m_mk');
                $m_khs = $this->load->model('student/m_khs');
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
        
        function ucf(){
        	$log = $this->checkLogin();
        
        	if($log == true){
        		$this->load->view('student/general/header');
        		$this->load->view('student/general/sidebar');
        		$this->load->view('student/recommendation/ucf/body');
        		$this->load->view('student/general/script');
        		$this->load->view('student/recommendation/ucf/script');
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
		//========ITEM BASED COLLABORATIVE FILTERING============================================================================================		
		//======================================================================================================================================		
		function getIcfRekomendasi(){
			// param{
			// in: nim
			// }
			//
			// get mhs info: fakultas-prodi
			$student = $this->getMhsInfo();
			
			
			//tabel relasi mhs-mk-tempuh
			$mkKhsTempuh = $this->getIcfMkKhsTempuh($student);
// 			print_r($mkKhsTempuh);exit;
			
			/* 
			//-----------------------------------
			//transformasi array menjadi dua dimensi
			$mkKhsTempuhTrans = $this->getIcfTransformMkKhsTempuh($mkKhsTempuh);
// 			print_r($deviasi);exit;
			
			// generating array of deviation (tabel 3.6)			
			$deviasi = $this->getIcfTabelDeviasi($mkKhsTempuhTrans);
// 			print_r($deviasi);exit;
			
			$enc = json_encode($deviasi);
			//----------------------------------
			 */
			
			//file read & write path
			$dirDev = FCPATH."public/files/rec/icf/deviasi/";
			$filenameDevArr = $student['K_JURUSAN'].$student['K_FAKULTAS'].$student['K_JENJANG']."-arr.dat";
			$filenameDevJson = $student['K_JURUSAN'].$student['K_FAKULTAS'].$student['K_JENJANG']."-json.dat";
			
			//write to file, uncomment this to generate new deviation files
// 			$writeDevArr = file_put_contents($dirDev.$filenameDevArr, serialize($deviasi));
// 			$writeDevJson = file_put_contents($dirDev.$filenameDevJson, $enc);
// 			echo $writeDevArr."-".$writeDevJson;exit;

			// get file data deviasi yang sudah di generate dari file
			$deviasiArr = unserialize(file_get_contents($dirDev.$filenameDevArr));
// 			print_r($deviasiArr); exit;
			
// 			$deviasiJson = json_decode(file_get_contents($dirDev.$filenameDevJson), true);
// 			print_r($deviasiJson);

			$deviasiCrossArr = $this->getIcfTabelDeviasiCrossed($deviasiArr);
// 			print_r($deviasiCrossArr);exit;

			$dirDevCross = FCPATH."public/files/rec/icf/deviasi-cross/";
			$filenameDevCrossArr = $student['K_JURUSAN'].$student['K_FAKULTAS'].$student['K_JENJANG']."-arr.dat";
			
			//write to file, uncomment this to generate new deviation files
// 			$writeDevCrossArr = file_put_contents($dirDevCross.$filenameDevCrossArr, serialize($deviasiCrossArr));
// 			echo $writeDevCrossArr;exit;

			$devCrossArr = unserialize(file_get_contents($dirDevCross.$filenameDevCrossArr));
// 			print_r($deviasiCrossArr);exit;

			//get array of user average rating
// 			$userAverageRating = $this->getIcfUserAverageRating($deviasiArr);
// 			print_r($userAverageRating); exit;

			//get available course
			$courses = $this->m_mk->getMKAvailable($student);
// 			print_r($courses); exit;

			//get array of similarity
			$similarityArr = $this->getIcfSimilarityTable($devCrossArr, $courses);
// 			print_r($similarityArr); exit;

			//simulasi perhitungan similarity antar dua item
// 			$similarityEx = $this->getIcfSimilarity(array('IFK15001', '0'), array('IFK15003', '0'), $devCrossArr);
// 			echo $similarityEx;

			//get mata kuliah yang belum ditempuh
			$coursesTawar = $this->m_mk->getMKTawar($student);
// 			print_r($coursesTawar);

			//get khs matakuliah student
			$coursesKhs = $this->m_khs->getAllKhs($student);
// 			print_r($coursesKhs); exit;

			//get average item rating
			$avgItemsRating = $this->getIcfAvgItemsRating($mkKhsTempuh);
// 			print_r($avgItemsRating); exit;
			
			//menghitung prediksi
			$predictionTableWei = $this->getIcfPredictionTable($coursesTawar, $coursesKhs, $similarityArr, $avgItemsRating, 'weightedsum');
			$predictionTableHer = $this->getIcfPredictionTable($coursesTawar, $coursesKhs, $similarityArr, $avgItemsRating, 'herlocker');
// 			print_r($predictionTable);
			
			//simulasi perhitungan prediksi penempuhan matakuliah oleh mahasiswa
			
			//representasi prediksi dalam tabel
			$html = $this->getIcfPredictionTableHtml($predictionTableWei, "Tabel Prediksi ICF - Weighted Sum", "tabel-prediksi-icf-wei");
			echo $html;
			$html = $this->getIcfPredictionTableHtml($predictionTableHer, "Tabel Prediksi ICF - Herlocker", "tabel-prediksi-icf-her");
			echo $html;
			
			exit;
			
		}
		
		function getIcfMkKhsTempuh($student){
			return $this->m_rekomendasi->getIcfMkKhsTempuh($student);
		}
		
		function getIcfTransformMkKhsTempuh($mkKhsTempuh){
			$deviasi = array();
			foreach ($mkKhsTempuh as $key => $value){
				// 				$tmp = array();
				$deviasi[$value['NIM']][$value['K_MK']][$value['THN_MK']]['IS_TEMPUH'] = $value['IS_TEMPUH'];
			}
			return $deviasi;
		}
		
		function getIcfTabelDeviasi($deviasi){
// 			print_r($deviasi); exit;
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
			return $deviasi;
		}
		
		function getIcfTabelDeviasiCrossed($deviasiArr){
// 			print_r($deviasiArr);exit;
			$deviasiCrossArr = array();
			foreach ($deviasiArr as $key => $value){
				foreach ($value as $key2 => $value2){
					foreach ($value2 as $key3 => $value3){
// 						$deviasiCrossArr[$key2][$key3][$key] = $value3['DEVIASI'];
						$deviasiCrossArr[$key][$key2][$key3] = $value3['DEVIASI'];
					}
				}
			}
			return $deviasiCrossArr;
		}
		
		function getIcfUserAverageRating($arr){
// 			print_r($arr); exit;

			$userAverageRating = array();
			foreach ($arr as $key => $value){
				foreach ($value as $key2 => $value2){
					foreach ($value2 as $key3 => $value3){
						$userAverageRating[$key] = $value3['AVGRATING'];
						break;
					}
					break;
				}
			}
			return $userAverageRating;
		}
		
		function getIcfSimilarityTable($arr, $course){
			$similarity = array();
			foreach ($course as $key => $value){
				foreach ($course as $key2 => $value2){
					if($key2 < $key){
						continue;
					}
// 					$similarity[$key][$key2] = $this->getIcfSimilarity(
					$similarity[$value['K_MK']."-".$value['THN_MK']][$value2['K_MK']."-".$value2['THN_MK']]
						= $this->getIcfSimilarity(
							array($value['K_MK'], $value['THN_MK']), 
							array($value2['K_MK'], $value2['THN_MK']), 
							$arr
						);
// 					echo $key . ", " . $key2. " = " . $similarity[$key][$key2] . "<br/>";
				}
			}
// 			print_r($similarity); exit;
			return $similarity;
		}
		
		function getIcfSimilarity($item1, $item2, $arr){
// 			print_r($arr); exit;
			$sum1 = 0;
			$sum2 = 0;
			$sum3 = 0;
			$sum4 = 0;
			
			$i = 0;
			foreach ($arr as $key => $value){
				$sum1 = $sum1 + ($value[$item1[0]][$item1[1]] * $value[$item2[0]][$item2[1]]);
				$sum2 = $sum2 + (($value[$item1[0]][$item1[1]]) * ($value[$item1[0]][$item1[1]]));
				$sum3 = $sum3 + (($value[$item2[0]][$item2[1]]) * ($value[$item2[0]][$item2[1]]));
				$i++;
				if($i==2){
// 					$sim = ($sum1) / (sqrt($sum2) * sqrt($sum3));
// 					echo $sum1." / "."( sqrt".($sum2) ." * sqrt". ($sum3)." ) = ". $sim. "<br/>"; exit;
					break;
				}
				
			}
			$sim = ($sum1) / (sqrt($sum2) * sqrt($sum3));
// 			echo $sum1." / "."( sqrt".($sum2) ." * sqrt". ($sum3)." ) = ". $sim. "<br/>"; exit;
			return $sim;
		}
		
		function  getIcfPredictionTable($coursesTawar, $coursesKhs, $similarityTable, $avgItemsRating, $type){
// 			print_r($similarityTable); exit;
			
			$predictionTable = array();
			foreach ($coursesTawar as $key => $value){
				if($type == 'weightedsum') $pred = $this->getIcfPredictionWeightedSum($value, $coursesKhs, $similarityTable);
				else if($type == 'herlocker') $pred = $this->getIcfPredictionHerlocker($value, $coursesKhs, $similarityTable, $avgItemsRating);
// 				echo $pred; exit;
				$predictionTable[$value['K_MK']][$value['THN_MK']]['PRED'] = $pred;
			}
// 			print_r($predictionTable); exit;
			return $predictionTable;
		}
		
		function getIcfPredictionWeightedSum($mkTawar, $courseKhs, $similarityTable){
// 			print_r($similarityTable); exit;

// 			$mkTawar['K_MK'] = 'IFK15011';
			$sum1 = 0;
			$sum2 = 0;
			foreach ($courseKhs as $key => $value){
// 				foreach ($similarityTable as $key2 => $value2){
// 					if ($similarityTable)
					// weighted sum = sigma(Sin * Run), Run = 1
					
					if($mkTawar['K_MK']."-".$value['THN_MK'] == $value['K_MK']."-".$value['THN_MK']) continue;

					if (isset($similarityTable[$mkTawar['K_MK']."-".$value['THN_MK']][$value['K_MK']."-".$value['THN_MK']])){
						if($similarityTable
								[$mkTawar['K_MK']."-".$value['THN_MK']]
								[$value['K_MK']."-".$value['THN_MK']] >= 0){
							
							$sum1 = $sum1 +
								($similarityTable
									[$mkTawar['K_MK']."-".$value['THN_MK']]
									[$value['K_MK']."-".$value['THN_MK']]
									* 1);
							$sum2 = $sum2 +
							abs($similarityTable
									[$mkTawar['K_MK']."-".$value['THN_MK']]
									[$value['K_MK']."-".$value['THN_MK']]);
// 													echo $mkTawar['K_MK']."-".$value['THN_MK'] . " --- " . $value['K_MK']."-".$value['THN_MK']
// 														." = (".
// 														$similarityTable
// 														[$mkTawar['K_MK']."-".$value['THN_MK']]
// 														[$value['K_MK']."-".$value['THN_MK']]
// 														. ")<br/>";
// 													echo $sum1."<br/>";
						}
						
						
					}
					else if (isset($similarityTable[$value['K_MK']."-".$value['THN_MK']][$mkTawar['K_MK']."-".$value['THN_MK']])){
						if($similarityTable
								[$value['K_MK']."-".$value['THN_MK']]
								[$mkTawar['K_MK']."-".$value['THN_MK']] >= 0){
							
							$sum1 = $sum1 +
								($similarityTable
									[$value['K_MK']."-".$value['THN_MK']]
									[$mkTawar['K_MK']."-".$value['THN_MK']]
									* 1);
							$sum2 = $sum2 +
								abs($similarityTable
									[$value['K_MK']."-".$value['THN_MK']]
									[$mkTawar['K_MK']."-".$value['THN_MK']]);
// 							echo $value['K_MK']."-".$value['THN_MK'] . " --- " . $mkTawar['K_MK']."-".$value['THN_MK']
// 							." = (".
// 							$similarityTable
// 							[$value['K_MK']."-".$value['THN_MK']]
// 							[$mkTawar['K_MK']."-".$value['THN_MK']]
// 							. ")<br/>";
// 							echo $sum1."<br/>";
						}
						
					}
// 				}
			}
			$pred = $sum1 / $sum2;
// 			echo $sum1 . " / " . $sum2 . " = " . $pred; exit;
			return $pred;
		}
		
		function getIcfPredictionHerlocker($mkTawar, $courseKhs, $similarityTable, $avgItemsRating){
// 						print_r($similarityTable); exit;
		
			// 			$mkTawar['K_MK'] = 'IFK15011';
			$sum1 = 0;
			$sum2 = 0;
			foreach ($courseKhs as $key => $value){
				// 				foreach ($similarityTable as $key2 => $value2){
				// 					if ($similarityTable)
				// weighted sum = sigma(Sin * (Run - avg(Rn))), Run = 1
					
				if($mkTawar['K_MK']."-".$value['THN_MK'] == $value['K_MK']."-".$value['THN_MK']) continue;
		
				if (isset($similarityTable[$mkTawar['K_MK']."-".$value['THN_MK']][$value['K_MK']."-".$value['THN_MK']])){
					if($similarityTable
							[$mkTawar['K_MK']."-".$value['THN_MK']]
							[$value['K_MK']."-".$value['THN_MK']] >= 0){
							
						$sum1 = $sum1 +
						($similarityTable
								[$mkTawar['K_MK']."-".$value['THN_MK']]
								[$value['K_MK']."-".$value['THN_MK']]
								* (1 - ($avgItemsRating[$value['K_MK']][$value['THN_MK']]['AVG'])));
						$sum2 = $sum2 +
						abs($similarityTable
								[$mkTawar['K_MK']."-".$value['THN_MK']]
								[$value['K_MK']."-".$value['THN_MK']]);
// 													echo $mkTawar['K_MK']."-".$value['THN_MK'] . " --- " . $value['K_MK']."-".$value['THN_MK']
// 														." = (".
// 														$similarityTable
// 														[$mkTawar['K_MK']."-".$value['THN_MK']]
// 														[$value['K_MK']."-".$value['THN_MK']]
// 														. " - ". $avgItemsRating[$value['K_MK']][$value['THN_MK']]['AVG'] .")<br/>";
// 													echo $sum1."<br/>";
					}
		
		
				}
				else if (isset($similarityTable[$value['K_MK']."-".$value['THN_MK']][$mkTawar['K_MK']."-".$value['THN_MK']])){
					if($similarityTable
							[$value['K_MK']."-".$value['THN_MK']]
							[$mkTawar['K_MK']."-".$value['THN_MK']] >= 0){
							
						$sum1 = $sum1 +
						($similarityTable
								[$value['K_MK']."-".$value['THN_MK']]
								[$mkTawar['K_MK']."-".$value['THN_MK']]
								* (1 - ($avgItemsRating[$value['K_MK']][$value['THN_MK']]['AVG'])));
						$sum2 = $sum2 +
						abs($similarityTable
								[$value['K_MK']."-".$value['THN_MK']]
								[$mkTawar['K_MK']."-".$value['THN_MK']]);
// 													echo $value['K_MK']."-".$value['THN_MK'] . " --- " . $mkTawar['K_MK']."-".$value['THN_MK']
// 													." = (".
// 													$similarityTable
// 													[$value['K_MK']."-".$value['THN_MK']]
// 													[$mkTawar['K_MK']."-".$value['THN_MK']]
// 													. " - ". $avgItemsRating[$value['K_MK']][$value['THN_MK']]['AVG'] .")<br/>";
// 													echo $sum1."<br/>";
					}
		
				}
				// 				}
			}
// 			exit;
			$pred = $avgItemsRating[$mkTawar['K_MK']][$mkTawar['THN_MK']]['AVG'] +($sum1 / $sum2);
			// 			echo $sum1 . " / " . $sum2 . " = " . $pred; exit;
			return $pred;
		}
		
		function getIcfAvgItemsRating($mkKhsTempuh){
			$avgRating = array();
			foreach ($mkKhsTempuh as $key => $value){
				if(!isset($avgRating[$value['K_MK']][$value['THN_MK']]['SUM'])){ $avgRating[$value['K_MK']][$value['THN_MK']]['SUM'] = 0;}
				if(!isset($avgRating[$value['K_MK']][$value['THN_MK']]['COUNT'])){ $avgRating[$value['K_MK']][$value['THN_MK']]['COUNT'] = 0;}
				if(!isset($avgRating[$value['K_MK']][$value['THN_MK']]['AVG'])){ $avgRating[$value['K_MK']][$value['THN_MK']]['AVG'] = 0;}
				$avgRating[$value['K_MK']][$value['THN_MK']]['SUM'] = $avgRating[$value['K_MK']][$value['THN_MK']]['SUM'] + $value['IS_TEMPUH'];
				$avgRating[$value['K_MK']][$value['THN_MK']]['COUNT'] = $avgRating[$value['K_MK']][$value['THN_MK']]['COUNT'] + 1;
				$avgRating[$value['K_MK']][$value['THN_MK']]['AVG'] = $avgRating[$value['K_MK']][$value['THN_MK']]['SUM'] / $avgRating[$value['K_MK']][$value['THN_MK']]['COUNT'];
			}
// 			print_r($avgRating);exit;
			return $avgRating;
		}
		
		function getIcfPredictionTableHtml($data, $title, $id){
        	$html = '';
        	$html .= '<hr/><h4>'.$title.'</h4><br/>';
        	$html .= '<table class="table table-bordered table-hover table-striped datatable" id="'.$id.'">';
        	$html .= '<thead>';
        	$html .= '<tr>';
        	$html .= '<td>NO</td>';
        	$html .= '<td>K_MK</td>';
        	$html .= '<td>THN_MK</td>';
        	$html .= '<td>PREDIKSI</td>';
        	$html .= '<td>ACTIONS</td>';
        	$html .= '</tr>';
        	$html .= '</thead>';
        	
        	$i = 0;
        	foreach ($data as $key => $val){
        		foreach ($val as $key2 => $val2){
        			$i++;
        			$html .= '<tr>';
        			$html .= '<td>'.$i.'</td>';
        			$html .= '<td>'.$key.'</td>';
        			$html .= '<td>'.$key2.'</td>';
        			$html .= '<td>'.$val2['PRED'].'</td>';
        			$html .= '<td>'.'</td>';
        			$html .= '</tr>';
        		}
        		
        	}
        		
        	$html .= '</tbody>';
        	$html .= '</table>';
//         	echo $html; exit;
        	return $html;
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