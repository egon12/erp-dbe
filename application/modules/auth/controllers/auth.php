<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller 
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('auth/ion_auth');
		$this->load->library('form_validation');
		$this->load->helper('url');

		// Load MongoDB library instead of native db driver if required
		$this->config->item('use_mongodb', 'ion_auth') ?
		$this->load->library('mongo_db') :

		$this->load->database();

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
		$this->load->helper('language');
	}

	//redirect
	function index()
	{
		redirect('/');
	}

	//log the user in
	function login()
	{
		if($this->ion_auth->logged_in()) {
			redirect('/');
		}
		
		//validate form input
		$this->form_validation->set_rules('identity', 'Identity', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true)
		{
			//check to see if the user is logging in
			//check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());


                // go to referer
                // todo check this
                redirect($this->input->post('referrer'), 'refresh');
               
			}
			else
			{
				//if the login was un-successful
				//redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			//the user is not logging in so display the login page
			//set the flash data error message if there is one
			$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$data['identity'] = array('name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
				'class' => 'loginEmail',
				'placeholder' => 'Username'
			);
			
			$data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'class' => 'loginPassword',
				'placeholder' => 'Password'
			);

            /** this is for referer for fast login */
            // todo check is this work?
            $data['referrer'] = array(
                'name' => 'referrer',
                'id' => 'referrer',
                'type' => 'hidden',
                'value' => $this->session->flashdata('referer'),
            );
			
			$this->template
				->set('title', 'Login')
				->set('data', $data)
				->render_page('login', 'auth/login_form');
		}
	}

	//log the user out
	function logout()
	{
		//log the user out
		$logout = $this->ion_auth->logout();

		//redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('auth/login', 'refresh');
	}
}
