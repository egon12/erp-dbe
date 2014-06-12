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

class History extends Admin_Controller {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('history_model');
    }

    public function index()
    {
        // update or sync database first
        $this->history_model->sync();

        /* before and after */
        if ($this->input->get('start') ) {
            $start = strtotime ($this->input->get('start')  );
            $end = strtotime ($this->input->get('end')  );
        } else {
            $start = strtotime ('now', mktime(0,0,0) );
            $end = strtotime ('+1day', mktime(0,0,0) );
        }

        $where = NULL;
        if ($this->input->get('value') != '') {
            $where = array(
                $this->input->get('key') => $this->input->get('value'),
            );
        }

        $this->load->vars(array(
            'start' => date ("d M Y", $start),
            'end' => date ("d M Y", $end),
        ));

        $this->template
          ->set('title', 'Transaction History')
          ->render_page('main', 'history_view');
    }

    public function datatables()
    {
        $start = $this->input->get('dStart');
        $end = $this->input->get('dEnd');

        $limit  = $this->get_limit();
        $offset = $this->get_offset();
        $search = $this->get_search();
        $sort   = $this->get_sort();
        $sort_dir = $this->get_sort_dir();

        $where = array (
            'new' => $this->input->get('bNew'),
            'cancelled' => $this->input->get('bCancelled'),
        );


        $data = $this->history_model->receipts($start, $end, $where, $search, $limit, $offset, $sort, $sort_dir);
        $debug = $this->db->last_query();

        $echo = isset($_GET['sEcho'])?$_GET['sEcho'] : 0;

        $output = array(
            "sEcho" => intval($echo),
            "iTotalRecords" => $data->all,
            "iTotalDisplayRecords" => $data->filtered,
            "aaData" => (array) $data->result,
            "debug" => $debug,
            "fSubtotal" => $data->subtotal,
            "fDiscount" => $data->discount,
            "fTotal" => $data->total,
        );

        echo json_encode($output);
    }

    public function download()
    {
        $start = $this->input->get('dStart');
        $end = $this->input->get('dEnd');
        $where = array (
            'new' => $this->input->get('bNew'),
            'cancelled' => $this->input->get('bCancelled'),
        );
        $search = $this->get_search();
        $data = $this->history_model->receipts_download($start, $end, $where, $search);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="history.csv"');
        header('Content-Transfer-Encoding: text');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        echo $data;
    }

    private function get_offset()
    {
        $start = 0;
        if ($this->input->get('iDisplayStart')) {
            $start = intval($this->input->get('iDisplayStart'));

            if ($start < 0)
                $start = 0;
        }

        return $start;
    }

    private function get_limit()
    {
        $offset = 10;
        if ($this->input->get('iDisplayLength')) {
            $offset = intval($this->input->get('iDisplayLength'));
            if ($offset < 5 || $offset > 500) {
                $offset = 10;
            }
        }

        return $offset;
    }

    private function get_sort_dir()
    {
        $sort_dir = "ASC";
        $sdir = strip_tags($this->input->get('sSortDir_0'));
        if (isset($sdir)) {
            if ($sdir != "asc" ) {
                $sort_dir = "desc";
            }
        }

        return $sort_dir;
    }

    private function get_sort() 
    {
        $sCol = $this->input->get('iSortCol_0');
        $col = 0;
        //list your entities for db search, based your column order in datatables
        $cols = array( "timestamp", "customer_name", "products", "subtotal", "discount", "total", "user_name");

        if (isset($sCol)) {
            $col = intval($sCol);
            if ($col < 0 || $col > 6)
                $col = 0;
        }
        $colName = $cols[$col];

        return $colName;
    }

    private function get_search()
    {
        if (isset($_GET['sSearch']) and $_GET['sSearch'] != 'undefined') {
            return $this->input->get('sSearch');
        } 
    }
}
