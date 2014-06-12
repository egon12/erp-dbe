<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Point Of Sales
 *
 * This modules is to handle transaction. POS are the main Interface to
 * handle Transaction. It also check the flow of users. The Flow are created
 * to make job easier and faster
 *
 * ***CAREFULL***
 * In this Controller, we load some model. These model have same name in another
 * modules. The model are product_model, customer_model.
 *
 * PHP version 5
 * 
 * modules_dependency = customer, product
 *
 * @category
 * @package
 * @author      Egon Firman<egon.firman@gmail.com>"
 * @copyright
 * @license
 * @version
 * @link
 *
 * todo ubah session jadi pake bahasa Inggris?
 * todo adain lagi set_customer_info
 * todo pake PHPJqueryCallback terbaru?
 * 
 */ 

class POS extends Admin_Controller 
{

    /**
     * todo apakah mungkin tidak model dilo
     */
    function __construct() 
    {
        parent::__construct();

        // set the language
        $this->load->language('pos');

        /* Check whether they have role to this system */
        /* I add manager because it used in cancel receipt */
        if (!$this->ion_auth->in_group(array('owner','administrator','cashier')))
        {
            if ($this->input->is_ajax_request()) {
                echo $this->lang->line('not_login');
            }
            redirect('/');
        }

        /* Load the default model */
        $this->load->model(array('report_model', 'receipt_model','pos_session_model','product_model','customer_model'));

        /* load helper */
        $this->load->helper(array('url','cookie'));

        /* Load The Library */
        $this->load->library('PHPJQueryCallback');

        /* get the user_id */
        $this->user_id = $this->session->userdata('user_id');
    }

    /**
     *
     */
    function index()
    {
        $this->load->helper('form');
        $this->load->vars($this->_report());
        $this->load->view('pos/pos1_view');
    }

    /**
     * set_customer
     */
    function set_customer()
    {
        $this->load->model('membership/membership_model');

        $callback = new PHPJqueryCallback();

        $this->_form_reset($callback);

        try {
            $customer = $this->customer_model->search_and_get($this->input->post('query'));
            $this->pos_session_model->set_customer ($customer->id);
        } catch (Exception $e) {
            $callback->alert ("Error!\n".$e->getMessage());
            $callback->send();
        }

        // process
        $callback->val('#customer_input', $customer->name);
        $callback->html('#customer_name', $customer->name);
        $callback->html('#customer_last_visit', date('d, M Y', strtotime($this->customer_model->last_visit($customer->id))));
        $callback->attr('#customer_details', 'href', site_url('/customer/'.$customer->id));
        $callback->focus('#product_input');
        $callback->send();
    }

    /**
     * set_product
     */
    function set_product() 
    {
        $callback = new PHPJqueryCallback();

        $product_code = $this->input->post('query');

        // if empty go to diskon
        if ($product_code == '') {
            $callback->val('#product_input', '');
            $callback->focus('#payment_input');
            $callback->send();
        }

        if ($product_code == '-') {
            $callback->val('#product_input', '');
            $callback->val('#discount_input', '0');
            $callback->focus('#discount_input');
            $callback->send();
        }

        try {
            $product = $this->product_model->search_and_get($product_code);
            $this->pos_session_model->set_product($product->code);
        } catch (Exception $e) {
            if ($e->getMessage() == 'session_not_valid') {
                $callback->focus('#customer_input');
                $callback->alert($this->lang->line($e->getMessage()));
            } else {
                $callback->alert("Error!\n".$e->getMessage());
            }
            $callback->send();
        }

        $callback->val ("#product_input", $product->name);
        $callback->val ("#quantity_input", 1);
        $callback->focus ( "#quantity_input" );
        $callback->send();
    }

    /**
     * set_quantity
     * todo give try catch when set quantity
     *
     */
    function set_quantity()
    {
        $callback = new PHPJqueryCallback();

        $quantity = $this->input->post('query');

        // Canceling product with empty or 0
        if ($quantity == '' or $quantity == '0') {
            $callback->val ("#product_input", '');
            $callback->focus ( "#product_input" );
            $callback->send ();
        }


        try {
            $ses = $this->pos_session_model->last();
        } catch (Exception $e) {
            if ($e->getMessage() == 'session_not_valid') {
                $callback->focus('#customer_input');
                $callback->alert($this->lang->line($e->getMessage()));
            } else {
                $callback->alert("Error!\n".$e->getMessage());
            }
            $callback->send();
        }

        if ($ses->activity != 'set_product') {
            $callback->alert("Error!\nProduct not specified yet" );
            $callback->val("#product_input", '');
            $callback->focus("#product_input" );
            $callback->send();
        }

        $produk = $this->product_model->get($ses->parameter);

        $this->pos_session_model->set_quantity($quantity);

        $line_total = (int) $produk->price * (int) $quantity;
        $total = $this->pos_session_model->total() + $line_total;

        $callback->append("table#purchase_item > tbody", "<tr><td>$produk->name</td><td>$quantity</td><td>".number_format($line_total)."</td></tr>");
        $callback->html("#total_number", number_format($total));
        $callback->val("#product_input", '');
        $callback->focus("#product_input");
        $callback->send();
    }

    /**
     * Ok permasalahan utama dari diskon dia ditaruh di table kuitansi
     * bukan di kuitansi_baris, akibatnya agak susah mentracenya
     *
     *
     * todo bikin diskon di kuitansi_baris? Maybe later
     */
    function set_discount()
    {
        $discount = $this->input->post('query');

        $callback = new PHPJqueryCallback();

        try {
            $kegiatan = $this->pos_session_model->last();
            if ($kegiatan->activity == 'set_product') {
                $callback->alert("Error!\nSet the quantity first");
                $callback->focus("#quantity_input");
                $callback->send();
            }
            $this->pos_session_model->set_discount($discount);

        } catch (Exception $e) {
            if ($e->getMessage() == 'session_not_valid') {
                $callback->focus('#customer_input');
                $callback->alert($this->lang->line($e->getMessage()));
            } else {
                $callback->alert("Error!\n".$e->getMessage());
            }
            $callback->send();
        }

        $total = $this->pos_session_model->total() - (int) $discount;

        $callback->html("#total_number", number_format($total));
        $callback->focus("#method_input");
        $callback->send();
    }

    /**
     *
     */
    function set_method()
    {
        $callback = new PHPJqueryCallback();

        $payment_method = $this->input->post('query');

        try {
            $kegiatan = $this->pos_session_model->last();
            if ($kegiatan->activity == 'set_product') {
                $callback->alert("Error!\nSet the quantity first");
                $callback->focus("#quantity_input");
                $callback->send();
            }
            $this->pos_session_model->set_method($payment_method);

        } catch (Exception $e) {
            if ($e->getMessage() == 'session_not_valid') {
                $callback->focus('#customer_input');
                $callback->alert($this->lang->line($e->getMessage()));
            } else {
                $callback->alert("Error!\n".$e->getMessage());
            }
            $callback->send();
        }

        // if not cash set the payment
        if ($payment_method != 'cash') {
            $callback->val('#payment_input', $this->pos_session_model->total());
        }

        $callback->focus('#payment_input');
        $callback->send();
    }

    /**
     *
     */
    function set_payment()
    {
        $callback = new PHPJqueryCallback();

        // Check Session order
        try {
            // check all the product has quantity
            $kegiatan = $this->pos_session_model->last();
            if ($kegiatan->activity == 'set_product') {
                $callback->alert("Error!\nSet the quantity first");
                $callback->focus("#quantity_input");
                $callback->send();
            }
            // check this transaction has an items
            if (!$this->pos_session_model->check_product_exist() ) {
                $callback->alert ("There are no items");
                $callback->focus("#product_input");
                $callback->send();
            }
            // check payment is bigger than total
            $payment = $this->input->post('query');
            $total = $this->pos_session_model->total();
            if ((int) $payment < $total ) {
                $callback->alert("Payment less than Total!");
                $callback->focus("#payment_input");
                $callback->send();
            }

            // ok set payment
            $this->pos_session_model->set_payment ($payment);

            // then get the receipt
            $receipt = $this->pos_session_model->get_receipt($this->user_id);

            // process the receipt
            $receipt = $this->receipt_model->create($receipt); 
            $this->scan ($receipt);

        } catch (Exception $e) {
            if ($e->getMessage() == 'session_not_valid') {
                $callback->focus('#customer_input');
                $callback->alert($this->lang->line($e->getMessage()));
            } else {
                $callback->alert("Error!\n".$e->getMessage());
            }
            $callback->send();
        }

        $callback->alert("Change : ".$receipt->change);

        if ($total > 0) {
            $callback->jsprint($receipt->struk());
        }

        $this->_form_reset($callback);
        $callback->callback($this->config->site_url('pos/update_report'));
        $callback->send();
    }


    /**
     * form_reset ()
     * todo dibuat inputnya yang dikasih id?
     */
    private function _form_reset ($callback) 
    {
        //$callback->html('#customer_info', '');
        $callback->html('table#purchase_item > tbody', '<tr></tr>');

        $callback->html('#total_number', '0');

        $callback->val('#customer_input', '');
        $callback->val('#product_input', '');
        $callback->val('#quantity_input', '1');
        $callback->val('#discount_input', '0');
        $callback->val('#method_input', 'cash');
        $callback->val('#payment_input', '0');
        $callback->focus('#customer_input');
    }

    /**
     *
     */
    public function update_report()
    {
        $callback = new PHPJqueryCallback();
        foreach ($this->_report() as $id => $value)
        {
            $callback->html('#'.$id, $value);
        }
        $callback->send();
    }

    /**
     *
     */
    private function _report()
    {
        return array(
            'todays_income' => number_format(
                (float) $this->report_model->receipts_total_in_day()->total /1000
            ),
            'transactions_number' => $this->report_model->count_todays_receipts(),
            'customers_number' => $this->report_model->count_todays_customers(),
            'new_customers_number' => $this->report_model->count_todays_new_customers(),
        );
    }

    /**
     * create_customer_info
     *
     * todo this is hardcoding
     * todo maybe this is not eneeded
     */
    private function _create_customer_info($customer, $callback) 
    {
        //todo atur gimana supaya indah
        //peramasalahannya ada pada headingnya

        $customer_info = "Customer : ".$customer_name;
        $customer_info .= '<a href="'.site_url('/customer/').$customer->id.'">(details)</a>';
        return $customer_info;

        $biodata = '<h4>Biodata</h4>';

        $biodata .= 
            '<div class="c-label">Name :</div> '.
            '<div class="c-value">'.$customer->name.'</div>';
        $biodata .= 
            '<div class="c-label">Address :</div>'.
            '<div class="c-value">'.$customer->address.'</div>';
        $biodata .= 
            '<div class="c-label">Phone : '.'</div>'.
            '<div class="c-value">'.$customer->phone.'</div>';
        $biodata .= 
            '<div class="c-label">Birth Place and Date : '.'</div>'.
            '<div class="c-value">'.
            $customer->birth_place ." ".
            $customer->birth_date.
            '</div>';
        return $biodata;
    }

    /**
     * cari orang berdasarakan
     * nama, alamat no telepon atau id pasien
     *
     * return apbila hanya satu json object pasien 
     * apabila lebih json array object pasien
     * 
     */
    function cari_orang ($query = '') 
    {
        if ($query == '') { $query = $_GET['term']; }

        $query=urldecode($query);

        $hasil = array();
        if ( preg_match("/^\d{1,4}$/", $query) ) {
            array_push($hasil, $this->customer_model->get($query));
        }
        $hasil = array_merge($hasil , $this->customer_model->search($query));

        $hasil2 = array();
        foreach ($hasil as $row) {
            $nnat = new stdClass();
            $nnat->label = $row->id." ".$row->name." | ".$row->address." | ".$row->phone;
            $nnat->value = $row->id;
            array_push ($hasil2, $nnat);
        }
        $this->output->set_content_type('application/json')
            ->set_output(json_encode($hasil2));
    }

    /**
     *
     */
    function find_customer($query = '') 
    {
        $query=urldecode($query);
        $result = array();
        if ( preg_match("/^\d{1,4}$/", $query) ) {
            array_push($result, $this->customer_model->get($query));
        }
        $result = array_merge($result , $this->customer_model->search($query));

        $this->output->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    /**
     * cari produk berdasarkan kode, nama ataupun keterangan
     */
    function find_product ($query='') 
    {
        $query=urldecode($query);

        if ( preg_match("/^\d{1,4}$/", $query) ) {
            $result = $this->product_model->search_by_category($query);
        } else {
            $result = $this->product_model->search($query);
        }

        $this->output->set_content_type('application/json')
            ->set_output(json_encode($result));
    }


    /**
     * =====================================================================
     */
    function cancel ($receipt_id=NULL) 
    {
        if ($receipt_id != NULL) {
            $inactive_reason = $this->input->get('inactive_reason');
            $receipt = $this->receipt_model->cancel($receipt_id,$this->user_id,$inactive_reason );
            $this->unscan ($receipt);
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        }

        $data = array (
            'receipts_failed' => $this->report_model->receipts_canceled_in_day(),
            'receipts' => $this->report_model->receipts_today(),
            'receipts_total' => $this->report_model->receipts_total_in_day(),
            'date' => date('d M Y', strtotime('today')),
            'title' => "Cancellation"

        );

        $this->load->vars($data);
		$this->template->render_page('main', 'cancel_view');
    }

    /**
     * scan
     * todo use Event
     *
     * todo use this to make something happen when 
     * receipt created
     * todo pindahin ini dari module pos
     */
    private function scan($receipt) 
    {
        $this->load->model('membership/membership_model');
        $this->load->model('stocks/stocks_model');
        $this->membership_model->scan_receipt($receipt);
        $this->stocks_model->scan_receipt($receipt);
    }

    /**
     * unscan
     *
     * todo use this script to make something happen
     * when receiept canceled
     * todo pindahin ini dari module pos
     */
    private function unscan($receipt) 
    {
        $this->load->model('membership/membership_model');
        $this->load->model('stocks/stocks_model');
        $this->membership_model->unscan_receipt($receipt);
        $this->stocks_model->unscan_receipt($receipt);
    }
}
