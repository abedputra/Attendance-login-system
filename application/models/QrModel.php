<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class QrModel extends CI_Model
{
    public $status;
    public $roles;

    /**
     * Insert the QR code.
     *
     * @param $allData
     * @return bool
     */
    public function insertQr($allData)
    {
        $data = array(
          'name' => $allData['qr'],
        );
        $q = $this->db->insert_string('history_qr', $data);
        $this->db->query($q);
        $check = $this->db->insert_id();

        if ($check) {
            return true;
        }

        return false;

    }

    /**
     * Delete function for the QR.
     *
     * @param $id
     * @return bool
     */
    public function deleteHistoryQr($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('history_qr');

        return $this->db->affected_rows() == '1';
    }

    /**
     * Get all data qr code.
     *
     * @return bool
     */
    public function getHistoryQrData()
    {
        $query = $this->db->get('history_qr');
        return $query->result();
    }

    /**
     * Get dataTables.
     *
     * @return array
     */
    public function getDataTables()
    {
        // Get data from the database
        $this->load->library('datatables');
        $this->datatables->select('*, history_qr.id as id, history_qr.name as image');
        $this->datatables->from('history_qr');
        $this->datatables->add_column('action', anchor('qr/deleteHistoryQr/$1', 'Delete', array('class' => 'btn btn-danger btn-sm')), 'id');

        // Generate the data
        return $this->datatables->generate();

    }
}
