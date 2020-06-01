<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Qr extends CI_Controller
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
        $this->load->model('QrModel', 'QrModel', TRUE);
        $this->load->model('MainModel', 'MainModel', TRUE);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
        $this->load->library('userlevel');
    }

    /**
     * View generate qr page.
     *
     * @return void
     */
    public function generateQr()
    {
        $data = $this->session->userdata;

        //check user level
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        //check is admin or not
        if ($dataLevel == 'is_admin') {

            $data['title'] = 'Generate QR Code';
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

                if ($this->MainModel->isDuplicate($this->input->post('email'))) {
                    $this->session->set_flashdata('flash_message', 'User email already exists');
                    redirect(site_url() . 'qr/generateQr');
                } else {
                    $this->load->library('password');
                    $post = $this->input->post(NULL, TRUE);
                    $cleanPost = $this->security->xss_clean($post);
                    $cleanPost['qr'] = $this->input->post('firstname') . ' ' . $this->input->post('lastname');
                    $hashed = $this->password->create_hash($cleanPost['password']);

                    $cleanPost['email'] = $this->input->post('email');
                    $cleanPost['role'] = $this->input->post('role');
                    $cleanPost['firstname'] = $this->input->post('firstname');
                    $cleanPost['lastname'] = $this->input->post('lastname');
                    $cleanPost['password'] = $hashed;
                    unset($cleanPost['passconf']);

                    $cleanPost['qr'] = $this->input->post('firstname') . ' ' . $this->input->post('lastname');
                    // Insert to database
                    if (!$this->MainModel->addUser($cleanPost)) {
                        $this->session->set_flashdata('flash_message', 'There was a problem saving the QR.');
                        redirect(site_url() . 'qr/generateQr');
                    } else {
                        if (!$this->QrModel->insertQr($cleanPost)) {
                            $this->session->set_flashdata('flash_message', 'There was a problem saving the QR.');
                            redirect(site_url() . 'qr/generateQr');
                        } else {
                            $this->load->view('template/header', $data);
                            $this->load->view('template/navbar', $data);
                            $this->load->view('template/container');
                            $this->load->view('qr/generate_qr', $data, $cleanPost);
                        }
                    }
                }

            } else {
                $this->form_validation->set_rules('qr', 'Your Employee Full Name');
                if (empty($_POST)) {
                    $this->load->view('template/header', $data);
                    $this->load->view('template/navbar', $data);
                    $this->load->view('template/container');
                    $this->load->view('qr/generate_qr', $data);
                } else {
                    if ($this->input->post('qr') == '') {
                        $this->session->set_flashdata('flash_message', 'Please fill the name of user.');
                        redirect(site_url() . 'qr/generateQr');
                    }
                    $post = $this->input->post(NULL, TRUE);
                    $cleanPost = $this->security->xss_clean($post);
                    $cleanPost['qr'] = $this->input->post('qr');

                    if (!$this->QrModel->insertQr($cleanPost)) {
                        $this->session->set_flashdata('flash_message', 'There was a problem saving the QR, and generate the QR.');
                        redirect(site_url() . 'qr/generateQr');
                    } else {
                        $this->load->view('template/header', $data);
                        $this->load->view('template/navbar', $data);
                        $this->load->view('template/container');
                        $this->load->view('qr/generate_qr', $data, $cleanPost);
                    }
                }
            }
        }
    }

    /**
     * View history page.
     *
     * @return void
     */
    public function historyQr()
    {
        $data = $this->session->userdata;
        $data['title'] = 'History QR';

        //check user level
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        // Load the js
        $data['js_to_load'] = array(
            'jquery.dataTables.min.js',
            'dataTables.buttons.min.js',
            'buttons.flash.min.js',
            'jszip.min.js',
            'pdfmake.min.js',
            'vfs_fonts.js',
            'buttons.html5.min.js',
            'buttons.print.min.js',
            'dataTables.bootstrap.min.js',
            'qr/history_qr.js'
        );

        //check is admin or not
        if ($dataLevel == 'is_admin') {
            $this->load->view('template/header', $data);
            $this->load->view('template/navbar', $data);
            $this->load->view('template/container');
            $this->load->view('qr/history_qr', $data);
            $this->load->view('template/footer', $data);
        } else {
            redirect(site_url() . 'main/');
        }
    }

    /**
     * Function delete the QR.
     *
     * @return void
     */
    public function deleteHistoryQr($id)
    {
        $data = $this->session->userdata;
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        // Check user level

        // Check is admin or not
        if ($dataLevel == 'is_admin') {
            $getDelete = $this->QrModel->deleteHistoryQr($id);

            if (!$getDelete) {
                $this->session->set_flashdata('flash_message', 'Error, cant delete the QR!');
            } else  {
                $this->session->set_flashdata('success_message', 'Delete QR was successful.');
            }
            redirect(site_url() . 'qr/historyQr/');
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
        echo $this->QrModel->getDataTables();
    }
}
