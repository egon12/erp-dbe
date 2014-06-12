<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Table of Content
==================================================
	1.	User CRUD
	2.	Datatables
	3.	Security
==================================================*/


/*==================================================
	1.	User CRUD
==================================================*/

class Users extends Admin_Controller {
	
	public function __construct()
	{
        parent::__construct();
		
		$this->load->model('auth/ion_auth_model');
		$this->load->model('users/users_model');
		$this->load->language('users', 'english');
        if ($this->ion_auth->get_users_groups()->row()->name != 'owner') {
            redirect ('/');
        }
    }
	
	public function index()
	{	
		$data['users'] = $this->ion_auth->users()->result();
		$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		
		foreach ($data['users'] as $key => $user)
		{
			$data['users'][$key]->groups = $this->ion_auth->get_users_groups($user->id)->result();
		}

		$this->template
			->set('title', 'User')
			->set('data', $data)
			->render_page('main', 'users/index');
	}
	
	public function add_user()
	{
		$groups = $this->ion_auth->groups()->result_array();
		
		//validate form input
		$rules_add = array(
			array('field' => 'username', 'label' => 'Username', 'rules'  => 'required|xss_clean'),
			array('field' => 'first_name', 'label' => 'First Name', 'rules'  => 'required|xss_clean'),
			array('field' => 'last_name', 'label' => 'Last Name', 'rules'  => 'xss_clean'),
			array('field' => 'phone', 'label' => 'Phone', 'rules'  => 'required|xss_clean'),
			array('field' => 'company', 'label' => 'Company', 'rules'  => 'required|xss_clean'),
			array('field' => 'email', 'label' => 'Email', 'rules'  => 'required|valid_email'),
			array('field' => 'password', 'label' => 'Password', 'rules'  => 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]'),
			array('field' => 'password_confirm', 'label' => 'Password Confirm', 'rules'  => 'required'),
			array('field' => 'groups', 'label' => 'Groups', 'rules'  => 'xss_clean')
		);
		
		$this->form_validation->set_rules($rules_add);
		
		if ($this->form_validation->run($this) == TRUE)
		{
			$username = $this->input->post('username');
			$email    = $this->input->post('email');
			$password = $this->input->post('password');

			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => $this->input->post('company'),
				'phone'      => $this->input->post('phone'),
			);
			
			$group = array($this->input->post('groups'));
			
			$this->ion_auth->register($username, $password, $email, $additional_data, $group);
			
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("users", 'refresh');
		}
		else
		{
			//display the create user form
			//set the flash data error message if there is one
			$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$data['groups'] = $groups;
			$data['username'] = array(
				'name'  => 'username',
				'id'    => 'username',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('username'),
			);
			$data['first_name'] = array(
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('first_name'),
			);
			$data['last_name'] = array(
				'name'  => 'last_name',
				'id'    => 'last_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('last_name'),
			);
			$data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
			$data['company'] = array(
				'name'  => 'company',
				'id'    => 'company',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('company'),
			);
			$data['phone'] = array(
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone'),
			);
			$data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$data['password_confirm'] = array(
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);

			// set the view
			$this->template
				->set('title', 'Add User')
				->set('data', $data)
				->render_page('main', 'users/add_user');
		}
	}
	
	public function edit_user($id)
	{
		$user = $this->ion_auth->user($id)->row();
		$groups = $this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();
		
		// if not redirect:
		// $message = '';
		
		// validate form input
		
		$rules_edit = array(
			array('field' => 'username', 'label' => 'Username', 'rules'  => 'required|xss_clean'),
			array('field' => 'first_name', 'label' => 'First Name', 'rules'  => 'required|xss_clean'),
			array('field' => 'last_name', 'label' => 'Last Name', 'rules'  => 'required|xss_clean'),
			array('field' => 'phone', 'label' => 'Phone', 'rules'  => 'required|xss_clean'),
			array('field' => 'company', 'label' => 'Company', 'rules'  => 'required|xss_clean'),
			array('field' => 'email', 'label' => 'Email', 'rules'  => 'required|xss_clean|valid_email'),
			array('field' => 'groups', 'label' => 'Groups', 'rules'  => 'xss_clean'),
		);
		
		$this->form_validation->set_rules($rules_edit);
		
		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}
			
			$post = array(
				'username' => $this->input->post('username'),
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => $this->input->post('company'),
				'phone'      => $this->input->post('phone'),
				'email'      => $this->input->post('email')
			);

			// Update the groups user belongs to
			
			$groupData = $this->input->post('groups');
			
			if (isset($groupData) && !empty($groupData)) {
				
				/* if you using multiple checkboxes OR select more than one group
				$this->ion_auth->remove_from_group('', $id);

				foreach ($groupData as $grp) {
					$this->ion_auth->add_to_group($grp, $id);
				}
				*/
				
				// if using simple dropdown
				$this->ion_auth->remove_from_group('', $id);
				$this->ion_auth->add_to_group($groupData, $id);
			}
			

			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$min_length = $this->config->item('min_password_length', 'ion_auth');
				$max_length = $this->config->item('max_password_length', 'ion_auth');
				
				$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $min_length . ']|max_length[' . $max_length . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', 'Password Confirm', 'required');

				$post['password'] = $this->input->post('password');
			}
			
			// form processing success
			if ($this->form_validation->run($this) === TRUE)
			{
				$this->ion_auth->update($user->id, $post);
				
				// if redirect use this:
				// check to see if we are creating the user
				// redirect them back to the admin page
				$this->session->set_flashdata('message', '<div class="nNote nSuccess"><p>User saved.</p></div>');
				redirect("users", 'refresh');
				
				// if not redirect:
				// $message = '<div class="nNote nSuccess"><p>User <strong>saved</strong>!</p></div>';
			}
		}
		
		/*	# Data for view ------------------------------------- */
		
		// display the edit user form
		$data['csrf'] = $this->_get_csrf_nonce();

		// set the flash data error message if there is one
		
		// if redirect use this:
		$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		
		// if not redirect:
		// $data['message'] = (validation_errors() ? validation_errors() : $message);

		// pass the data to the view
		$data['user'] = $user;
		$data['groups'] = $groups;
		$data['currentGroups'] = $currentGroups;
		
		$data['username'] = array(
			'name'  => 'username',
			'id'    => 'username',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('username', $user->username),
		);
		$data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		);
		$data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		);
		$data['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
		);
		$data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
		);
		$data['email'] = array(
			'name'  => 'email',
			'id'    => 'email',
			'type'  => 'email',
			'value' => $this->form_validation->set_value('email', $user->email),
		);
		$data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);
		
		// set the view
		$this->template
			->set('title', 'Edit User')
			->set('data', $data)
			->render_page('main', 'users/edit_user');
	}
	
	public function delete_user($id)
	{
		$user = $this->ion_auth->user($id)->row();
		$username = $user->username;
		$this->ion_auth->delete_user($id);
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect("users", 'refresh');
	}
	
/*==================================================
	2.	Datatables
==================================================*/
	
	public function datatables()
	{
		// variable initialization
		$search = "";
		$start = 0;
		$offset = 10;

		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}

		// limit
		$start = $this->get_start();
		$offset = $this->get_offset();

		// sort
		$sort_dir = $this->get_sort_dir();

		// run query to get user listing
		$user_listing = $this->users_model->listing($start, $offset, $search, $this->get_sort(), $sort_dir);
		$iFilteredTotal = $this->users_model->search($search)->num_rows();
		
		$iTotal = $this->users_model->count_all();

        /*
         * Output
         */
         $output = array(
             "sEcho" => intval($_GET['sEcho']),
             "iTotalRecords" => $iTotal,
             "iTotalDisplayRecords" => $iFilteredTotal,
             "aaData" => array()
         );

        // get result after running query and put it in array
        foreach ($user_listing->result() as $row) {
			$record = array();
			
			$role = $this->ion_auth->get_users_groups($row->id)->result();
			
			$record['id'] = $row->id; // required!
			$record['username'] = $row->username;
			$record['fullname'] = $row->first_name . ' ' . $row->last_name;
			$record['group'] = $row->name;
			$record['email'] = $row->email;
			$record['active'] = $row->active;

			$output['aaData'][] = $record;
		}
		// format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
		//echo '<pre>', print_r($user_listing), '</pre>';
	}

	public function get_start() {
		$start = 0;
		if (isset($_GET['iDisplayStart'])) {
			$start = intval($_GET['iDisplayStart']);

			if ($start < 0)
				$start = 0;
		}

		return $start;
	}

	public function get_offset() {
		$offset = 10;
		if (isset($_GET['iDisplayLength'])) {
			$offset = intval($_GET['iDisplayLength']);
			if ($offset < 5 || $offset > 500) {
				$offset = 10;
			}
		}

		return $offset;
	}

	public function get_sort_dir() {
		$sort_dir = "ASC";
		$sdir = strip_tags($_GET['sSortDir_0']);
		if (isset($sdir)) {
			if ($sdir != "asc" ) {
				$sort_dir = "desc";
			}
		}

		return $sort_dir;
	}

	public function get_sort() {
		$sCol = $_GET['iSortCol_0'];
		$col = 0;
		// list your entities for db search, based your column order in datatables
		$cols = array( "username", "first_name", "name", "email" );

		if (isset($sCol)) {
			$col = intval($sCol);
			if ($col < 0 || $col > 4)
				$col = 0;
		}
		$colName = $cols[$col];

		return $colName;
	}
	
/*==================================================
	3.	Security
==================================================*/
	
	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	public function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}
