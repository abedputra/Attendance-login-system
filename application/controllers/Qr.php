<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Qr extends CI_Controller
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

    public function generateqr()
    {
        $data = $this->session->userdata;

        //check user level
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        //check is admin or not
        if ($dataLevel == "is_admin") {

            $data['title'] = "Generate QR Code";
            $userDetails = $this->input->post('user-details');
            // generate the qr with user details
            if (!empty($userDetails) && $userDetails == 1) {

                $this->form_validation->set_rules('firstname', 'First Name', 'required');
                $this->form_validation->set_rules('lastname', 'Last Name', 'required');
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
                $this->form_validation->set_rules('role', 'role', 'required');
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
                $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

                $this->form_validation->set_rules('qr', 'Your Employee Full Name');

                if ($this->user_model->isDuplicate($this->input->post('email'))) {
                    $this->session->set_flashdata('flash_message', 'User email already exists');
                    redirect(site_url() . 'qr/generateqr');
                } else {
                    $this->load->library('password');
                    $post = $this->input->post(NULL, TRUE);
                    $cleanPost = $this->security->xss_clean($post);
                    $cleanPost['qr'] = $this->input->post('firstname') . " " . $this->input->post('lastname');
                    $hashed = $this->password->create_hash($cleanPost['password']);
                    $cleanPost['email'] = $this->input->post('email');
                    $cleanPost['role'] = $this->input->post('role');
                    $cleanPost['firstname'] = $this->input->post('firstname');
                    $cleanPost['lastname'] = $this->input->post('lastname');
                    $cleanPost['password'] = $hashed;
                    unset($cleanPost['passconf']);

                    $cleanPost['qr'] = $this->input->post('firstname') . ' ' . $this->input->post('lastname');
                    //insert to database
                    if (!$this->user_model->addUser($cleanPost)) {
                        $this->session->set_flashdata('flash_message', 'There was a problem saving the QR.');
                        redirect(site_url() . 'qr/generateqr');
                    } else {
                        if (!$this->user_model->insertQr($cleanPost)) {
                            $this->session->set_flashdata('flash_message', 'There was a problem saving the QR.');
                            redirect(site_url() . 'qr/generateqr');
                        } else {
                            $this->load->view('template/header', $data);
                            $this->load->view('template/navbar', $data);
                            $this->load->view('template/container');
                            $this->load->view('qr/generateqr', $data, $cleanPost);
                        }
                    }
                }

            } else {
                $this->form_validation->set_rules('qr', 'Your Employee Full Name');
                if (empty($_POST)) {
                    $this->load->view('template/header', $data);
                    $this->load->view('template/navbar', $data);
                    $this->load->view('template/container');
                    $this->load->view('qr/generateqr', $data);
                } else {
                    if ($this->input->post('qr') == "") {
                        $this->session->set_flashdata('flash_message', 'Please fill the name of user.');
                        redirect(site_url() . 'qr/generateqr');
                    }
                    $post = $this->input->post(NULL, TRUE);
                    $cleanPost = $this->security->xss_clean($post);
                    $cleanPost['qr'] = $this->input->post('qr');

                    if (!$this->user_model->insertQr($cleanPost)) {
                        $this->session->set_flashdata('flash_message', 'There was a problem saving the QR, and generate the QR.');
                        redirect(site_url() . 'qr/generateqr');
                    } else {
                        $this->load->view('template/header', $data);
                        $this->load->view('template/navbar', $data);
                        $this->load->view('template/container');
                        $this->load->view('qr/generateqr', $data, $cleanPost);
                    }
                }
            }
        } // check user level
    }

    public function historyqr()
    {
        $data = $this->session->userdata;
        $data['title'] = "History QR";

        //check user level
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        //check is admin or not
        if ($dataLevel == "is_admin") {
            $this->load->view('template/header', $data);
            $this->load->view('template/navbar', $data);
            $this->load->view('template/container');
            $this->load->view('qr/historyqr', $data);
        } else {
            redirect(site_url() . 'main/');
        }
    }

    public function deletehistoryqr($id)
    {
        $data = $this->session->userdata;
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        //check is admin or not
        if ($dataLevel == "is_admin") {
            $getDelete = $this->user_model->deleteHistoryQr($id);

            $alldata = $this->user_model->getHistoryQrData();
            $dataCount = count($alldata);
            if ($getDelete == false && $dataCount > 0) {
                $this->session->set_flashdata('flash_message', 'Error, cant delete the user!');
            } else if ($getDelete == true && $dataCount > 0) {
                $this->session->set_flashdata('success_message', 'Delete user was successful.');
            } else if ($dataCount > 0) {
                $this->session->set_flashdata('flash_message', 'Someting Error!');
            }
            redirect(site_url() . 'qr/historyqr/');
        } else {
            redirect(site_url() . 'main/');
        }
    }

    public function dataTableJson()
    {
        $this->load->library('datatables');
        $this->datatables->select('*, history_qr.id as id, history_qr.name as image');
        $this->datatables->from('history_qr');
        $this->datatables->add_column('action', anchor('qr/deletehistoryqr/$1', 'Delete', array('class' => 'btn btn-danger btn-sm')), 'id');

        // return data
        echo $this->datatables->generate();

    }
}
