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

class Detail extends Admin_Controller {

    function index ($receipt_id = NULL) {
        $data = $this->receipt_model->get($receipt_id); 
        $data->title = "Receipt's Detail";
        $this->load->vars ($data);
        if ($this->input->is_ajax_request()) {
            $this->load->view ('detail_view');
        } else {
            $this->template->render_page('main', 'detail_view');
        }
    }

}
