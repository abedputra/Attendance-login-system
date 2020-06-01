<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportModel extends CI_Model
{
    /**
     * Get dataTables.
     *
     * @param $data
     * @return array
     */
    public function getDataTables($data)
    {
        // If role is admin
        if ($data['role'] == 1) {
            $this->load->library('datatables');
            $this->datatables->select('*');
            $this->datatables->from('absent');
        } else {
            $this->load->library('datatables');
            $this->datatables->select('*');
            $this->datatables->from('absent');
            $this->datatables->where('name', $data['name']);
        }

        // return data
        return $this->datatables->generate();

    }
}
