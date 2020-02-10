<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller
{

    public $status;
    public $roles;

    function __construct()
    {
        parent::__construct();
        $this->load->model('User_model', 'user_model', TRUE);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
        $this->load->library('userlevel');
    }

    public function index()
    {
        $data = $this->session->userdata;
        $this->load->library("pagination");

        //check user level
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }

        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        $dataInfo = array(
            'id' => $data['id']
        );
        $data['title'] = "Report";
        $resultGetUser = $this->user_model->getUserInfo($dataInfo['id']);
        $data["name"] = $resultGetUser->first_name . " " . $resultGetUser->last_name;

        $this->load->view('template/header', $data);
        $this->load->view('template/navbar', $data);
        $this->load->view('template/container');
        $this->load->view('report/report', $data);

    }

    public function dataTableJson()
    {
        $role = $this->input->post('role');
        $name = $this->input->post('name');

        // If role is admin
        if ($role == 1) {
            $this->load->library('datatables');
            $this->datatables->select('*');
            $this->datatables->from('absent');
        } else {
            $this->load->library('datatables');
            $this->datatables->select('*');
            $this->datatables->from('absent');
            $this->datatables->where('name', $name);
        }

        // return data
        echo $this->datatables->generate();

    }
}
