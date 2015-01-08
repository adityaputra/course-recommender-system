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
        
        function cf(){
        	$log = $this->checkLogin();
        	 
        	if($log == true){
        		$this->load->view('student/general/header');
        		$this->load->view('student/general/sidebar');
        		$this->load->view('student/recommendation/cf/body');
        		$this->load->view('student/general/script');
        		$this->load->view('student/recommendation/cf/script');
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
        
        function getCbRekomendasi(){
        	// param{
        	// in: nim
	        // }
	        //
	       	// get mhs info: fakultas-prodi
	       	$student = $this->getMhsInfo();
        	
        	// get MK yang bisa ditempuh mhs join khs, menampilkan semua mata kuliah yang bisa diambil beserta data apakah mk tersebut ditempuh
        	$mkKhs = $this->getCbMkKhs($student);//print_r($mkKhs);exit;
//         	echo ($this->getGenericTable($mkKhs, 'table-mk-khs', 'Tabel Matriks MK - KHS'));
        	
	       	// pembentukan matriks
	       	$mkKhsKomp = $this->getCbMkKhsKomp($student);
	       	$decisionTree = $this->getCbDecisionTree($mkKhsKomp);
// 	       	echo ($this->getGenericTable($mkKhsKomp, 'table-mk-khs-komp', 'Tabel Matriks MK - KHS - KOMPETENSI'));
	       	
	       	// pembentukan rules
        	
        	
        }
        
        function getMhsInfo(){
        	return ($this->session->userdata('student_detail')) ;
        }
        
        function getCbMkKhs($student){
        	return $this->m_rekomendasi->getCbMkKhs($student);
        }
        
        function getCbMkKhsKomp($student){
        	return $this->m_rekomendasi->getCbMkKhsKomp($student);
        }
        
        function getCbDecisionTree($data){
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
        		$temp['K_NILAI'] = $val['K_NILAI'];
        		array_push($tree1, $temp);
        		
//         		$tree2[$val['U1']][$val['U2']][$val['U3']][$val['U4']][$val['U5']][$val['U6']][$val['U7']] = $val['K_NILAI'];
        		$tree2[$val['U1']][$val['U2']][$val['U3']][$val['U4']][$val['U5']][$val['U6']][$val['U7']] = 0;
//         		array_push($tree2[$val['U1']][$val['U2']][$val['U3']][$val['U4']][$val['U5']][$val['U6']][$val['U7']], 1);
        		
        	}
        	
        	foreach ($data as $key => $val){
        		$tree2[$val['U1']][$val['U2']][$val['U3']][$val['U4']][$val['U5']][$val['U6']][$val['U7']] = $tree2[$val['U1']][$val['U2']][$val['U3']][$val['U4']][$val['U5']][$val['U6']][$val['U7']] + 1;
        	}
        	
        	print_r($tree2);exit;
        	return $tree;
        }
        
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