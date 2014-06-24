<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Auth_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->model('pos/report_model');
        $data['todays_income'] = number_format($this->report_model->receipts_total_in_day()->total/1000);
        $data['transactions_number'] = $this->report_model->count_todays_receipts();
        $data['customers_number'] = $this->report_model->count_todays_customers();
        $data['new_customers_number'] = $this->report_model->count_todays_new_customers();

        //echo '<pre>', print_r($this->the_user, true), '</pre>';
        /*================================*/
        /* mari kita iseng */


        $this->load->model('pos/report_model');
        // get this month 
        $month = strtotime('today');
        $start = date('Y-m-1', $month);
        $end = date('Y-m-t', $month);
        $thisM = $this->report_model->sum_receipts_per_day($start, $end);

        // get last month
        //$month = strtotime('first day of previous month');
        $month = strtotime('-1 month');
        $start = date('Y-m-1', $month);
        $end = date('Y-m-t', $month);
        $lastM = $this->report_model->sum_receipts_per_day($start, $end);

        $data['this_month'] = "[ [0,0]";
        $data['last_month'] = "[ [0,0]";
        $data['max'] = 0;

        foreach ($thisM as $row) 
        {
            $day = date('d', strtotime($row->date));
            $total = $row->total/1000;
            $data['this_month'] .= ",[$day,$total]";
            if ($total > $data['max']) {
                $data['max'] = $total;
            }
        }

        foreach ($lastM as $row) 
        {
            $day = date('d', strtotime($row->date));
            $total = $row->total/1000;
            $data['last_month'] .= ",[$day,$total]";
            if ($total > $data['max']) {
                $data['max'] = $total;
            }
        }

        $data['this_month'] .= "]";
        $data['last_month'] .= "]";

        $this->load->vars($data);


        /*================================*/
        $this->template
            ->set('title', 'Dashboard')
            ->set('the_user', $this->the_user)
            ->render_page('main');
    }
}
/* End of file main.php */
/* Location: ./application/controllers/main.php */
