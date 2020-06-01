<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller
{

    public $status;
    public $roles;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('MainModel', 'MainModel', TRUE);
        $this->load->model('ReportModel', 'ReportModel', TRUE);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
        $this->load->library('userlevel');
    }

    /**
     * View index report page.
     *
     * @return void
     */
    public function index()
    {
        $data = $this->session->userdata;

        //check user level
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }

        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        $dataInfo = array(
            'id' => $data['id']
        );
        $data['title'] = 'Report';
        $resultGetUser = $this->MainModel->getUserInfo($dataInfo['id']);
        $data['name'] = $resultGetUser->first_name . ' ' . $resultGetUser->last_name;

        // Load js
        $data['js_to_load'] = array(
            'bootstrap-datepicker.min.js',
            'jquery.dataTables.min.js',
            'dataTables.buttons.min.js',
            'buttons.flash.min.js',
            'jszip.min.js',
            'pdfmake.min.js',
            'vfs_fonts.js',
            'buttons.html5.min.js',
            'buttons.print.min.js',
            'dataTables.bootstrap.min.js',
            'report/index.js'
        );

        // Add data for the js to call ajax
        $data['data_js'] = array(
            'var role = ' . $data['role'] . ';',
            'var name = "' . $data['first_name'] . ' ' . $data['last_name'] . '";',
        );

        if ($dataLevel == 'is_admin' || $dataLevel == 'is_user') {
            $this->load->view('template/header', $data);
            $this->load->view('template/navbar', $data);
            $this->load->view('template/container');
            $this->load->view('report/report', $data);
            $this->load->view('template/footer', $data);
        } else {
            redirect(site_url() . 'main/');
        }

    }

    /**
     * Get dataTables.
     *
     * @return void
     */
    public function dataTableJson()
    {
        // Get role and name
        $role = $this->input->post('role');
        $name = $this->input->post('name');

        $data = array(
            'role' => $role,
            'name' => $name,
        );

        echo $this->ReportModel->getDataTables($data);
    }
}
