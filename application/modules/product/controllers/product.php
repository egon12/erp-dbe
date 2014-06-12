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

Class Product extends Admin_Controller {
	
	public function __construct()
	{
        parent::__construct();

		$this->load->model('product/product_model');
		$this->load->language('module', 'english');

        $user_group = $this->ion_auth->get_users_groups()->row()->name;
        if ('owner' == $user_group || 'administrator' == $user_group) {

        } else {
            redirect ('/');
        }
    }
	
	private $rules = array(
		array('field' => 'code', 'label' => 'Code', 'rules'  => 'required|xss_clean'),
		array('field' => 'name', 'label' => 'Name', 'rules'  => 'required|xss_clean'),
		array('field' => 'price', 'label' => 'Price', 'rules'  => 'required|xss_clean'),
		array('field' => 'description', 'label' => 'Description', 'rules'  => 'xss_clean')
	);
	
	public function index()
	{
		$data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

		$this->template
			->set('title', 'Products')
			->set('data', $data)
			->render_page('main', 'product/index');
	}
	
	public function add()
	{
		//validate form input
		$this->form_validation->set_rules($this->rules);
		
		if ($this->form_validation->run($this) == TRUE)
		{
			//if form validation success

			$post = array(
				'code'			=> $this->input->post('code'),
				'name'			=> $this->input->post('name'),
				'description'	=> $this->input->post('description')
			);
			
			if ($this->input->post('price')) {
				
				//remove commas from numeric strings
				$price = $this->input->post('price');
				if (preg_match("/^[0-9,]+$/", $price)) $price = str_replace(',', '', $price);
				$post['price'] = $price;
			}
			
			$this->product_model->insert($post);
			
			$this->session->set_flashdata('message', '<div class="nNote nSuccess"><p>Product successfully created.</p></div>');
			redirect("product", 'refresh');
		} 
		else
		{	
			//if form validation error
			//display the create user form
			//set the flash data error message if there is one
			$data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
			
			$data['code'] = array(
				'name'  => 'code',
				'id'    => 'code',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('code'),
			);
			$data['name'] = array(
				'name'  => 'name',
				'id'    => 'name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('name'),
			);
			$data['price'] = array(
				'name'  => 'price',
				'id'    => 'price',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('price'),
			);
			$data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);

			//set the view
			$this->template
				->set('title', 'Add Product')
				->set('data', $data)
				->render_page('main', 'product/add');
		}
	}
	
	public function edit($id)
	{
		
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
				'code'			=> $this->input->post('code'),
				'name'			=> $this->input->post('name'),
				'description'	=> $this->input->post('description')
			);
			
			if ($this->input->post('price')) {
				
				//remove commas from numeric strings
				$price = $this->input->post('price');
				if (preg_match("/^[0-9,]+$/", $price)) $price = str_replace(',', '', $price);
				$post['price'] = $price;
			}
			
			$this->product_model->update($id, $post);
			
			$this->session->set_flashdata('message', '<div class="nNote nSuccess"><p>Product '.$post['name'].' is saved.</p></div>');
			redirect("product", 'refresh');
		}
		else
		{
			//display the edit user form
			
			$data['csrf'] = $this->_get_csrf_nonce();
			
			//set the flash data error message if there is one
			$data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
			
            // get the product
            $product = $this->product_model->get_item_by_id($id);

			//pass the data to the view
			$data['product'] = $product;
			
			$data['code'] = array(
				'name'  => 'code',
				'id'    => 'code',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('code', $product->code),
			);
			$data['name'] = array(
				'name'  => 'name',
				'id'    => 'name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('name', $product->name),
			);
			$data['price'] = array(
				'name'  => 'price',
				'id'    => 'price',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('price', number_format($product->price)),
			);
			$data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description', $product->description),
			);

			//set the view
			$this->template
				->set('title', 'Edit Product')
				->set('data', $data)
				->render_page('main', 'product/edit');
		}
	}
	
	public function delete($id)
	{
		$name = $this->product_model->get_item_by_id($id)->name;
		$this->product_model->delete($id);
		$this->session->set_flashdata('message', '<div class="nNote nSuccess"><p>Product "'.$name.'" deleted.</p></div>');
		redirect("product", 'refresh');
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
		$customer_listing = $this->product_model->listing($start, $offset, $search, $this->get_sort(), $sort_dir);
		$iFilteredTotal = $this->product_model->search($search)->num_rows();
		
		$iTotal = $this->product_model->count_all();

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
			$record['code'] = $row->code;
			$record['name'] = $row->name;
			$record['price'] = number_format($row->price);
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
		$cols = array( "code", "name", "price", "description" );

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
