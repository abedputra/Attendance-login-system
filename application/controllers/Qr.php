<?php
defined('BASEPATH') or exit('No direct script access allowed');

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

        // Load the js
        $data['js_to_load'] = array(
            'qr/generate_qr.js'
        );

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
                    $cleanPost['first_name'] = $this->input->post('firstname');
                    $cleanPost['last_name'] = $this->input->post('lastname');
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
                            $this->load->view('template/footer', $data);
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
                    $this->load->view('template/footer', $data);
                } else {
                    // Check if any duplicate name of QR code
                    if ($this->QrModel->isDuplicateQr($this->input->post('qr'))) {
                        $this->session->set_flashdata('flash_message', 'Name of user already exists. Please try another one.');
                        redirect(site_url() . 'qr/generateQr');
                    }

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
                        $this->load->view('template/footer', $data);
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
            } else {
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

    /**
     * Function hash password.
     *
     * @param $pass
     * @return hash password
     */
    public function hashPassword($pass){
        $this->load->library('password');
        return $this->password->create_hash($pass);
    }

    /**
     * Upload data from csv file.
     *
     * @return void
     */
    public function importData()
    {
        $errorMessage = '';
        $errorMessageQr = '';
        $errorArr = array();
        $errorArrQr = array();

        // File extension
        $extension = pathinfo($_FILES['import']['name'], PATHINFO_EXTENSION);

        // If file extension is 'csv'
        if(!empty($_FILES['import']['name']) && $extension == 'csv'){

            $fp = fopen($_FILES['import']['tmp_name'],'r');

            // Skipping header row
            fgetcsv($fp);

            while(($csvData = fgetcsv($fp)) !== FALSE){
                $csvData = array_map('utf8_encode', $csvData);

                // Row column length
                $dataLen = count($csvData);

                // Skip row if length != 6
                if( !($dataLen == 6) ) {
                    continue;
                }

                // Assign value to variables
                $email = trim($csvData[0]);
                $first_name = trim($csvData[1]);
                $last_name = trim($csvData[2]);
                $canLogin = trim($csvData[5]);
                $name = $first_name . ' ' . $last_name;

                // Insert data to users table
                if($canLogin == 'yes'){

                    // Check if any duplicate email
                    if ($this->MainModel->isDuplicate($email)) {
                        $errorArr[] = $email;
                        $str = implode (", ", $errorArr);
                        $errorMessage = '<span style="color:#b80e0e;">But, some data email already exists ( ' . $str . ' )</span>';
                        continue;
                    }

                    $role = trim($csvData[3]);
                    $password = trim($csvData[4]);

                    $hashed = $this->hashPassword($password);

                    $data = array(
                        'email' => $email,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'role' => $role,
                        'password' => $hashed,
                    );

                    $this->MainModel->addUser($data);
                }

                // Check if any duplicate name of QR code
                if ($this->QrModel->isDuplicateQr($name)) {
                    $errorArrQr[] = $name;
                    $strQr = implode (", ", $errorArrQr);
                    $errorMessageQr = '<span style="color:#b80e0e;"> Also, some data name already exists ( ' . $strQr . ' )</span>';
                    continue;
                }

                // Insert data to QR code
                $data = array(
                    'name' => $name,
                );

                $this->QrModel->saveFromCsv($data);
            }

            $this->session->set_flashdata('success_message', 'Imported was success! ' . $errorMessage . ' ' . $errorMessageQr);
            redirect(site_url() . 'qr/generateQr');
        }

        $this->session->set_flashdata('flash_message', 'Please select CSV file.');
        redirect(site_url() . 'qr/generateQr');
    }
}
