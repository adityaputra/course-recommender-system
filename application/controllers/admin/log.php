<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log extends CI_Controller {

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
            $m_user = $this->load->model('m_user');
        }
        function index()
        {
        	$this->load->view('admin/login');
        }
        
        public function in(){
        	$access = $this->m_user->login($_POST['inputUsername'], md5($_POST['inputPassword']));
//         	print_r($access);exit;
        	if(count($access) == 0){
        		$this->load->view('admin/login');
        	}
        	else if(count($access) == 1){
        		$userdata = array(
        				'admin_username'  => $access[0]['USERNAME'],
        				'admin_level'     => $access[0]['LEVEL'],
        				'admin_logged_in' => TRUE
        		);
        		$this->session->set_userdata($userdata);
        		redirect('admin/home');
        	}
        	
        }
        
        public function out(){
        	$this->session->sess_destroy();
        	redirect('admin/log');
        }
}
?>