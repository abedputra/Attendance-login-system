<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
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
        $data['title'] = "User List";

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
            $this->load->view('user/user', $data);
        } else {
            redirect(site_url() . 'main/');
        }
    }

    public function deleteuser($id)
    {
        $data = $this->session->userdata;
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        //check is admin or not
        if ($dataLevel == "is_admin") {
            $getDelete = $this->user_model->deleteUser($id);

            if ($getDelete == false) {
                $this->session->set_flashdata('flash_message', 'Error, cant delete the user!');
            } else if ($getDelete == true) {
                $this->session->set_flashdata('success_message', 'Delete user was successful.');
            } else {
                $this->session->set_flashdata('flash_message', 'Someting Error!');
            }
            redirect(site_url() . 'user/');
        } else {
            redirect(site_url() . 'main/');
        }
    }

    public function changerole($id) //level user
    {
        $data = $this->session->userdata;
        //check user level
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        $data['title'] = "Change Level Admin";
        $data['groups'] = $this->user_model->getUserDataById($id);
        $data['id'] = $id;

        //check is admin or not
        if ($dataLevel == "is_admin") {

            $this->form_validation->set_rules('email', 'Your Email', 'required');
            $this->form_validation->set_rules('level', 'User Level', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('template/header', $data);
                $this->load->view('template/navbar', $data);
                $this->load->view('template/container');
                $this->load->view('user/changelevel', $data);
                $this->load->view('template/footer');
            } else {
                $cleanPost['email'] = $this->input->post('email');
                $cleanPost['level'] = $this->input->post('level');
                if (!$this->user_model->updateUserLevel($cleanPost)) {
                    $this->session->set_flashdata('flash_message', 'There was a problem updating the level user');
                } else {
                    $this->session->set_flashdata('success_message', 'The level user has been updated.');
                    redirect(site_url() . 'user/');
                }

            }
        } else {
            redirect(site_url() . 'main/');
        }
    }

    public function adduser()
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

            $this->form_validation->set_rules('firstname', 'First Name', 'required');
            $this->form_validation->set_rules('lastname', 'Last Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('role', 'role', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
            $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

            $data['title'] = "Add User";

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('template/header', $data);
                $this->load->view('template/navbar');
                $this->load->view('template/container');
                $this->load->view('user/adduser', $data);
                $this->load->view('template/footer');
            } else {
                if ($this->user_model->isDuplicate($this->input->post('email'))) {
                    $this->session->set_flashdata('flash_message', 'User email already exists');
                    redirect(site_url() . 'user/adduser');
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
                    if (!$this->user_model->addUser($cleanPost)) {
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

    public function banuser($id) //ban or unban user
    {
        $data = $this->session->userdata;
        //check user level
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        $data['title'] = "Ban User";
        $data['groups'] = $this->user_model->getUserDataById($id);
        $data['id'] = $id;

        //check is admin or not
        if ($dataLevel == "is_admin") {

            $this->form_validation->set_rules('email', 'Your Email', 'required');
            $this->form_validation->set_rules('banuser', 'Ban or Unban', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('template/header', $data);
                $this->load->view('template/navbar', $data);
                $this->load->view('template/container');
                $this->load->view('user/banuser', $data);
                $this->load->view('template/footer');
            } else {
                $cleanPost['email'] = $this->input->post('email');
                $cleanPost['banuser'] = $this->input->post('banuser');
                if (!$this->user_model->updateUserban($cleanPost)) {
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

    public function dataTableJson()
    {
        $this->load->library('datatables');
        $this->datatables->select('*, users.id as id');
        $this->datatables->from('users');
        $this->datatables->add_column('delete', anchor('user/deleteuser/$1', 'Delete', array('class' => 'btn btn-danger btn-sm')), 'id');
        $this->datatables->add_column('change_role', anchor('user/changerole/$1', 'Change Role', array('class' => 'btn btn-primary btn-sm')), 'id');
        $this->datatables->add_column('ban_user', anchor('user/banuser/$1', 'Ban User', array('class' => 'btn btn-success btn-sm')), 'id');

        // return data
        echo $this->datatables->generate();

    }
}
