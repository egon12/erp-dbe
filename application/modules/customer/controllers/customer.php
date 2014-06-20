<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Table of Content
==================================================
	1.	Customers CRUD
	2.	Datatables
	3.	Security
	4.  Another Function (todo apakah ini harusnya di sini?)	
==================================================*/


/*==================================================
	1.	Customers CRUD
==================================================*/

class Customer extends Admin_Controller {
	
    public function __construct()
    {
        parent::__construct();

        $this->load->model('customer/customer_model');
        $this->load->language('module', 'english');
    }

    private $rules = array(
        array('field' => 'name', 'label' => 'Name', 'rules'  => 'required|xss_clean'),
        array('field' => 'address', 'label' => 'Address', 'rules'  => 'xss_clean'),
        array('field' => 'phone', 'label' => 'Phone', 'rules'  => 'required|xss_clean'),
        array('field' => 'birth_place', 'label' => 'Birth Place', 'rules'  => 'xss_clean'),
        array('field' => 'birth_date', 'label' => 'Birth Date', 'rules'  => 'required|xss_clean|valid_date'),
        array('field' => 'description', 'label' => 'Description', 'rules'  => 'xss_clean')
    );

    public function index() {
        $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

        $this->template
            ->set('title', 'Customers')
            ->set('data', $data)
            ->render_page('main', 'customer/index');
    }

	public function add() {
		
		//validate form input
		$this->form_validation->set_rules($this->rules);
		
		if ($this->form_validation->run($this) == TRUE)
		{
			//if form validation success

			$post = array(
				'name'			=> $this->input->post('name'),
				'address'		=> $this->input->post('address'),
				'phone'			=> $this->input->post('phone'),
				'birth_place'	=> $this->input->post('birth_place'),
				'description'	=> $this->input->post('description')
			);
			
			if ($this->input->post('birth_date'))
			{
				// convert dd-mm-yyyy to yyyy-mm-dd
				$date_pieces = explode("-", $this->input->post('birth_date'));
				$post['birth_date'] = $date_pieces[2].'-'.$date_pieces[1].'-'.$date_pieces[0];
			}
			
			$this->customer_model->insert($post);
			
			$this->session->set_flashdata('message', '<div class="nNote nSuccess"><p>Customer <strong>"'.$post['name'].'"</strong> successfully inputed.</p></div>');
			redirect("customer/add", 'refresh');
		} 
		else
		{	
			//if form validation error
			//display the create user form
			//set the flash data error message if there is one
			$data['message'] = (validation_errors() ? '<div class="nNote nFailure">'.validation_errors().'</div>' : $this->session->flashdata('message'));
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
			$data['birth_place'] = array(
				'name'  => 'birth_place',
				'id'    => 'birth_place',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('birth_place'),
			);
			$data['age'] = array(
				'name'  => 'age',
				'id'    => 'age',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('age'),
			);
			$data['birth_date'] = array(
				'name'  => 'birth_date',
				'id'    => 'birth_date',
				'class' => 'datepicker',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('birth_date'),
			);
			$data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);

			//set the view
			$this->template
				->set('title', 'Add Customer')
				->set('data', $data)
				->render_page('main', 'customer/add');
		}
	}
	
	public function edit($id)
	{
		$customer = $this->customer_model->get_item_by_id($id);
		
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
				'birth_place'	=> $this->input->post('birth_place'),
				'description'	=> $this->input->post('description')
			);
			
			if ($this->input->post('birth_date'))
			{
				// convert dd-mm-yyyy to yyyy-mm-dd
				$date_pieces = explode("-", $this->input->post('birth_date'));
				$post['birth_date'] = $date_pieces[2].'-'.$date_pieces[1].'-'.$date_pieces[0];
			}
			
			$this->customer_model->update($id, $post);
			
			$this->session->set_flashdata('message', '<div class="nNote nSuccess"><p>Customer saved.</p></div>');
			redirect("customer", 'refresh');
		}
		else
		{
			//display the edit user form
			
			$data['csrf'] = $this->_get_csrf_nonce();
			
			//set the flash data error message if there is one
			$data['message'] = (validation_errors() ? '<div class="nNote nFailure">'.validation_errors().'</div>' : $this->session->flashdata('message'));
			
			//pass the data to the view
			$data['customer'] = $customer;
			$data['name'] = array(
				'name'  => 'name',
				'id'    => 'name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('name', $customer->name),
			);
			$data['address'] = array(
				'name'  => 'address',
				'id'    => 'address',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('address', $customer->address),
			);
			$data['phone'] = array(
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone', $customer->phone),
			);
			$data['birth_place'] = array(
				'name'  => 'birth_place',
				'id'    => 'birth_place',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('birth_place', $customer->birth_place),
			);
			$data['age'] = array(
				'name'  => 'age',
				'id'    => 'age',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('age'),
			);
			$data['birth_date'] = array(
				'name'  => 'birth_date',
				'id'    => 'birth_date',
				'class' => 'datepicker',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('birth_date', date("d-m-Y", strtotime($customer->birth_date))),
			);
			$data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description', $customer->description),
			);

			//set the view
			$this->template
				->set('title', 'Edit Customer')
				->set('data', $data)
				->render_page('main', 'customer/edit');
		}
	}
	
	public function delete($id)
	{
		$name = $this->customer_model->get_item_by_id($id)->name;
		$this->customer_model->delete($id);
		$this->session->set_flashdata('message', '<div class="nNote nSuccess"><p>Customer "'.$name.'" deleted.</p></div>');
		redirect("customer", 'refresh');
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
		$customer_listing = $this->customer_model->listing($start, $offset, $search, $this->get_sort(), $sort_dir);
		$iFilteredTotal = $this->customer_model->search($search)->num_rows();
		
		$iTotal = $this->customer_model->count_all();

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
			$record['birth_place'] = $row->birth_place;

			$output['aaData'][] = $record;
		}
		//format it to JSON, this output will be displayed in datatable
		echo json_encode($output);
		//echo '<pre>', print_r($customer_listing), '</pre>';
	}

	private function get_start() {
		$start = 0;
		if (isset($_GET['iDisplayStart'])) {
			$start = intval($_GET['iDisplayStart']);

			if ($start < 0)
				$start = 0;
		}

		return $start;
	}

	private function get_offset() {
		$offset = 10;
		if (isset($_GET['iDisplayLength'])) {
			$offset = intval($_GET['iDisplayLength']);
			if ($offset < 5 || $offset > 500) {
				$offset = 10;
			}
		}

		return $offset;
	}

	private function get_sort_dir() {
		$sort_dir = "ASC";
		$sdir = strip_tags($_GET['sSortDir_0']);
		if (isset($sdir)) {
			if ($sdir != "asc" ) {
				$sort_dir = "desc";
			}
		}

		return $sort_dir;
	}

	private function get_sort() {
		$sCol = $_GET['iSortCol_0'];
		$col = 0;
		//list your entities for db search, based your column order in datatables
		$cols = array( "id", "name", "address", "phone", "birth_place" );

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

/*=================================================
 *
 *
 ==================================================*/
    function view($customer_id) {
        $this->load->model('history_model');
        $data = $this->history_model->get($customer_id);
        $this->template
            ->set('title', 'Customer History')
            ->set('data', $data)
            ->render_page('main', 'view');
    }
}
