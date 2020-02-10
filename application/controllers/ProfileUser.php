<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProfileUser extends CI_Controller
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
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }

        $data['title'] = "Profile";
        $this->load->view('template/header', $data);
        $this->load->view('template/navbar', $data);
        $this->load->view('template/container');
        $this->load->view('profile/profile', $data);
        $this->load->view('template/footer');
    }

    public function edituser() //edit user
    {
        $data = $this->session->userdata;
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }

        $dataInfo = array(
            'firstName' => $data['first_name'],
            'id' => $data['id'],
        );

        $data['title'] = "Change Password";
        $this->form_validation->set_rules('firstname', 'First Name', 'required');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'min_length[5]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'matches[password]');

        $data['groups'] = $this->user_model->getUserInfo($dataInfo['id']);

        $issetPass = $this->input->post('password');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('template/header', $data);
            $this->load->view('template/navbar', $data);
            $this->load->view('template/container');
            $this->load->view('profile/changeuser', $data);
        } else {
            $this->load->library('password');
            $post = $this->input->post(NULL, TRUE);
            $cleanPost = $this->security->xss_clean($post);
            $hashed = $this->password->create_hash($cleanPost['password']);
            $cleanPost['password'] = $hashed;
            $cleanPost['user_id'] = $dataInfo['id'];
            $cleanPost['email'] = $this->input->post('email');
            $cleanPost['firstname'] = $this->input->post('firstname');
            $cleanPost['lastname'] = $this->input->post('lastname');
            if ($issetPass) {
                unset($cleanPost['passconf']);
                if (!$this->user_model->updateProfile($cleanPost)) {
                    $this->session->set_flashdata('flash_message', 'There was a problem updating your profile');
                } else {
                    $this->session->set_flashdata('success_message', 'Your profile has been updated.');
                }
            } else {
                if (!$this->user_model->updateProfileUser($cleanPost)) {
                    $this->session->set_flashdata('flash_message', 'There was a problem updating your profile');
                } else {
                    $this->session->set_flashdata('success_message', 'Your profile has been updated.');
                }
            }
            redirect(site_url() . 'profileuser/edituser/');
        }
    }
}
