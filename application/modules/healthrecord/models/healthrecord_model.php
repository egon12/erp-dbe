<?php
/**
 * HealthRecord is interface to healthrecord database.
 * Still think the best for the unstructured document
 * maybe the best is save it in json.
 * But still it's not will be available for searching and etc.
 * And it will hard in processing the data..
 *
 * 
 *
 *
 */
class HealthRecord_Model extends CI_Model
{

    protected $table_prefix = 'healthrecord_';

    public function insert($table, $data = array())
    {
        if ($data['customer_id'] == null) {
            throw new Exception('Customer must be set first.');
        }

        $data['user_id'] = $this->session->userdata('user_id');

        foreach ($data as $key => $value) {
            if ($value == "") {
                unset ($data[$key]);
            }
        }

        $row_affected = $this->db->insert($this->table_prefix.$table, $data);

        // validateion
        if ($row_affected != 1) {
            throw new Exception ("Data not inserted");
        }
    }

    public function get($table)
    {
        $this->db->select($this->table_prefix.$table . '.* , users.first_name');
        $this->db->join('users', 'user_id = users.id');
        $this->db->order_by('date', 'desc');

        // only get that not have been deleted
        $this->db->where('deleted', 0);

        $result = $this->db->get($this->table_prefix.$table)->result();

        // validation
        if (sizeof($result) == 0) {
            throw new Exception("Data is not exists");
        }

        return $result;
    }

    public function getByCustomer($table, $customer_id = null)
    {
        $this->db->where('customer_id', $customer_id);
        return $this->get($table);
    }

    public function getById($table, $id)
    {
        $this->db->where($this->table_prefix.$table.'.id', $id);
        return $this->get($table)[0];
    }


    public function process_image_file()
    {
        // todo make this
    }

    /**
     * use id as reference to update table
     *
     */
    public function update($table, $data)
    {
        //$this->db->where(array('date' => $data['date'], 'customer_id' => $data['customer_id']));
        $this->db->where(array('id' => $data['id']));
        return $this->db->update($this->table_prefix.$table, $data);
    }

    public function delete($table, $id)
    {
        $row_affected = $this->db->where('id', $id)
            ->set('deleted', 1)
            ->update($this->table_prefix.$table);
        
        if ($row_affected != 1) {
            throw new Exception ("Error when delete some data");
        }
    }
}
