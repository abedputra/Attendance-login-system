<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class ReportModel extends CI_Model
{
    /**
     * Get dataTables.
     *
     * @param $data
     * @return Datatables
     */
    public function getDataTables($data)
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        // If role is admin
        if ($data['role'] == 1) {
            $datatables->query('SELECT `id`, `name`, `date`, `in_time`, 
               `out_time`, `work_hour`, `over_time`, `late_time`, 
               `early_out_time`, `in_location`, `out_location` FROM `absent`'
            );
        } else {
            $datatables->query('SELECT `id`, `name`, `date`, `in_time`, 
               `out_time`, `work_hour`, `over_time`, `late_time`, 
               `early_out_time`, `in_location`, `out_location` FROM `absent` WHERE `name` = "' . $data['name'] . '"'
            );
        }

        // return data
        return $datatables->generate();

    }
}
