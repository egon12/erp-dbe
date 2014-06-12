<?php
class Admin_Controller extends Auth_Controller
{

    public $user;

    public function __construct()
    {

        parent::__construct();

        if (!$this->input->is_ajax_request()) {
		
            $this->load->model('pos/report_model');
            $this->load->model('purchasing/po_model');
            $this->load->model('stocks/stocks_model');

            // todo pindahin nih data statistic ke model or something
            /*
            $data['todays_total'] = $this->report_model->receipts_total_in_day()->total;
            $data['need_to_buy'] = $this->po_model->get_proposal_number();
            $data['need_to_move'] = $this->stocks_model->get_stockout_pending_number();
            $data['notyet_arrive'] = $this->po_model->notyet_arrived_number();
            $data['notyet_approve'] = $this->po_model->notyet_approved_number();
            */

            $data['todays_income'] = number_format(
                (float) $this->report_model->receipts_total_in_day()->total/1000,
                2
            );
            $data['transactions_number'] = $this->report_model->count_todays_receipts();
            $data['customers_number'] = $this->report_model->count_todays_customers();
            $data['new_customers_number'] = $this->report_model->count_todays_new_customers();

            $this->load->vars($data);
        }
    }
}
