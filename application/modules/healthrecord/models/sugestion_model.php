<?php
/**
 * sugestion_model
 * 
 * 
 * 
 * PHP Version 5.5
 * 
 * @category Model
 * @package  Business
 * @author   Egon Firman <egon.firman@gmail.com>
 * @license  http://bvap.me None
 * @link     http://bvap.me
 **/
 
class Sugestion_model extends CI_Model
{
    protected $table = 'healthrecord_sugestion_list';

    public function getList()
    {
        return $this->db->get($this->table)->result();
    }

    public function get($id)
    {
        return $this->db->get($this->table, $id)->row();
    }

    public function add($data)
    {
        $this->db->insert($this->table, $data);
    }

    public function edit($id, $data)
    {
        $this->db->update($this->table, $data, array('id' => $id));
    }

    public function delete($id)
    {
        $row = $this->get($id);
        $this->db->delete($this->table, array('id' => $id));
        return $row;
    }

    public function get_from_diagnostic($name = "") 
    {
        $sugestion = $this->db->get_where(
            $this->table, 
            array('diagnostic' => $name)
        )->row();

        $result = "";

        if ($sugestion) {
            if ($sugestion->diagnostic) {
                $result .= "Saran untuk penderita " . $sugestion->diagnostic . "\n";
            }
            if ($sugestion->number_therapy) {
                $result .= "Jumlah Terapi: " . $sugestion->number_therapy . "\n";
            }
            if ($sugestion->electrostatic) {
                $result .= "Electrostatis: " . $sugestion->electrostatic . "\n";
            }
            if ($sugestion->biowater) {
                $result .= "Biowater: " . $sugestion->biowater . "\n";
            }
            if ($sugestion->sauna) {
                $result .= "Sauna: " . $sugestion->sauna . "\n";
            }
            if ($sugestion->massage) {
                $result .= "Massage: " . $sugestion->massage . "\n";
            }
            if ($sugestion->others) {
                $result .= "Lainnya: " . $sugestion->others . "\n";
            }
        } else {
            $result = "Belum ada saran untuk $name\n";
        }

        return $result;
    }
}
