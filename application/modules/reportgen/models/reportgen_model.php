<?php
/**
 * reportstype_model
 * 
 * @author Egon Firman <egon.firman@gmail.com>
 **/
 

class Reportgen_model extends CI_Model
{
    /*
    protected $income_per_day = 'select date_format(receipts.timestamp, "%W, %e %M %Y") as date,  count(distinct(receipts.id)) as transactions, format(sum(receipts_line.quantity * receipts_line.price),0) as income from receipts join receipts_line on receipts_line.receipt_id = receipts.id  group by date(receipts.timestamp);';

    protected $customer_expenses = 'SELECT customer.id, customer.name, sum(receipts_line.quantity * receipts_line.price) from customer left join receipts on (receipts.customer_id = customer.id) left join receipts_line on (receipts_line.receipt_id = receipts.id) group by customer.id';
    select customer_id
    //protected $customer_expenses = 'SELECT customers.id, customers.name, sum(receipts_line.quantity * receipts_line.price) from customers left join receipts on (receipts.customer_id = customers.id) left join receipts_line on (receipts_line.receipt_id = receipts.id) group by customers.id';
    //*/

    protected $reports = array();

    protected $table = 'report_generator';

    public function getList() 
    {
        /*
        if (!$this->db->table_exists($this->table))
        {

        }
     */

        $query = $this->db->query("SHOW FULL TABLES WHERE table_type LIKE 'VIEW' and tables_in_erp like 'report_%'");

        $this->reports = array();

        foreach ($query->result() as $row) {
            $reports[] = $row->Tables_in_erp;
        }
        return $reports;
    }

    public function getCsv($report_type)
    {
        $query = $this->db->query('SELECT * FROM '.$report_type);
        $this->load->dbutil();
        return $this->dbutil->csv_from_result($query);
    }

    public function install()
    {
        $this->load->dbforge();

        $this->dbforge->create_table($this->table);

        $fields = array(
            'id' => array(

            ),
            'name' => array(

            ),
            'sql' => array(

            ),
            'default' => array(

            ),
        );
    }
}
