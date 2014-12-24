<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Competency extends CI_Controller {
	
        function __construct(){
                parent::__construct();
                $m_mk = $this->load->model('admin/m_mk');
                $m_kompetensi = $this->load->model('admin/m_kompetensi');
        }
        function index()
        {
        	$this->courses();
        }
        
        function courses(){
        	$log = $this->checkLogin();
        	 
        	if($log == true){
        		$this->load->view('admin/general/header');
        		$this->load->view('admin/general/sidebar');
        		$this->load->view('admin/competency/courses/body');
        		$this->load->view('admin/general/script');
        		$this->load->view('admin/competency/courses/script');
        		$this->load->view('admin/general/footer');
        	}
        	else {
        		redirect('admin/log');
        	}
        }
        
        function checkLogin(){
        	if($this->session->userdata('admin_logged_in') == TRUE){
        		return true;
        	}
        	else{
        		return false;
        	}
        }
        
        function master(){
        	$log = $this->checkLogin();
        
        	if($log == true){
        		$this->load->view('admin/general/header');
        		$this->load->view('admin/general/sidebar');
        		$this->load->view('admin/competency/master/body');
        		$this->load->view('admin/general/script');
        		$this->load->view('admin/competency/master/script');
        		$this->load->view('admin/general/footer');
        	}
        	else {
        		redirect('admin/log');
        	}
        }
        
        function ajaxLoadTabelCourses(){
        	
        	$html = '';
        	$html .= '<table class="table table-bordered table-hover table-striped datatable" id="table">';
            $html .= '<thead>';
			
			$data = $this->m_mk->getMKKompetensi('x');
			foreach ($data as $key => $val){
				$html .= '<tr>';
				foreach ($val as $key2 => $val2){
					$html .= '<th>'.$key2.'</th>';
				}
				$html .= '<th>Actions</th>';
				$html .= '</tr>';
				break;
			}
			$html .= '</thead>';
			
			foreach ($data as $key => $val){
				$html .= '<tr>';
				foreach ($val as $key2 => $val2){	
					$html .= '<td>'.$val2.'</td>';
				}
				$html .= "<td>".
							"<button class='btn btn-info btn-xs' onclick='editMK(".$val['K_MK'].", ".$val['THN_MK'].")'><i class='fa fa-fw fa-search'></i> Detail</button> ".
							"<button class='btn btn-warning btn-xs' onclick='editMK(".$val['K_MK'].", ".$val['THN_MK'].")'><i class='fa fa-fw fa-pencil'></i> Edit</button> ";
				$html .= 	"<button class='btn btn-danger btn-xs' onclick='editMK(".$val['K_MK'].", ".$val['THN_MK'].")'><i class='fa fa-fw fa-remove'></i> Hapus</button> ".
						 "</td>";
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
        
        function ajaxLoadTabelMaster(){
        	 
        	$html = '';
        	$html .= '<table class="table table-bordered table-hover table-striped datatable" id="table">';
        	$html .= '<thead>';
        		
        	$data = $this->m_kompetensi->getKompetensi('x');
        	foreach ($data as $key => $val){
        		$html .= '<tr>';
        		foreach ($val as $key2 => $val2){
        			$html .= '<th>'.$key2.'</th>';
        		}
        		$html .= '<th>Actions</th>';
        		$html .= '</tr>';
        		break;
        	}
        	$html .= '</thead>';
        		
        	foreach ($data as $key => $val){
        		$html .= '<tr>';
        		foreach ($val as $key2 => $val2){
        			$html .= '<td>'.$val2.'</td>';
        		}
        		$html .= "<td>".
        				"<button class='btn btn-info btn-xs' onclick='editMK(".$val['K_PROG_STUDI'].", ".$val['K_JURUSAN'].", ".$val['K_FAKULTAS'].", ".$val['K_JENJANG'].", ".$val['K_KOMPETENSI'].")'><i class='fa fa-fw fa-search'></i> Detail</button> ".
        				"<button class='btn btn-warning btn-xs' onclick='editMK(".$val['K_PROG_STUDI'].", ".$val['K_JURUSAN'].", ".$val['K_FAKULTAS'].", ".$val['K_JENJANG'].", ".$val['K_KOMPETENSI'].")'><i class='fa fa-fw fa-pencil'></i> Edit</button> ";
        		$html .= 	"<button class='btn btn-danger btn-xs' onclick='editMK(".$val['K_PROG_STUDI'].", ".$val['K_JURUSAN'].", ".$val['K_FAKULTAS'].", ".$val['K_JENJANG'].", ".$val['K_KOMPETENSI'].")'><i class='fa fa-fw fa-remove'></i> Hapus</button> ".
        				"</td>";
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
}
?>