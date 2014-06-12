<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Akhirnya setelah lama, Stocks gua putuskan untuk berdiri sendiri.
 * Ini sudah diusulkan sejak lama, tapi setelah dipikir-pikir, dia cukup
 * kuat untuk menjadi dasar. Purchasing itu ngga cukup basic untuk dijadikan dasar
 *
 * Secara keseluruhan Stock berfungsi untuk monitoring persediaan barang.
 * Namun untuk dapat dimonitoring, produk yang dijual harus di add dulu di stocks
 * Baru kemudian dapat di monitor.
 *
 * Stocks_model yang sudah terbuat rencananya akan dijadikan dasar untuk Stocks yang
 * memiliki fungsi multiple gudang. 
 *
 *
 *
 *
 */ 
class Stocks extends Admin_Controller {

    /**
     *
     */
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('stocks_model');
        $this->load->model('purchasing/po_model');

		$this->load->language('module', 'english');

        /* set user for the stocks transaction */
        $this->stocks_model->user($this->ion_auth->user()->row()->id);

        /* set only use master stock */
        $this->stocks_model->select('master_stock');
    }



    /**
     * there are 3 functions in this section.
     *
     * 1. showing the stock list
     * 2. showint the sotck card
     * 3. receive generated stock in
     *
     * We combine these functions because 
     */
    function index($start=NULL, $end=NULL, $warehouse_id=1, $code=NULL) {

        /* First time go here set the time */
        if ($start == NULL) {
            $start = date('Y-m-d', strtotime('-7 day'));
            $end = date('Y-m-d', strtotime('+1 day'));

            /* forward the messages */
            $messages = $this->session->flashdata('messages');
            $this->session->set_flashdata('messages', $messages);

            redirect (current_url().'/index/'.$start.'/'.$end.'/1');
        }

        /* Ok check the input */
        if ($this->input->post()) {

            /* Checking are there arival  first */
            if ($r = $this->input->post('r')) {

                $date = date ('Y-m-d H:i:s');
                $messages = '<div class="nNote nSuccess"><p>Barang masuk :</p>';

                foreach (array_keys($r) as $ri) {
                    $product = $this->po_model->arrive($ri, $date);
                    if ($product != NULL) {
                        $po_id = $this->po_model->po_line_get_po_id($ri);
                        $this->stocks_model->link_with('po_line',$ri)
                            ->in( $product->code, $product->quantity, "Purchase Order no $po_id");
                        $messages .= '<p>'.$this->stocks_model->get_name ($product->code).
                            '('.$product->code.') sebanyak '.$product->quantity.'</p>';
                    }
                }

                $messages .= '</div>';
                $this->session->set_flashdata('messages', $messages);
                redirect (current_url());
            }

            /* Ok, just view */
            redirect (site_url('stocks/index/'.
                date ('Y-m-d', strtotime($this->input->post('start'))).'/'.
                date ('Y-m-d', strtotime($this->input->post('end'))).'/'.
                '1/'.
                $this->input->post('code')
            ));
        }

        /* Initialize data */
        $data = array();

        $start = strtotime ($start);
        $end   = strtotime($end);
        $data['start'] = date('d M Y', $start); 
        $data['end'] = date('d M Y', $end); 

        /* Set the warehouse that use */
        $s = $this->stocks_model->select ($warehouse_id);
        $data['messages'] = $this->session->flashdata('messages');

        /* check wether card or list */
        if ($code == NULL) {
            /* Showing Stock List */
            $data['title'] = "Stocks List";
            $data['table'] = $this->po_model->notyet_arrived();
            $data['stock_list'] = $s->remaining_product_table($start,$end);
            $this->load->vars($data);
            $this->template->render_page('main','list_view');
        } else {
            /* Showing Stock Card */
            $data['title'] = "Stock Card";
            $data['code'] = $code;
            $data['name'] = $s->get_name($code);
            $data['table'] = $s->get_card($code, $start, $end);
            $this->load->vars($data);
            $this->template->render_page('main','card_view');
        }
    }


	private $rules = array(
		array('field' => 'code',         'label' => 'Product\'s Code', 'rules'  => 'required|xss_clean'),
		array('field' => 'low',          'label' => 'Low limits', 'rules'  => 'required|xss_clean'),
	);

    /**
     * CRUD
     *
     */
	public function add()
	{
		//validate form input
		$this->form_validation->set_rules($this->rules);
		
		if ($this->form_validation->run($this) == TRUE)
		{
			//if form validation success

			$post = array(
                'warehouse_id'  => 1,
				'code'			=> $this->input->post('code'),
				'low'	        => $this->input->post('low'),
			);
			
			$this->stocks_model->insert($post);
			
			$this->session->set_flashdata('message', '<div class="nNote nSuccess"><p>Item successfully created.</p></div>');
			redirect("stocks", 'refresh');
		} 
		else
		{	
			//if form validation error
			//display the create user form
			//set the flash data error message if there is one
			$data['messages'] = (validation_errors() ? '<div class="nNote nFailure">'.validation_errors().'</div>'  : $this->session->flashdata('message'));
			
			$data['code'] = array(
				'name'  => 'code',
				'type'  => 'text',
                'list'  => 'code',
				'value' => $this->form_validation->set_value('code'),
			);
			$data['low'] = array(
				'name'  => 'low',
				'id'    => 'low',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('low'),
			);

            $this->load->model ('product/product_model');

			//set the view
			$this->template
				->set('title', 'Add Stocks Item')
                ->set('product', $this->product_model->get_all())
				->set('data', $data)
				->render_page('main', 'add_view');
		}
	}
	
	public function edit($id)
	{
		
        $stocks = $this->stocks_model->get_item_by_id($id);
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
                'warehouse_id'  => 1,
				'code'			=> $this->input->post('code'),
				'low'	        => $this->input->post('low'),
			);
			
			$this->stocks_model->update($id, $post);
			
			$this->session->set_flashdata('message', '<div class="nNote nSuccess"><p>Stocks item saved.</p></div>');
			redirect("stocks", 'refresh');
		}
		else
		{
			//display the edit user form
			
			$data['csrf'] = $this->_get_csrf_nonce();
			
			//set the flash data error message if there is one
			$data['messages'] = (validation_errors() ? '<div class="nNote nFailure">'.validation_errors().'</div>' : $this->session->flashdata('message'));
			
			//pass the data to the view
			$data['stocks'] = $stocks;
            $data['id'] = $id;
			
			$data['code'] = array(
				'name'  => 'code',
                'list'  => 'code',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('code', $stocks->code),
			);
			$data['low'] = array(
				'name'  => 'low',
				'id'    => 'low',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('min_amount', $stocks->low),
			);

			//set the view
			$this->template
				->set('title', 'Edit Stocks Item')
				->set('data', $data)
				->render_page('main', 'edit_view');
		}
	}
	
	public function delete($id)
	{
		$this->stocks_model->delete($id);
		$this->session->set_flashdata('messages', '<div class="nNote nSuccess"><p>Stocks Item not monitored.</p></div>');
		redirect("stocks", 'refresh');
	}

    /**
     * stock in 
     */
    function in($code='') {
        /* Process input */
        if ($post = $this->input->post()) {

            /* Process Stock In */
            $this->stocks_model->select('master_stock')->in($post['code'], $post['quantity'], $post['description']);

            $messages = '<div class="nNote nSuccess"><p>Barang masuk :'.
                $this->stocks_model->get_name ($post['code']).
                '('.$post['code'].') sebanyak '.$post['quantity']. '</p></div>';
            $this->session->set_flashdata('messages', $messages);

            redirect (site_url('stocks'));
        }

        $data = array (
            'title'    => "Stock In",
            'messages' => $this->session->flashdata('messages'),
            'code'     => $code,
            'product' => $this->stocks_model->get_items(),
            'button_name' => 'Masukkan',
        );

        $this->load->vars($data);
        $this->template->render_page('main','inout_view');
    }

    /**
     * stock out 
     */
    function out($code='') {

        if ($post = $this->input->post()) {

            /* process stock out */
            $return = $this->stocks_model->select('master_stock')->out($post['code'], $post['quantity'], $post['description']);

            $messages = '<div class="nNote nSuccess"><p>Barang Keluar :'.
                $this->stocks_model->get_name ($post['code']).
                '('.$post['code'].') sebanyak '.$post['quantity']. '</p></div>'.$return;

            $this->session->set_flashdata('messages', $messages);
            redirect (site_url('stocks'));
        }

        $data = array (
            'title' => "Stock Out",
            'messages' => $this->session->flashdata('messages'),
            'code'     => $code,
            'product' => $this->stocks_model->get_items(),
            'button_name' => 'Keluarkan',
        );

        $this->load->vars($data);
        $this->template->render_page('main','inout_view');
    }

    /**
     * cancel in out and it's link
     */
    function cancel($id) {
        try {
            $this->stocks_model->cancel($id);
            $this->session->set_flashdata('messages', '<div class="nNote nSuccess"><p>Data berhasil di hapus</p></div>');
        } catch (Exception $e) {
            $this->session->set_flashdata ('messages', '<div class="nNote nFailure"><p>'.$e->getMessage().'</p></div>');
        } 
        redirect($_SERVER['HTTP_REFERER'],'refresh');
    }

    function arrive () {

    }

    /**
     * del notification
     */
    function del_request($id) {
        $this->stocks_model->del_stockout_pending($id);
        redirect('/stocks/out');
    }

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
