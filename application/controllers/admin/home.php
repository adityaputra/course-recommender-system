<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

        /**
         * Index Page for this controller.
         *
         * Maps to the following URL
         *              http://example.com/index.php/welcome
         *      - or -  
         *              http://example.com/index.php/welcome/index
         *      - or -
         * Since this controller is set as the default controller in 
         * config/routes.php, it's displayed at http://example.com/
         *
         * So any other public methods not prefixed with an underscore will
         * map to /index.php/welcome/<method_name>
         * @see http://codeigniter.com/user_guide/general/urls.html
         */
        function __construct(){
                parent::__construct();
//                 $m_mahasiswa = $this->load->model('m_mahasiswa');
        }
        function index()
        {
        	$log = $this->checkLogin();
        	
        	if($log == true){
        		$this->load->view('admin/general/header');
        		$this->load->view('admin/general/sidebar');
        		$this->load->view('admin/general/body');
        		$this->load->view('admin/general/script');
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
}
?>