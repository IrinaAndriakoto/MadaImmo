<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */

	public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('login_md'); 
        $this->load->helper('url');
        date_default_timezone_set('UTC');
    }

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function authenticate() {
        $nom = $this->input->post('nom');
        $pwd = $this->input->post('motdepasse');
        
        $user = $this->login_md->get_admin($nom, $pwd);
    
        if ($user) {
            $this->session->set_userdata('id',$user->id);
            $this->session->set_userdata('role', $user->role);
			$this->session->set_userdata('login',$user->login);
            $this->session->set_userdata('pwd', $user->pwd);
    
            if ($user->role == 'admin') {
                $data['role'] = $user->role;
                $data['pwd'] = $user->pwd;

                redirect('Welcome/admin');
            }
        } else {
			$this->session->set_flashdata('error', 'Nom d\'utilisateur, mot de passe ou numéro de téléphone incorrect.');
			redirect('Welcome/index');
        }
    }


	public function auth_client(){
        $login = $this->input->post('login');

        $user = $this->login_md->auth($login);
    
        if ($user) {
            $this->session->set_userdata('id',$user->id);
            $this->session->set_userdata('role', $user->role);
			$this->session->set_userdata('login',$user->login);
            $this->session->set_userdata('pwd', $user->pwd);
    
            if ($user->role == 'proprio') {
                $data['role'] = $user->role;
                $data['pwd'] = $user->pwd;

                redirect('Welcome/proprio');
            } else if ($user->role == 'client'){
				$data['role'] = $user->role;
                $data['pwd'] = $user->pwd;

                redirect('Welcome/client');
			}
        } else {
			$this->session->set_flashdata('error', 'Email ou numéro de téléphone incorrect.');
			redirect('Welcome/index');
        }
    }

	public function admin() {
		$role = $this->session->userdata('role');
		
		if ( $role == 'admin') {
			$this->load->view('accueil_admin');
		} else {
			redirect('Welcome/index');
		}
	}
	
	public function proprio(){
		$login = $this->session->userdata('login');
		$role = $this->session->userdata('role');
        $this->load->model('traitement_md');
        $data['biens'] = $this->traitement_md->getBiensById($login);

		if ( $role == 'proprio') {
			$this->load->view('accueil_proprio',$data);
		} else{
			redirect('Welcome/index');
		}
	}

	public function client(){
		$login = $this->session->userdata('login');
		$role = $this->session->userdata('role');

		$this->load->model('traitement_md');
        $data['biensss'] = $this->traitement_md->getBiensByClient($login);

		if ( $role == 'client') {
			$this->load->view('accueil_client',$data);
		} else{
			redirect('Welcome/index');
		}
	}

	public function logout() {
        $this->session->sess_destroy();
        redirect('Welcome/index');
    }

}
