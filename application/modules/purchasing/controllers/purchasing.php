<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Purchase berfungsi untuk mencatat dan menangani pembelian
 * Dikarenakan program ini dimulai dari POS, maka ada sedikit
 * keterbalikan...
 *
 * Ok, gua akan menggunakan sistem yang agak sedikit basah atau WET di sini
 * WETTTTT = Write Everyting Ten Thousand Times
 *
 * Purchase, itu ada create, view_between, dan delete proposal...
 *
 * todo benerin purchase ??? Benerin kaya gimana?
 * todo bikin get between
 *
 * todo atur alert ketika date not perfect? (function create)
 * todo pikir apakah lebih baik pake nama create instead of set?
 * todo benerin css untuk media
 *
 * ok ada beberapa permasalah... 
 * pertama cancel... karena tampaknya mereka sangat senang dengan
 * menu cancelation..
 *
 * ok gua sekarang harus rapihin function yang udah ada..
 * mana function buat set dan mana function buat get..
 * akan lebih baik apabila jika digunakan post untuk ngeset data
 * dan get untuk mendapatkan data... Karena memang seperti itulah 
 * seharusnya..
 *
 * ok, karena ini agak sedikit bermasalah, maka mari kita melakukan sedikit 
 * pertama view adalah tampilan utama...
 * dari situ dia bisa panggil, printer, email dan cancel, dan juga detail
 * view 
 *      printer, email, cancel, detail
 *
 * tampilan lain ialah view_approve (untuk si owner)
 *      dan dia bisa manggil approve dan cancel...
 * 
 * dan terakhir adalah view_inactive
 *
 *
 * todo ok, gua ngerusak lagi... hahahaha...
 * kali ini adalah masalah, dari view yang pake template, dan 
 * yang tidak pake template....
 * Dan gua gabungin semuanya, jadinya berantakan....
 * hahahaha...
 *
 *
 */ 
class Purchasing extends Admin_Controller {

    /**
     * exist for loading something default
     */
    function __construct() {
        parent::__construct();
        $this->load->helper('url');

        $this->load->model('po_model');
        $this->load->model('vendors/vendors_model');
        $this->load->model('pos/product_model');

        $this->user_id = $this->ion_auth->user()->row()->id;
    }

    /**
     * list
     */
    function index() {
        $data['table'] = $this->po_model->get_active();
        $data['title'] = "Purchase Order List";

        if ($this->ion_auth->in_group('owner')) {
            $data['approve_button'] = TRUE;
        }

        $this->load->vars($data);

        $this->template->render_page('main','po_list_view');
    }

    /**
     * add
     */
    function add() {
        /* Check whether they have role to this system */
        if (!$this->ion_auth->in_group(array('owner', 'administrator' ))) {
            redirect('/');
        }

        /* check wether there are post data */
        if ($this->input->post()) {

            $code = $this->input->post('code');
            $quantity = $this->input->post('quantity');
            $price = $this->input->post('price');

            $lines = array();
            foreach (array_keys($code) as $i) {
                $line = array (
                    'code' => $code[$i],
                    'quantity' => $quantity[$i],
                    'price' => $price[$i],
                );
                array_push ($lines, $line);
            }

            /* must exist data */
            $po = array(
                'vendor_id' => $this->input->post('vendor_id'),
                'created_on' => $this->input->post('created_on'),
                'user_id_create' => $this->user_id,
            );

            /* optional data */
            if ($this->input->post('discount')) { $po['discount'] = $this->input->post('discount'); }
            if ($this->input->post('tax')) { $po['tax'] = $this->input->post('tax'); }
            if ($this->input->post('postage')) { $po['postage'] = $this->input->post('postage'); }
            if ($this->input->post('description')) { $po['description'] = $this->input->post('description'); }
            if ($this->input->post('payment')) { $payment = $this->input->post('payment'); }
            $po = $this->po_model->add($po, $lines, $payment);
            redirect ('puchasing');
        }

        /* show create view */
        $data['vendors'] = $this->vendors_model->get_all();
        $data['products'] = $this->product_model->get_all();
        $data['proposal'] = $this->po_model->get_proposal();
        $data['title'] = "Create Purchase Order"; 

        $this->load->vars($data);
        $this->template->render_page('main','po_add_view');
    }

    /**
     * edit
     */
    function edit($po_id) {
        if ($this->input->post()) {

            $code = $this->input->post('code');
            $quantity = $this->input->post('quantity');
            $price = $this->input->post('price');

            $lines = array();
            foreach (array_keys($code) as $i) {
                $line = array (
                    'code' => $code[$i],
                    'quantity' => $quantity[$i],
                    'price' => $price[$i],
                );
                array_push ($lines, $line);
            }

            /* must exist data */
            $po = array(
                'vendor_id' => $this->input->post('vendor_id'),
                'created_on' => $this->input->post('created_on'),
                'user_id_create' => $this->user_id,
            );

            /* optional data */
            if ($this->input->post('discount')) { $po['discount'] = $this->input->post('discount'); }
            if ($this->input->post('tax')) { $po['tax'] = $this->input->post('tax'); }
            if ($this->input->post('postage')) { $po['postage'] = $this->input->post('postage'); }
            if ($this->input->post('description')) { $po['description'] = $this->input->post('description'); }
            if ($this->input->post('payment')) { $payment = $this->input->post('payment'); }
            else {$payment = NULL;}

            $po = $this->po_model->edit($po_id, $po, $lines, $payment);
            redirect ('purchasing');
        }
        $data = array (
            'title' => 'Edit Purchase Order',
            'po' => $this->po_model->get($po_id),
            'vendors' => $this->vendor_model->get_all(),
            'products' => $this->product_model->get_all(),
        );

        $this->load->vars($data);
        $this->template->render_page('main','po_edit_view');
    }

    /**
     * delete
     */
    function delete ($po_id) {
        /* Check whether they have role to this system */
        if (!$this->ion_auth->in_group(array('administrator', 'owner' ))) {
            redirect('/');
        }
        $this->po_model->delete($this->user_id, $po_id);
        redirect ('purchasing', 'refresh');
    }

    /**
     * detail
     */
    function detail($po_id) {
        $data = (array) $this->po_model->get($po_id);
        $data['title'] = 'Purchase Order';

        $this->load->vars($data);

        if ($this->input->is_ajax_request()) {
            $this->load->vars (array ('ajax' => TRUE));
            $this->load->view('po_detail_view');
        } else {
            $this->template->render_page('main','po_detail_view');
        }
    }

    /**
     * aprove
     */
    function approve($po_id,$method=NULL) {
        /* Check whether they have role to this system */
        if (!$this->ion_auth->in_group(array('owner'))) {
            redirect('/');
        }

        $this->po_model->approve($this->user_id, $po_id);
        redirect ('purchasing');
    }

    /**
     * fungsi ini sejajar dengan email
     * Apabila po diprint atau diemail maka dia dinyatakan sudah dikirim
     */
    function printer($po_id) {
        /* Check whether they have role to this system */
        if (!$this->ion_auth->in_group(array('owner', 'administrator'))) {
            redirect('/');
        }

        $this->po_model->sent($po_id);
        $data = (array) $this->po_model->get($po_id);
        $this->load->view('po_print_view', $data);
    }

    /**
     * todo ok selesaikan ini...
     *
     *
     */
    function email($po_id) {
        /* Check whether they have role to this system */
        if (!$this->ion_auth->in_group(array('owner', 'administrator'))) {
            redirect('/');
        }

        $this->po_model->sent($po_id);
        $data = (array) $this->po_model->get($po_id);

        $this->load->library('email');

        /*
        $this->email->from('machine@enfysolution.co.id', 'Automatic Email');
        $this->email->to(vendor_email);
        $this->email->cc(admin_email);

        $this->email->subject('Limit Stock Detected');
        $this->email->message($this->load->view('po_print_view', $data, TRUE));

        $this->email->send();
         */
    }

    /**
     * Ok, rasa udah banyak banget fungsinya
     * todo pikirin lagi nih gimana baiknya
     * fungsinya dah kerasa kebanyakan banget
     * 
     * todo inget aja kalau udah lunas activenya di off.. jadi ngga muncul ke 
     * sistem lagi...
     *
     */
    function pay() {
        if ($this->input->post()) {
            $amount = $this->input->post('amount');
            $date = $this->input->post('date');
            $po_id = $this->input->post('po_id');

            $this->po_model->pay ($amount, $this->user_id, $date, $po_id );
        }
        redirect ('purchasing/detail/'.$po_id);
    }

    function cancel_pay($payment_id) {
        $this->po_model->delete_pay($payment_id);
        redirect ($_SERVER['HTTP_REFERER'], 'refresh');
    }

    /**
     * button ganti jadi link
     */
    function del_proposal($poline_id) {
        $this->po_model->del_proposal($poline_id);
        redirect('purchasing/add');
    }
}
