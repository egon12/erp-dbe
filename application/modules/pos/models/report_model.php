<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Report_model
 *
 * Ok, di sini tadinya ada beberapa yang merupakan kode dari 
 * receipt_model. Why I move here because it belong to here.
 * Receipt model sudah mencapai lebih dari 300 barus. Mungkin 
 * memang memiliki banyak comment tapi
 *
 * atau Crud yang kita buat siap menerimanya?
 *
 */
class Report_model extends CI_Model 
{

    function __construct() 
    {
        parent::__construct();

        $this->load->database();

        $this->load->model('pos/receipt_model');

        $this->receipts = 'receipts';
        $this->receipts_line = 'receipts_line';
    }

    /**
     * get_failed_between ($start, $befor) 
     */
    function receipts_canceled_between($start, $end, $where=null) 
    {
        // filter by time
        $this->_filter_by_time($start,$end);

        // filter by where
        $this->_filter_by_where($where);

        $query = $this->db->get_where($this->receipts, array('active' => 0));

        // repair data
        $result = array();
        foreach ($query->result() as $row) {
            $result[] = clone $this->receipt_model->get($row->id);
        }
        return $result;
    }

    /**
     * get_failed_between
     */
    function receipts_canceled_in_day($date='today') 
    {
        $end = date('Y-m-d', strtotime($date) + 86400);

        return $this->receipts_canceled_between($date, $end);
    }

    /**
     * get subtotal, diskon and subtotal of kuitansi between
     * todo bikin error code
     */
    function receipts_total_between($start, $end, $where=NULL) {
        $result = new stdClass();

        //get subtotal
        $this->db->select_sum('quantity * price', 'subtotal');
        $this->db->join($this->receipts, $this->receipts_line.'.receipt_id = receipts.id');
        $this->_filter_by_time($start, $end, 'receipts');
        $this->_filter_by_where($where);
        $this->db->where('active', 1);
        $query = $this->db->get($this->receipts_line);
        $result->subtotal = $query->row()->subtotal;

        //get discount
        $this->db->select_sum('discount');
        $this->_filter_by_time($start, $end);
        $this->_filter_by_where($where);
        $this->db->where('active', 1);
        $query = $this->db->get($this->receipts);
        $result->discount = $query->row()->discount;

        // get total
        $result->total = $result->subtotal - $result->discount;
        return $result;
    }

    public function receipts_today ()
    {
        $sql = "select
            `receipts`.`id` AS `id`,
            `receipts`.`customer_id` AS `customer_id`,
            `customer`.`name` AS `customer_name`,
            (case when (cast(`receipts`.`timestamp` as date) = cast(`customer`.`timestamp` as date)) then 1 else 0 end) AS `new`,
            group_concat(`receipts_line`.`code` separator ',') AS `products_code`,
            group_concat(`receipts_line`.`name` separator ',') AS `products`,
            format(sum((`receipts_line`.`price` * `receipts_line`.`quantity`)),0)AS `subtotal`,
            format(`receipts`.`discount`,0) AS `discount`,
            format((sum((`receipts_line`.`price` * `receipts_line`.`quantity`)) - `receipts`.`discount`),0) AS total,
            `receipts`.`method` AS `method`,
            `receipts`.`user_id` AS `user_id`,
            `users`.`username` AS `user_name`,
            `receipts`.`timestamp` AS `timestamp`

            from (((  `receipts` 
            left join `receipts_line` on((`receipts`.`id` = `receipts_line`.`receipt_id`))) 
            left join `customer` on((`customer`.`id` = `receipts`.`customer_id`))) 
            left join `users` on((`users`.`id` = `receipts`.`user_id`))) 

            where `receipts`.`active` = 1

            and DATE(`receipts`.`timestamp`) = DATE(NOW())

            group by `receipts`.`id`;";

        return $this->db->query($sql)->result();

    }

    /**
     *
     * todo bikin error code
     * todo kode masih lama
     * todo pake group by days?
     * todo benerin strtotime?
     *
     * strtotime dibilang memperlama kode...
     * Tapi masalahnya gua butuh ini untuk ditambahkan jadi satu hari...
     * Jadi ketika dia bilang tanggal segini dia menentukan hari dan kemudian
     *
     *
     *
     */
    function receipts_total_in_day($date = 'today')
    {
        if (!isset($this->_receipts_total_in_day)) {
            $result = new stdClass();

            //get subtotal
            $this->db->select_sum('quantity * price', 'subtotal')
                ->select_sum('quantity', 'quantity')
                ->select('count(distinct customer_id) as customer')
                ->select('count(distinct receipt_id) as number')
                ->select('discount')
                ->join($this->receipts_line, $this->receipts_line.'.receipt_id = '.$this->receipts .'.id')
                ->where('active', 1);

            $this->_filter_by_time_now($this->receipts);
            $query = $this->db->get($this->receipts);

            $row = $query->row();

            $result->number = $row->number;
            $result->customer = $row->customer;
            $result->quantity = $row->quantity;
            $result->subtotal = $row->subtotal;
            $result->discount = $row->discount;

            // get total
            $result->total = $result->subtotal - $result->discount;
            $this->_receipts_total_in_day = $result;
        }
        return $this->_receipts_total_in_day;
    }

    /**
     *
     * todo selesain
     */
    function sum_receipts_per_day($start, $end)
    {
        // filter by time first
        $this->_filter_by_time($start, $end, $this->receipts);

        // select
        $this->db->select_sum('quantity * price', 'subtotal');
        $this->db->select_sum('discount');
        $this->db->select('DATE(receipts.timestamp) as date' );

        // join and grouping
        $this->db->join($this->receipts, $this->receipts_line.'.receipt_id = receipts.id');
        $this->db->where('active', 1);
        $this->db->group_by('DATE(receipts.timestamp)');

        $query = $this->db->get($this->receipts_line);

        $result = array();
        foreach ($query->result() as $row) {
            $row->total = $row->subtotal - $row->discount;
            $result[] = $row;
        }
        return $result;

    }

    /**
     *
     */
    function receipts_products_between ($after, $before) {
        $after = date("Y-m-d H:i:s", strtotime($after));
        $before = date("Y-m-d H:i:s", strtotime($before));
        $sql = "SELECT code, name, price, sum(quantity) AS quantity, sum(price * quantity) AS total FROM receipts_line JOIN receipts\n"
            . "WHERE receipts_line.receipt_id = receipts.id \n"
            . "AND receipts.active = 1\n"
            . "AND receipts.timestamp > \"".$after."\"\n"
            . "AND receipts.timestamp < \"".$before."\"\n"
            . "GROUP BY code\n"
            . "ORDER BY code ASC";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    /**
     * todo cek strtotoime second parameter what is for?
     *
     */
    function receipts_products_in_day ($date='now') {
        $start = strtotime($date, mktime(0,0,0));
        $end = $start + 24 * 3600; // + 1 day from date specified 
        $result = $this->receipts_products_between($start, $end);
        
        return $result; 
    }

    /**
     *
     * New and Update Customer 
     */
    function count_new_customers ($start='today', $end='tomorrow') 
    {
        $this->_filter_by_time($start, $end);
        return $this->db->count_all_results('customer');
    }

    /**
     *
     */
    function count_todays_new_customers()
    {
        $this->_filter_by_time_now();
        return $this->db->count_all_results('customer');
    }

    /**
     *
     */
    function count_customers ($start='today', $end='tomorrow')
    {
        $this->_filter_by_time($start, $end);
        $this->db->group_by('customer_id');

        // There's a bug in here
        // return $this->db->count_all_results('receipts');

        // hard ways
        // todo this is slow
        return $this->db->get('receipts')->num_rows(); 
    }

    /**
     * count todays receipts
     */
    function count_todays_customers()
    {
        return $this->receipts_total_in_day()->customer;

        /*
        $this->_filter_by_time_now();
        $this->db->group_by('customer_id');

        // There's a bug in here
        return $this->db->count_all_results('receipts');

        // hard ways
        // todo this is slow
        //return $this->db->get('receipts')->num_rows(); 
         */
    }

    /**
     * Count new Receipts
     */
    function count_receipts ($start='today', $end='tomorrow', $where=null)
    {
        $this->_filter_by_time($start, $end);
        $this->db->where('active', 1);
        $this->_filter_by_like(array('customer.name', 'users.username'), $where);
        return $this->db->count_all_results('receipts');
    }

    /**
     * count todays receipts
     */
    function count_todays_receipts()
    {
        $this->_filter_by_time_now();
        return $this->db->where('active', 1)->count_all_results('receipts');
    }

    /**
     *
     */
    private function _filter_by_time($start, $end, $table='') 
    {
        $start = strtotime($start);
        $end   = strtotime($end);

        $start = date('Y-m-d H:i:s', $start);
        $end   = date('Y-m-d H:i:s', $end);

        $table .= ($table) ? '.' : '';

        $this->db
            ->where ($table.'timestamp >', $start)
            ->where ($table.'timestamp <', $end);
    }

    private function _filter_by_time_now($table =  '')
    {
        $table .= ($table) ? '.' : '';
        $this->db->where('DATE('.$table.'timestamp)', date('Y-m-d'));
    }

    private function _filter_by_where($where_fields, $value=null)
    {
        // if null no filter
        if ($value == null) { return; }

        $or_where = false;

        if (is_array($where_fields)) {
            foreach ($where_fields as $field) {
                if ($or_where) {
                    $this->db->or_where($field, $value);
                } else {
                    $this->db->where($field, $value);
                    $or_where = true;
                }
            }
            return;
        }
    }

    private function _filter_by_like($like_fields, $value=null)
    {
        // if null return!
        if ($value == null) { 
            return; 
        }

        $or_like = false;

        $str = '(';

        if (is_array($like_fields)) {
            foreach ($like_fields as $field) {
                if ($or_like) {
                    $str .= ' OR '.$field . ' LIKE "%' .$value . '%" ';
                } else {
                    $str .= $field . ' LIKE "%' .$value . '%" ';
                    $or_like = true;
                }
            }
            $str .= ')';
            $this->db->where ($str);
            return;
        }
    }
}
