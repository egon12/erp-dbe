<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Table of Content
==================================================
	1.	CRUD
	2.	Datatables
	3.	Security
==================================================*/


/*==================================================
	1.	CRUD
==================================================*/

Class Vendors extends Admin_Controller {
	
	public function __construct()
	{
        parent::__construct();

		$this->load->model('vendors/vendors_model');
		$this->load->language('module', 'english');
    }
	
	private $rules = array(
		array('field' => 'name',        'label' => 'Name', 'rules'  => 'required|xss_clean'),
		array('field' => 'address',     'label' => 'Address', 'rules'  => 'required|xss_clean'),
		array('field' => 'phone',       'label' => 'Phone', 'rules'  => 'required|xss_clean'),
		array('field' => 'email',       'label' => 'Email', 'rules'  => 'xss_clean'),
		array('field' => 'description', 'label' => 'Description', 'rules'  => 'xss_clean')
	);
	
	public function index()
	{
		$data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

		$this->template
			->set('title', 'Products')
			->set('data', $data)
			->render_page('main', 'vendors/index');
	}
	
	public function add()
	{
		//validate form input
		$this->form_validation->set_rules($this->rules);
		
		if ($this->form_validation->run($this) == TRUE)
		{
			//if form validation success

			$post = array(
				'name'			=> $this->input->post('name'),
				'address'		=> $this->input->post('address'),
				'phone'			=> $this->input->post('phone'),
				'email'			=> $this->input->post('email'),
				'description'	=> $this->input->post('description')
			);
			
			$this->vendors_model->insert($post);
			
			$this->session->set_flashdata('message', '<div class="nNote nSuccess"><p>Product successfully created.</p></div>');
			redirect("vendors", 'refresh');
		} 
		else
		{	
			//if form validation error
			//display the create user form
			//set the flash data error message if there is one
			$data['message'] = (validation_errors() ? '<div class="nNote nFailure">'.validation_errors().'</div>'  : $this->session->flashdata('message'));
			
			$data['name'] = array(
				'name'  => 'name',
				'id'    => 'name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('name'),
			);
			$data['address'] = array(
				'name'  => 'address',
				'id'    => 'address',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('address'),
			);
			$data['phone'] = array(
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone'),
			);
			$data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
			$data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);

			//set the view
			$this->template
				->set('title', 'Add Vendors')
				->set('data', $data)
				->render_page('main', 'vendors/add');
		}
	}
	
	public function edit($id)
	{
		$vendor = $this->vendors_model->get_item_by_id($id);
		
		//validate form input
		$this->form_validation->set_rules($this->rules);
		
		if ($this->form_validation->run($this) == TRUE)
		{
			//if form validation success
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
				exit();
			}
			
			$post = array(
				'name'			=> $this->input->post('name'),
				'address'		=> $this->input->post('address'),
				'phone'			=> $this->input->post('phone'),
				'email'			=> $this->input->post('email'),
				'description'	=> $this->input->post('description')
			);
			
			
			$this->vendors_model->update($id, $post);
			
			$this->session->set_flashdata('message', '<div class="nNote nSuccess"><p>Vendor saved.</p></div>');
			redirect("vendors", 'refresh');
		}
		else
		{
			//display the edit user form
			
			$data['csrf'] = $this->_get_csrf_nonce();
			
			//set the flash data error message if there is one
			$data['message'] = (validation_errors() ? '<div class="nNote nFailure">'.validation_errors().'</div>' : $this->session->flashdata('message'));
			
			//pass the data to the view
			$data['vendor'] = $vendor;
			
			$data['name'] = array(
				'name'  => 'name',
				'id'    => 'name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('name', $vendor->name),
			);
			$data['address'] = array(
				'name'  => 'address',
				'id'    => 'address',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('address', $vendor->address),
			);
			$data['phone'] = array(
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone', $vendor->phone),
			);
			$data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('email', $vendor->email),
			);
			$data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description', $vendor->description),
			);

			//set the view
			$this->template
				->set('title', 'Edit Vendors')
				->set('data', $data)
				->render_page('main', 'vendors/edit');
		}
	}
	
	public function delete($id)
	{
		$name = $this->vendors_model->get_item_by_id($id)->name;
		$this->vendors_model->delete($id);
		$this->session->set_flashdata('message', '<div class="nNote nSuccess"><p>Vendors "'.$name.'" deleted.</p></div>');
		redirect("vendors", 'refresh');
	}

/*==================================================
	2.	Datatables
==================================================*/
	
	public function datatables()
	{
		//variable initialization
		$search = "";
		$start = 0;
		$offset = 10;

		//get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}

		//limit
		$start = $this->get_start();
		$offset = $this->get_offset();

		//sort
		$sort_dir = $this->get_sort_dir();

		//run query to get user listing
		$customer_listing = $this->vendors_model->listing($start, $offset, $search, $this->get_sort(), $sort_dir);
		$iFilteredTotal = $this->vendors_model->search($search)->num_rows();
		
		$iTotal = $this->vendors_model->count_all();

        /*
         * Output
         */
         $output = array(
             "sEcho" => intval($_GET['sEcho']),
             "iTotalRecords" => $iTotal,
             "iTotalDisplayRecords" => $iFilteredTotal,
             "aaData" => array()
         );

        //get result after running query and put it in array
        foreach ($customer_listing->result() as $row) {
			$record = array();
			
			$record['id'] = $row->id; //required!
			$record['name'] = $row->name;
			$record['address'] = $row->address;
			$record['phone'] = $row->phone;
			$record['email'] = $row->email;
			$record['description'] = $row->description;

			$output['aaData'][] = $record;
		}
		//format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
		//echo '<pre>', print_r($customer_listing), '</pre>';
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
		//list your entities for db search, based your column order in datatables
		$cols = array( "name", "address", "phone", "email" );

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
