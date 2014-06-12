<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * PHP version 5
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
 * 
 */ 

class Recap extends Admin_Controller {

    public function index() {

        if ($this->input->get('start') ) {
            $start = $this->input->get('start')  ;
            $end = $this->input->get('end')  ;
        } else {
            $start = date('Y-m-d');
            $end = date('Y-m-d', strtotime('+1day'));
        }

        $data = array (
            'products' => $this->report_model->receipts_products_between($start, $end),
            'products_total' => $this->report_model->receipts_total_between ($start, $end),
            'start' => date ("d M Y", strtotime($start)),
            'end' => date ("d M Y", strtotime($end)),
            'title' => "Products Recaps",
        );

        $this->load->vars ($data);
        if ($this->input->is_ajax_request()) {
            $this->load->view ('pos/recap_view');
        } else {
            $this->template->render_page('main', 'pos/recap_view');
        }
    }
}
