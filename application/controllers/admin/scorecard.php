<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scorecard extends CI_Controller {
	
        function __construct(){
                parent::__construct();
                $m_mahasiswa = $this->load->model('admin/m_mahasiswa');
                $m_krs = $this->load->model('admin/m_krs');
                $m_khs = $this->load->model('admin/m_khs');
        }
        function index()
        {
        	$log = $this->checkLogin();
        	
        	if($log == true){
        		$this->load->view('admin/general/header');
        		$this->load->view('admin/general/sidebar');
        		$this->load->view('admin/scorecard/body');
        		$this->load->view('admin/general/script');
        		$this->load->view('admin/scorecard/script');
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
        
        function search(){
        	$data = $this->m_mahasiswa->getMhs($_POST['in-nim']);
//         	print_r($data);exit;
        	$html = '';
        	$html .= '<table class="table table-hover table-striped" id="table-search-result">';
        	foreach ($data as $key => $val){
        		
        		foreach ($val as $key2 => $val2){
        			$html .= '<tr>';
        			$html .= '<td width="100px">'.$key2.'</td>'.'<td width="10px"> : </td><td id="res-'.$key2.'">'.$val2.'</td>';
        			$html .= '</tr>';
//         			$html .= "<td>";
        		}
        	}
        	$html .= '</table>';
        	echo $html;
        }
        
        function ajaxLoadTabel(){
        	$data = $this->m_khs->getKhs($_POST);
        	
        	$html = '';
        	$html .= '<table class="table table-bordered table-hover table-striped datatable" id="table">';
            $html .= '<thead>';
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
							"<button class='btn btn-info btn-xs' onclick='editMK(".$val['NIM'].")'><i class='fa fa-fw fa-search'></i> Detail</button> ".
							"<button class='btn btn-warning btn-xs' onclick='editMK(".$val['NIM'].")'><i class='fa fa-fw fa-pencil'></i> Edit</button> ";
				$html .= 	"<button class='btn btn-danger btn-xs' onclick='editMK(".$val['NIM'].")'><i class='fa fa-fw fa-remove'></i> Hapus</button> ".
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