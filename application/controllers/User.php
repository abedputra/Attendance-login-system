<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
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
        $this->load->model('UserModel', 'UserModel', TRUE);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
        $this->load->library('userlevel');
    }

    /**
     * View users index page.
     *
     * @return void
     */
    public function index()
    {
        $data = $this->session->userdata;
        $data['title'] = 'User List';

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
            'user/index.js'
        );

        //check is admin or not
        if ($dataLevel == 'is_admin') {
            $this->load->view('template/header', $data);
            $this->load->view('template/navbar', $data);
            $this->load->view('template/container');
            $this->load->view('user/user', $data);
            $this->load->view('template/footer', $data);
        } else {
            redirect(site_url() . 'main/');
        }
    }

    /**
     * Function delete user.
     *
     * @param $id
     * @return void
     */
    public function delete($id)
    {
        $data = $this->session->userdata;
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        //check is admin or not
        if ($dataLevel == 'is_admin') {
            $getDelete = $this->UserModel->deleteUser($id);

            if ($getDelete == false) {
                $this->session->set_flashdata('flash_message', 'Error, cant delete the user!');
            } else if ($getDelete == true) {
                $this->session->set_flashdata('success_message', 'Delete user was successful.');
            } else {
                $this->session->set_flashdata('flash_message', 'Something Error!');
            }
            redirect(site_url() . 'user/');
        } else {
            redirect(site_url() . 'main/');
        }
    }

    /**
     * Function edit user.
     *
     * @param $id
     * @return void
     */
    public function edit($id)
    {
        $data = $this->session->userdata;
        //check user level
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        $data['title'] = 'Edit User';
        $data['groups'] = $this->MainModel->getUserDataById($id);
        $data['id'] = $id;

        //check is admin or not
        if ($dataLevel == 'is_admin') {

            $this->form_validation->set_rules('firstname', 'First Name', 'required|alpha');
            $this->form_validation->set_rules('lastname', 'Last Name', 'required|alpha');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('role', 'role', 'required');

            $issetPass = $this->input->post('password');
            if ($issetPass) {
                $this->form_validation->set_rules('password', 'Password', 'min_length[5]');
                $this->form_validation->set_rules('passconf', 'Password Confirmation', 'matches[password]');
            }

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('template/header', $data);
                $this->load->view('template/navbar', $data);
                $this->load->view('template/container');
                $this->load->view('user/edit_user', $data);
                $this->load->view('template/footer');
            } else {
                $this->load->library('password');
                $issetPass = $this->input->post('password');
                $post = $this->input->post(NULL, TRUE);
                $cleanPost = $this->security->xss_clean($post);


                $cleanPost['id'] = $data['id'];
                $cleanPost['email'] = $this->input->post('email');
                $cleanPost['role'] = $this->input->post('role');
                $cleanPost['firstname'] = $this->input->post('firstname');
                $cleanPost['lastname'] = $this->input->post('lastname');

                // Is password is isset
                if ($issetPass) {
                    $hashed = $this->password->create_hash($cleanPost['password']);
                    $cleanPost['password'] = $hashed;
                    unset($cleanPost['passconf']);
                    $cleanPost['withPassword'] = 'yes';
                }else{
                    $cleanPost['withPassword'] = 'no';
                }

                if (!$this->UserModel->updateUserInfo($cleanPost)) {
                    $this->session->set_flashdata('flash_message', 'There was a problem updating the user');
                } else {
                    $this->session->set_flashdata('success_message', 'The user has been updated.');
                    redirect(site_url() . 'user/');
                }

            }
        } else {
            redirect(site_url() . 'main/');
        }
    }

    /**
     * Function add new user.
     *
     * @return void
     */
    public function add()
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

            $this->form_validation->set_rules('firstname', 'First Name', 'required|alpha');
            $this->form_validation->set_rules('lastname', 'Last Name', 'required|alpha');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('role', 'role', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
            $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

            $data['title'] = 'Add User';

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('template/header', $data);
                $this->load->view('template/navbar');
                $this->load->view('template/container');
                $this->load->view('user/add_user', $data);
                $this->load->view('template/footer');
            } else {
                if ($this->MainModel->isDuplicate($this->input->post('email'))) {
                    $this->session->set_flashdata('flash_message', 'User email already exists');
                    redirect(site_url() . 'user/add');
                } else {
                    $this->load->library('password');
                    $post = $this->input->post(NULL, TRUE);
                    $cleanPost = $this->security->xss_clean($post);
                    $hashed = $this->password->create_hash($cleanPost['password']);

                    $cleanPost['email'] = $this->input->post('email');
                    $cleanPost['role'] = $this->input->post('role');
                    $cleanPost['firstname'] = $this->input->post('firstname');
                    $cleanPost['lastname'] = $this->input->post('lastname');
                    $cleanPost['password'] = $hashed;
                    unset($cleanPost['passconf']);

                    //insert to database
                    if (!$this->MainModel->addUser($cleanPost)) {
                        $this->session->set_flashdata('flash_message', 'There was a problem updating your profile');
                    } else {
                        $this->session->set_flashdata('success_message', 'Success adding user.');
                    }
                    redirect(site_url() . 'user/');
                };
            }
        } else {
            redirect(site_url() . 'main/');
        }
    }

    /**
     * Function ban user.
     *
     * @param $id
     * @return void
     */
    public function banUser($id) //ban or unban user
    {
        $data = $this->session->userdata;
        //check user level
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        $data['title'] = 'Ban User';
        $data['groups'] = $this->MainModel->getUserDataById($id);
        $data['id'] = $id;

        //check is admin or not
        if ($dataLevel == 'is_admin') {

            $this->form_validation->set_rules('email', 'Your Email', 'required');
            $this->form_validation->set_rules('banuser', 'Ban or Unban', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('template/header', $data);
                $this->load->view('template/navbar', $data);
                $this->load->view('template/container');
                $this->load->view('user/ban_user', $data);
                $this->load->view('template/footer');
            } else {
                $cleanPost['email'] = $this->input->post('email');
                $cleanPost['banuser'] = $this->input->post('banuser');
                if (!$this->UserModel->updateUserBan($cleanPost)) {
                    $this->session->set_flashdata('flash_message', 'There was a problem updating');
                } else {
                    $this->session->set_flashdata('success_message', 'The status user has been updated.');
                    redirect(site_url() . 'user/');
                }

            }
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
        echo $this->UserModel->getDataTables();
    }
}
