<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * Keanggotaan_model
 *
 * Mungkin bisa dibilang ini adalah membership management. Untuk databasenya
 * udah kebayang tapi mungkin yang masih bingung adalah bagaimana menampilkannya
 * dia udah siap buat diupgrade tapi mungkin untuk sekarang cuman bisa untuk 
 * masukin member baru dan belum bisa mengubah jenis keanggotaan.
 *
 */

class Membership_model extends CI_Model
{

    private $table;
    private $table_type;
    /**
     * __consstruct
     */
    function __construct () {
        parent::__construct();
        $this->load->database();

        $this->table = 'membership';
        $this->table_type = 'membership_type';
    }


    /**
     * ok, baru ini aja function yang kepikiran ma gua...
     * dia akan sangat bergantung pada format kuitansi
     *
     *
     */
    function scan_receipt($receipt) {
        foreach ($receipt->lines as $line) {
            $jenis = $this->cari_jenis($line->code);

            if ($jenis != NULL) {
                $awal = date('Y-m-d', strtotime($receipt->timestamp));
                $akhir = date('Y-m-d', strtotime($receipt->timestamp .$jenis->period));
                $values = array (
                    'receipt_id' => $receipt->id,
                    'customer_id' => $receipt->customer_id,
                    'membership_type_id' => $jenis->id,
                    'start' => $awal,
                    'end' => $akhir
                );
                $this->db->insert($this->table, $values);
            }
        }
    }


    /**
     *
     *
     */
    function unscan_receipt($receipt) {
        $this->db->where ('receipt_id', $receipt->id);
        $this->db->delete($this->table);
    }

    /**
     * todo bikin documentation cara make membership management
     *
     */
    function lihat_keanggotaan($customer_id) {
        $this->db->where('customer_id', $customer_id);
        $this->db->where('active', TRUE);
        $this->db->join($this->table_type, $this->table_type.'.id = '.$this->table_type.'_id');
        $query = $this->db->get($this->table);

        $semua_keanggotaan = $query->result();

        foreach ($semua_keanggotaan as $k => $keanggotaan) {
            if ( strtotime ($keanggotaan->end) < strtotime ("now") ) {
                $this->db->where('id' , $keanggotaan->id);
                $this->db->set('active', FALSE);
                $keanggotaan->active = FALSE;
                $this->db->update($this->table);
                unset ($semua_keanggotaan[$k]);
            }
        }
        return $semua_keanggotaan;
    }


    /**
     *
     *
     */
    function get_jenis ($keanggotaan_jenis_id) {
        $this->db->where ('id', $keanggotaan_jenis_id);
        $query = $this->db->get($this->table_type,1);
        return $query->row();

    }
    

    function cari_jenis($kode_produk) {
        $this->db->where ('code', $kode_produk);
        $query = $this->db->get($this->table_type,1);
        return $query->row();
    }
}
