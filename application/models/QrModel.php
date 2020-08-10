<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

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
     * Save data form import csv.
     *
     * @param $data
     * @return void
     */
    public function saveFromCsv($data)
    {
        $q = $this->db->insert_string('history_qr', $data);
        $this->db->query($q);
        $this->db->insert_id();
    }

    /**
     * Get dataTables.
     *
     * @return Datatables
     */
    public function getDataTables()
    {
        // Get data from the database
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query('SELECT `id`, `name` FROM `history_qr`');

        // Add new column qr code
        $datatables->add('qr_code', static function ($data) {
            $dataQr = "{'name':'" . $data['name'] . "'}";
            return '<img class="img-thumbnail clickSave" src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=' . $dataQr . '&choe=UTF-8&chld=L|0" style="margin: 0 auto;display: block;widht:200px !important;">';
        });

        // Add new column action
        $datatables->add('action', static function ($data) {
            $dataQr = "{'name':'" . $data['name'] . "'}";
            return '<a href="https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . $dataQr . '&choe=UTF-8&chld=L|0" target="_blank"><button class="btn btn-primary btn-sm"><i class="fa fa-download" aria-hidden="true"></i> </button></a> <a href="' . site_url() . 'qr/deleteHistoryQr/' . $data['id'] . '"><button class="btn btn-danger btn-sm delete-button"><i class="fa fa-trash" aria-hidden="true"></i> </button> </a>';
        });

        // Generate the datatables
        return $datatables->generate();

    }

    /**
     * Function check duplicate history qr.
     *
     * @param $name
     * @return boolean
     */
    public function isDuplicateQr($name)
    {
        $this->db->get_where('history_qr', array('name' => $name), 1);
        return $this->db->affected_rows() > 0;
    }
}
