<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfileUser extends CI_Controller
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
        $this->load->model('ProfileModel', 'ProfileModel', TRUE);
        $this->load->model('MainModel', 'MainModel', TRUE);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
        $this->load->library('userlevel');
    }

    /**
     * View index profile page.
     *
     * @return void
     */
    public function index()
    {
        $data = $this->session->userdata;
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }

        $data['title'] = 'Profile';
        $this->load->view('template/header', $data);
        $this->load->view('template/navbar', $data);
        $this->load->view('template/container');
        $this->load->view('profile/index', $data);
        $this->load->view('template/footer');
    }

    /**
     * View edit profile page.
     *
     * @return void
     */
    public function edit()
    {
        $data = $this->session->userdata;
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }

        $dataInfo = array(
            'firstName' => $data['first_name'],
            'id' => $data['id'],
        );

        $data['title'] = 'Edit Profile';
        $this->form_validation->set_rules('firstname', 'First Name', 'required');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        $issetPass = $this->input->post('password');
        if ($issetPass) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[5]');
            $this->form_validation->set_rules('passconf', 'Password Confirmation', 'matches[password]');
        }

        $data['groups'] = $this->MainModel->getUserInfo($dataInfo['id']);

        // Load the js
        $data['js_to_load'] = array(
            'profile/index.js'
        );

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('template/header', $data);
            $this->load->view('template/navbar', $data);
            $this->load->view('template/container');
            $this->load->view('profile/edit', $data);
            $this->load->view('template/footer', $data);
        } else {
            $this->load->library('password');
            $post = $this->input->post(NULL, TRUE);
            $cleanPost = $this->security->xss_clean($post);

            // Is password is isset
            if ($issetPass) {
                $hashed = $this->password->create_hash($cleanPost['password']);
                $cleanPost['password'] = $hashed;
                unset($cleanPost['passconf']);
                $cleanPost['withPassword'] = 'yes';
            } else {
                $cleanPost['withPassword'] = 'no';
            }

            $cleanPost['user_id'] = $dataInfo['id'];
            $cleanPost['email'] = $this->input->post('email');
            $cleanPost['firstname'] = $this->input->post('firstname');
            $cleanPost['lastname'] = $this->input->post('lastname');

            unset($cleanPost['passconf']);
            if (!$this->ProfileModel->updateProfile($cleanPost)) {
                $this->session->set_flashdata('flash_message', 'There was a problem updating your profile');
            } else {
                $this->session->set_flashdata('success_message', 'Your profile has been updated.');
            }
            redirect(site_url() . 'ProfileUser/edit/');
        }
    }
}
