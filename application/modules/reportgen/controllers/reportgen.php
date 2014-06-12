<?php
/**
 * customertest
 * 
 * @author Egon Firman <egon.firman@gmail.com>
 **/
 

class Reportgen extends Admin_Controller
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->library('datatable');
        $this->load->model('reportgen_model');
    }


    public function index()
    {
        $this->template
            ->set('data', array('message' => ''))
            ->set('title', 'Report List')
            ->set('reports', $this->reportgen_model->getList())
            ->set('raw', array('customer','product','receipts','receipts_line'))
            ->render_page('main', 'reportgen/index_view');
        $this->reportgen_model->getList();

    }

    public function table($report_name)
    {
        $this->datatable->table = $report_name;
        $this->template->set('title', $report_name);
        $this->datatable->sAjaxSource = current_url();

        if ($this->input->is_ajax_request()) {
            // if ajax (the datatables are asking the data
            echo $this->datatable->serverProcess();
        } else {
            $this->template
                ->set('title', $report_name)
                ->set('data', array('message' => ''))
                ->set('htmlTable', $this->datatable->getHtmlTable())
                ->set('javascript', $this->datatable->getJavascript())
                ->render_page('main', 'reportgen/table_view');
        }

    }

    public function download($report_name)
    {
        $text = $this->reportgen_model->getCsv($report_name);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$report_name.'.csv"');
        header('Content-Transfer-Encoding: text');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        echo $text;

    }

}
