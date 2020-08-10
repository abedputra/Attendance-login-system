<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends CI_Controller
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
        $this->load->model('SettingsModel', 'SettingsModel', TRUE);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
        $this->load->library('userlevel');
    }

    /**
     * View setting index page.
     *
     * @return void
     */
    public function index()
    {
        $data = $this->session->userdata;
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }
        $this->load->helper('url');
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        $data['title'] = 'Settings';
        $this->form_validation->set_rules('start_time', 'Start', 'required');
        $this->form_validation->set_rules('out_time', 'Out', 'required');
        $this->form_validation->set_rules('many_employee', 'How many employee', 'required');
        $this->form_validation->set_rules('key', 'KEY', 'required');
        $this->form_validation->set_rules('timezone', 'Timezone', 'required');

        $result = $this->MainModel->getSettings();
        $data['id'] = $result->id;
        $data['many_employee'] = $result->many_employee;
        $data['start'] = $result->start_time;
        $data['out'] = $result->out_time;
        $data['recaptcha'] = $result->recaptcha;

        if (!empty($data['timezone'] = $result->timezone)) {
            $data['timezonevalue'] = $result->timezone;
            $data['timezone'] = $result->timezone;
        } else {
            $data['timezonevalue'] = '';
            $data['timezone'] = 'Select a time zone';
        }

        if (!empty($data['key'] = $result->key_insert)) {
            $data['key'] = $result->key_insert;
        }

        // Load the js
        $data['js_to_load'] = array(
            'jquery.timepicker.min.js',
            'setting/index.js'
        );

        if ($dataLevel == 'is_admin') {
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('template/header', $data);
                $this->load->view('template/navbar', $data);
                $this->load->view('template/container');
                $this->load->view('settings/settings', $data);
                $this->load->view('template/footer', $data);
            } else {
                $post = $this->input->post(NULL, TRUE);
                $cleanPost = $this->security->xss_clean($post);

                $cleanPost['id'] = $this->input->post('id');
                $cleanPost['start_time'] = $this->input->post('start_time');
                $cleanPost['out_time'] = $this->input->post('out_time');
                $cleanPost['many_employee'] = $this->input->post('many_employee');
                $cleanPost['key'] = $this->input->post('key');
                $cleanPost['timezone'] = $this->input->post('timezone');
                $cleanPost['recaptcha'] = $this->input->post('recaptcha');

                // Save settings
                if (!$this->SettingsModel->settings($cleanPost)) {
                    $this->session->set_flashdata('flash_message', 'There was a problem updating your data!');
                } else {
                    $this->session->set_flashdata('success_message', 'Your data has been updated.');
                }
                redirect(site_url() . 'settings/');
            }
        }
    }
}
