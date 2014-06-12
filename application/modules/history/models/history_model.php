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
 * dependency_modules = customer, product and POS
 *
 * @author Egon Firman <egon.firman@gmail.com>
 */
class History_model extends CI_Model 
{
    /**
     * Create because function receipts between hit the performance so bad.
     * Creating view is not the solution that have impact on performance. 
     * It's slows down a litle bit. 
     *
     * create view receipts_history
     *
     *
     */
    public function receipts($timestamp_start, $timestamp_end, $where=null, $like=null, $limit=100, $offset=0, $order_by='timestamp', $order_dir='asc')
    {

        // countall
        $return = new stdClass();
        $return->all = $this->db->count_all('receipts');

        // count filtered
        $this->_filter_by_time($timestamp_start, $timestamp_end);
        $this->_filter_by_new_and_cancelled($where);
        $this->_filter_by_like(array ('customer_id', 'customer_name', 'user_name', 'products'), $like);
        $return->filtered = $this->db->count_all_results('receipts_history_temp');

        // sum
        // todo do not format the view, because it will break counting
        $this->db->select_sum('subtotal');
        $this->db->select_sum('discount');
        $this->db->select_sum('total');
        $this->db->where('active', 1); // only count the active one
        $this->_filter_by_time($timestamp_start, $timestamp_end);
        $this->_filter_by_like(array ('customer_id','customer_name', 'user_name', 'products'), $like);
        $row = $this->db->get('receipts_history_temp')->row();
        $return->subtotal = $row->subtotal;
        $return->discount = $row->discount;
        $return->total    = $row->total;

        // bug must be filterd again or mybe the bug is in me
        $this->_filter_by_time($timestamp_start, $timestamp_end);
        $this->_filter_by_new_and_cancelled($where);
        $this->_filter_by_like(array ('customer_id','customer_name', 'user_name', 'products'), $like);
        $this->db->order_by ($order_by, $order_dir);
        $return->result = $this->db->get('receipts_history_temp', $limit, $offset)->result();

        return $return;
    }

    public function receipts_download($timestamp_start, $timestamp_end, $where, $like=null)
    {
        $this->load->dbutil();

        $this->_filter_by_time($timestamp_start, $timestamp_end);
        $this->_filter_by_new_and_cancelled($where);
        $this->_filter_by_like(array ('customer_id','customer_name', 'user_name', 'products'), $like);
        $query = $this->db->get('receipts_history_temp');

        return $this->dbutil->csv_from_result($query);
    }

    public function sync()
    {
        // check table is existed 
        if ($this->db->table_exists('receipts_history_temp'))
        {
            // check is it updated
            $sql = "SELECT timestamp FROM receipts_history_temp WHERE timestamp = (SELECT timestamp FROM receipts ORDER BY timestamp DESC LIMIT 1)";
            $query = $this->db->query($sql);
            if ($query->num_rows > 0) { // still synched
                return;
            }
            else {
                $this->db->query('drop table receipts_history_temp');
            }
        }

        // ok if all test is passed means we need to create new table
        $sql = "CREATE TABLE receipts_history_temp as";

        $sql .= "(select
            `receipts`.`id` AS `id`,
            `receipts`.`customer_id` AS `customer_id`,
            `customer`.`name` AS `customer_name`,
            (case when (cast(`receipts`.`timestamp` as date) = cast(`customer`.`timestamp` as date)) then 1 else 0 end) AS `new`,
            group_concat(`receipts_line`.`code` separator ',') AS `products_code`,
            group_concat(`receipts_line`.`name` separator ',') AS `products`,
            (sum((`receipts_line`.`price` * `receipts_line`.`quantity`)))AS `subtotal`,
            (`receipts`.`discount`) AS `discount`,
            (sum((`receipts_line`.`price` * `receipts_line`.`quantity`)) - `receipts`.`discount`) AS `total`,
            `receipts`.`method` AS `method`,
            `receipts`.`user_id` AS `user_id`,
            `users`.`username` AS `user_name`,
            `receipts`.`active` AS `active`,
            `receipts`.`inactive_reason` AS `inactive_reason`,
            `receipts`.`timestamp` AS `timestamp`

            from (((  `receipts` 
            left join `receipts_line` on((`receipts`.`id` = `receipts_line`.`receipt_id`))) 
            left join `customer` on((`customer`.`id` = `receipts`.`customer_id`))) 
            left join `users` on((`users`.`id` = `receipts`.`user_id`))) 

            group by `receipts`.`id`);";

        $this->db->query($sql);
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

    private function _filter_by_new_and_cancelled($where)
    {
        if ($where['new']) {
            $this->db->where('new', 1);
        }
        if ($where['cancelled']) {
            $this->db->where('active', 0);
        }
    }
}

