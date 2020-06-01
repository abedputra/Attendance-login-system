<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller
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
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
        $this->load->library('userlevel');
    }

    /**
     * View index page.
     *
     * @return void
     */
    public function index()
    {
        // User data from session
        $data = $this->session->userdata;
        if (empty($data)) {
            redirect(site_url() . 'main/login/');
        }

        // Check user level
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }

        // Init data show on view
        $data['title'] = 'Dashboard Admin';
        $data['js_to_load'] = array('Chart.js', 'main/index.js');

        if (empty($this->session->userdata['email'])) {
            redirect(site_url() . 'main/login/');
        } else {
            $this->load->view('template/header', $data);
            $this->load->view('template/navbar', $data);
            $this->load->view('template/container');
            $this->load->view('main/index', $data);
            $this->load->view('template/footer', $data);
        }
    }

    /**
     * Get data attendance today.
     *
     * @return array|false|string
     */
    public function getAttendanceToday()
    {
        $dataChart = [];
        // Init data show on view
        $result = $this->MainModel->getSettings();
        $data['start'] = $result->start_time;
        $data['timezone'] = $result->timezone;

        // Get date now base on timezone
        $now = new DateTime();
        $now->setTimezone(new DateTimezone($data['timezone']));
        $data['nowToday'] = $now->format('Y-m-d');
        $data['absent_today'] = $this->MainModel->getAbsentToday('', '', 'date', $data['nowToday']);
        if (!empty($data['absent_today'])) {
            $dataChart = [];
            foreach ($data['absent_today'] as $value) {
                $dataChart['name_chart'][] = $value->name;
                $dataChart['check_in_chart'][] = date('H.i', strtotime($value->in_time));
                $dataChart['late_time_chart'][] = date('H.i', strtotime($data['start']));
            }
            $dataChart = json_encode($dataChart);
        }
        print_r($dataChart);
    }

    /**
     * View login page.
     *
     * @return void
     */
    public function login()
    {
        $data = $this->session->userdata;
        if (!empty($data['email'])) {
            redirect(site_url() . 'main/');
        } else {
            $this->load->library('curl');
            $this->load->library('recaptcha');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');

            // Get recaptcha
            $result = $this->MainModel->getSettings();
            $recaptcha = $result->recaptcha;
            $data['recaptcha'] = $result->recaptcha;
            $data['title'] = 'Welcome Back! Please Login';

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('template_frontend/header', $data);
                $this->load->view('template_frontend/container');
                $this->load->view('main/login');
                $this->load->view('template_frontend/footer');
            } else {
                $post = $this->input->post();
                $clean = $this->security->xss_clean($post);
                $userInfo = $this->MainModel->checkLogin($clean);

                // Recaptcha
                // Check if recaptcha is on
                if ($recaptcha == 1) {
                    $recaptchaResponse = $this->input->post('g-recaptcha-response');
                    $userIp = $_SERVER['REMOTE_ADDR'];
                    $key = $this->recaptcha->secret;
                    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $key . '&response=' . $recaptchaResponse . '&remoteip=' . $userIp; //link
                    $response = $this->curl->simple_get($url);
                    $status = json_decode($response, true);

                    if (!$userInfo) {
                        $this->session->set_flashdata('flash_message', 'Wrong password or email.');
                        redirect(site_url() . 'main/login');
                    } elseif ($userInfo->banned_users == "ban") {
                        $this->session->set_flashdata('danger_message', 'You’re temporarily banned from our website!');
                        redirect(site_url() . 'main/login');
                    } elseif ($status['success'] && $userInfo && $userInfo->banned_users == 'unban') { // Recaptcha check, success login, ban or unban
                        foreach ($userInfo as $key => $val) {
                            $this->session->set_userdata($key, $val);
                        }
                        redirect(site_url() . 'main/');
                    } else {
                        //recaptcha failed
                        $this->session->set_flashdata('flash_message', 'Error...! Google Recaptcha UnSuccessful!');
                        redirect(site_url() . 'main/login/');
                        exit;
                    }
                    // check if Recaptcha is off
                } else {
                    if (!$userInfo) {
                        $this->session->set_flashdata('flash_message', 'Wrong password or email.');
                        redirect(site_url() . 'main/login');
                    } elseif ($userInfo->banned_users == 'ban') {
                        $this->session->set_flashdata('danger_message', 'You’re temporarily banned from our website!');
                        redirect(site_url() . 'main/login');
                    } elseif ($userInfo && $userInfo->banned_users == 'unban') { // Recaptcha check, success login, ban or unban
                        foreach ($userInfo as $key => $val) {
                            $this->session->set_userdata($key, $val);
                        }
                        redirect(site_url() . 'main/');
                    }
                }
            }
        }
    }

    /**
     * Logout.
     *
     * @return void
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect(site_url() . 'main/login/');
    }

    /**
     * Create a new user registration.
     *
     * @return void
     */
    public function register()
    {
        $data['title'] = 'Register to Our System';
        $this->load->library('curl');
        $this->load->library('recaptcha');
        $this->form_validation->set_rules('firstname', 'First Name', 'required');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        // Get recaptcha
        $result = $this->MainModel->getSettings();
        $recaptcha = $result->recaptcha;
        $data['recaptcha'] = $result->recaptcha;

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('template_frontend/header', $data);
            $this->load->view('template_frontend/container');
            $this->load->view('main/register', $data);
            $this->load->view('template_frontend/footer');
        } else {
            if ($this->MainModel->isDuplicate($this->input->post('email'))) {
                $this->session->set_flashdata('flash_message', 'User email already exists');
                redirect(site_url() . 'main/register');
            } else {
                $clean = $this->security->xss_clean($this->input->post(NULL, TRUE));

                // Recaptcha
                // Check if recaptcha is on
                if ($recaptcha == 1) {

                    // Recaptcha
                    $recaptchaResponse = $this->input->post('g-recaptcha-response');
                    $userIp = $_SERVER['REMOTE_ADDR'];
                    $key = $this->recaptcha->secret;
                    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $key . '&response=' . $recaptchaResponse . '&remoteip=' . $userIp; //link
                    $response = $this->curl->simple_get($url);
                    $status = json_decode($response, true);

                    // Recaptcha check
                    if ($status['success']) {
                        // Insert to database
                        $id = $this->MainModel->insertUser($clean);
                        $token = $this->MainModel->insertToken($id);

                        // Generate token url
                        $qstring = $this->base64url_encode($token);
                        $url = site_url() . 'main/complete/token/' . $qstring;
                        $link = '<a href="' . $url . '">' . $url . '</a>';

                        // Send to email
                        // Content
                        $message = '';
                        $message .= 'Hello, ' . $this->input->post('lastname') . '<br>';
                        $message .= '<br>';
                        $message .= 'Welcome! you have signed up with our website with the following information:<br>';
                        $message .= '<br>';
                        $message .= '<strong>Username : ' . $this->input->post('email') . '</strong><br>';
                        $message .= '<strong>Password : (Not Set) </strong><br>';
                        $message .= '<br>';
                        $message .= 'Before you can login, you need to activate and set your Password';
                        $message .= '<br>';
                        $message .= 'account by clicking on this link:';
                        $message .= '<br><br>';
                        $message .= $link . '<br>';
                        $message .= '<br>';
                        $message .= 'Thank You';

                        $to_email = $this->input->post('email'); //send to

                        // Load email library
                        $this->load->library('email');

                        $this->email->from($this->config->item('register'), 'Set Password ' . $this->input->post('firstname') . ' ' . $this->input->post('lastname')); //from sender, title email
                        $this->email->to($to_email);
                        $this->email->subject('Set Password Login');
                        $this->email->message($message);
                        $this->email->set_mailtype("html"); //type is HTML

                        // Sending mail
                        if ($this->email->send()) {
                            redirect(site_url() . 'main/successRegister/');
                        } else {
                            $this->session->set_flashdata('flash_message', 'There was a problem sending an email.');
                            exit;
                        }
                    } else {
                        // Recaptcha failed
                        $this->session->set_flashdata('flash_message', 'Error...! Google Recaptcha UnSuccessful!');
                        redirect(site_url() . 'main/register/');
                        exit;
                    }
                    // check if Recaptcha is off
                } else {
                    //insert to database
                    $id = $this->MainModel->insertUser($clean);
                    $token = $this->MainModel->insertToken($id);

                    //generate token
                    $qstring = $this->base64url_encode($token);
                    $url = site_url() . 'main/complete/token/' . $qstring;
                    $link = '<a href="' . $url . '">' . $url . '</a>';

                    // Send to email
                    // Content
                    $message = '';
                    $message .= 'Hello, ' . $this->input->post('lastname') . '<br>';
                    $message .= '<br>';
                    $message .= 'Welcome! you have signed up with our website with the following information:<br>';
                    $message .= '<br>';
                    $message .= '<strong>Username : ' . $this->input->post('email') . '</strong><br>';
                    $message .= '<strong>Password : (Not Set) </strong><br>';
                    $message .= '<br>';
                    $message .= 'Before you can login, you need to activate and set your Password';
                    $message .= '<br>';
                    $message .= 'account by clicking on this link:';
                    $message .= '<br><br>';
                    $message .= $link . '<br>';
                    $message .= '<br>';
                    $message .= 'Thank You';

                    $to_email = $this->input->post('email'); //send to

                    // Load email library
                    $this->load->library('email');
                    
                    $this->email->from($this->config->item('register'), 'Set Password ' . $this->input->post('firstname') . ' ' . $this->input->post('lastname')); //from sender, title email
                    $this->email->to($to_email);
                    $this->email->subject('Set Password Login');
                    $this->email->message($message);
                    $this->email->set_mailtype('html'); //type is HTML

                    // Sending mail
                    if ($this->email->send()) {
                        redirect(site_url() . 'main/successRegister/');
                    } else {
                        $this->session->set_flashdata('flash_message', 'There was a problem sending an email.');
                        exit;
                    }
                }
            }
        }
    }

    /**
     * Generate encode base64 url.
     *
     * @param  $data
     * @return string
     */
    public function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * View complete registration and user add password.
     *
     * @return void
     */
    public function complete()
    {
        $token = base64_decode($this->uri->segment(4));
        $cleanToken = $this->security->xss_clean($token);

        $user_info = $this->MainModel->isTokenValid($cleanToken); // Either false or array();

        if (!$user_info) {
            $this->session->set_flashdata('flash_message', 'Token is invalid or expired');
            redirect(site_url() . 'main/login');
        }
        $data = array(
            'firstName' => $user_info->first_name,
            'email' => $user_info->email,
            'user_id' => $user_info->id,
            'token' => $this->base64url_encode($token)
        );

        $data['title'] = 'Set the Password';
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('template_frontend/header', $data);
            $this->load->view('template_frontend/container');
            $this->load->view('main/complete', $data);
            $this->load->view('template_frontend/footer');
        } else {

            $this->load->library('password');
            $post = $this->input->post(NULL, TRUE);

            $cleanPost = $this->security->xss_clean($post);

            $hashed = $this->password->create_hash($cleanPost['password']);
            $cleanPost['password'] = $hashed;
            unset($cleanPost['passconf']);
            $userInfo = $this->MainModel->updateUserInfoComplete($cleanPost);

            if (!$userInfo) {
                $this->session->set_flashdata('flash_message', 'There was a problem updating your record');
                redirect(site_url() . 'main/login');
            }
            unset($userInfo->password);
            foreach ($userInfo as $key => $val) {
                $this->session->set_userdata($key, $val);
            }
            redirect(site_url() . 'main/');
        }
    }

    /**
     * View success registration.
     *
     * @return void
     */
    public function successRegister()
    {
        $data['title'] = 'Success Register';
        $this->load->view('template_frontend/header', $data);
        $this->load->view('template_frontend/container');
        $this->load->view('main/register_info');
        $this->load->view('template_frontend/footer');
    }

    /**
     * Reset password.
     *
     * @return void
     */
    public function forgot()
    {
        $data['title'] = 'Forgot Password';
        $this->load->library('curl');
        $this->load->library('recaptcha');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        // Recaptcha
        $result = $this->MainModel->getSettings();
        $recaptcha = $result->recaptcha;
        $data['recaptcha'] = $result->recaptcha;

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('template_frontend/header', $data);
            $this->load->view('template_frontend/container');
            $this->load->view('main/forgot', $data);
            $this->load->view('template_frontend/footer');
        } else {
            $email = $this->input->post('email');
            $clean = $this->security->xss_clean($email);
            $userInfo = $this->MainModel->getUserInfoByEmail($clean);

            if (!$userInfo) {
                $this->session->set_flashdata('flash_message', 'We cant find your email address');
                redirect(site_url() . 'main/login');
            }

            if ($userInfo->status != $this->status[1]) { // If status is not approved
                $this->session->set_flashdata('flash_message', 'Your account is not in approved status');
                redirect(site_url() . 'main/login');
            }

            // Recaptcha
            // Check if recaptcha is on
            if ($recaptcha == 1) {

                // Recaptcha
                $recaptchaResponse = $this->input->post('g-recaptcha-response');
                $userIp = $_SERVER['REMOTE_ADDR'];
                $key = $this->recaptcha->secret;
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $key . "&response=" . $recaptchaResponse . "&remoteip=" . $userIp; //link
                $response = $this->curl->simple_get($url);
                $status = json_decode($response, true);

                // Recaptcha check
                if ($status['success']) {

                    //generate token
                    $token = $this->MainModel->insertToken($userInfo->id);
                    $qstring = $this->base64url_encode($token);
                    $url = site_url() . 'main/resetPassword/token/' . $qstring;
                    $link = '<a href="' . $url . '">' . $url . '</a>';

                    //send to email
                    //content
                    $message = '';
                    $message .= 'Hello, ' . $this->input->post('lastname') . '<br>';
                    $message .= '<br>';
                    $message .= 'We\'ve generated a new password for you at your<br>';
                    $message .= 'request, you can use this new password with your username:<br>';
                    $message .= '<br>';
                    $message .= '<strong>Username : ' . $this->input->post('email') . '</strong><br>';
                    $message .= '<strong>Password : (Forgot Password) </strong><br>';
                    $message .= '<br>';
                    $message .= 'To reset your Password please, clicking on this link:';
                    $message .= '<br><br>';
                    $message .= $link . '<br>';
                    $message .= '<br>';
                    $message .= 'Thank You';

                    $to_email = $this->input->post('email'); //send to

                    //Load email library
                    $this->load->library('email');

                    $this->email->from($this->config->item('forgot'), 'Reset Password! ' . $this->input->post('firstname') . ' ' . $this->input->post('lastname')); //from sender, title email
                    $this->email->to($to_email);
                    $this->email->subject('Reset Password');
                    $this->email->message($message);
                    $this->email->set_mailtype("html"); //type is HTML

                    //Sending mail
                    if ($this->email->send()) {
                        redirect(site_url() . 'main/successResetPassword/');
                    } else {
                        $this->session->set_flashdata('flash_message', 'There was a problem sending an email.');
                        exit;
                    }
                } else {
                    //recaptcha failed
                    $this->session->set_flashdata('flash_message', 'Error...! Google Recaptcha UnSuccessful!');
                    redirect(site_url() . 'main/register/');
                    exit;
                }
            } else {
                //generate token
                $token = $this->MainModel->insertToken($userInfo->id);
                $qstring = $this->base64url_encode($token);
                $url = site_url() . 'main/resetPassword/token/' . $qstring;
                $link = '<a href="' . $url . '">' . $url . '</a>';

                //send to email
                //content
                $message = '';
                $message .= 'Hello, ' . $this->input->post('lastname') . '<br>';
                $message .= '<br>';
                $message .= 'We\'ve generated a new password for you at your<br>';
                $message .= 'request, you can use this new password with your username:<br>';
                $message .= '<br>';
                $message .= '<strong>Username : ' . $this->input->post('email') . '</strong><br>';
                $message .= '<strong>Password : (Forgot Password) </strong><br>';
                $message .= '<br>';
                $message .= 'To reset your Password please, clicking on this link:';
                $message .= '<br><br>';
                $message .= $link . '<br>';
                $message .= '<br>';
                $message .= 'Thank You';

                $to_email = $this->input->post('email'); //send to

                //Load email library
                $this->load->library('email');

                $this->email->from($this->config->item('forgot'), 'Reset Password! ' . $this->input->post('firstname') . ' ' . $this->input->post('lastname')); //from sender, title email
                $this->email->to($to_email);
                $this->email->subject('Reset Password');
                $this->email->message($message);
                $this->email->set_mailtype("html"); //type is HTML

                //Sending mail
                if ($this->email->send()) {
                    redirect(site_url() . 'main/successResetPassword/');
                } else {
                    $this->session->set_flashdata('flash_message', 'There was a problem sending an email.');
                    exit;
                }
            }
        }

    }

    /**
     * View reset password page.
     *
     * @return void
     */
    public function resetPassword()
    {
        $token = $this->base64url_decode($this->uri->segment(4));
        $cleanToken = $this->security->xss_clean($token);
        $user_info = $this->MainModel->isTokenValid($cleanToken); //either false or array();

        if (!$user_info) {
            $this->session->set_flashdata('flash_message', 'Token is invalid or expired');
            redirect(site_url() . 'main/login');
        }
        $data = array(
            'firstName' => $user_info->first_name,
            'email' => $user_info->email,
            //'user_id'=>$user_info->id,
            'token' => $this->base64url_encode($token)
        );

        $data['title'] = 'Reset Password';
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('template/header', $data);
            $this->load->view('template/container');
            $this->load->view('main/resetPassword', $data);
            $this->load->view('template/footer');
        } else {
            $this->load->library('password');
            $post = $this->input->post(NULL, TRUE);
            $cleanPost = $this->security->xss_clean($post);
            $hashed = $this->password->create_hash($cleanPost['password']);
            $cleanPost['password'] = $hashed;
            $cleanPost['user_id'] = $user_info->id;
            unset($cleanPost['passconf']);
            if (!$this->MainModel->updatePassword($cleanPost)) {
                $this->session->set_flashdata('flash_message', 'There was a problem updating your password');
            } else {
                $this->session->set_flashdata('success_message', 'Your password has been updated. You may now login');
            }
            redirect(site_url() . 'main/login');
        }
    }

    /**
     * Decode base64 url.
     *
     * @param  $data
     * @return string
     */
    public function base64url_decode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    /**
     * View reset password.
     *
     * @return void
     */
    public function successResetPassword()
    {
        $data['title'] = 'Success Reset Password';
        $this->load->view('template_frontend/header', $data);
        $this->load->view('template_frontend/container');
        $this->load->view('main/reset_pass_info');
        $this->load->view('template_frontend/footer');
    }

    /**
     * Function attendance check-in or check-out.
     *
     * @return void
     */
    public function absent_attendance()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            // Get key from request
            $key = $this->input->post('key');

            // Get settings data
            $result = $this->MainModel->getSettings();
            $data['many_employee'] = $result->many_employee;
            $data['start'] = $result->start_time;
            $data['out'] = $result->out_time;
            $data['key'] = $result->key_insert;

            // Check if key not empty
            if (!empty($key)) {
                if ($key == $data['key']) {

                    $Q = $this->security->xss_clean($this->input->post('q', TRUE));
                    $name = $this->security->xss_clean($this->input->post('name', TRUE));
                    $date = $this->security->xss_clean($this->input->post('date', TRUE));
                    $location = $this->security->xss_clean($this->input->post('location', TRUE));

                    // Check command is in our out
                    if ($Q == 'in') {

                        $in_time = $this->security->xss_clean($this->input->post('in_time', TRUE));
                        $change_in_time = strtotime($in_time);

                        // Get late time
                        $get_late_time = $this->getTime($change_in_time - strtotime($data['start']));
                        $late_time = "$get_late_time[0]:$get_late_time[1]:$get_late_time[2]";

                        $allData = array(
                            'name' => $name,
                            'date' => $date,
                            'in_location' => $location,
                            'in_time' => $in_time,
                            'late_time' => $late_time
                        );

                        $insertData = $this->MainModel->insertAbsent($allData);
                        if ($insertData == true) {
                            echo 'Success!';
                        } else {
                            echo 'Error! Something Went Wrong!';
                        }
                    } else if ($Q == 'out') {

                        $out_time = $this->security->xss_clean($this->input->post('out_time', TRUE));
                        $change_out_time = strtotime($out_time);

                        // Open in_time from database
                        $getDataIn['in_time'] = $this->MainModel->getDataAbsent('name', $name, 'date', $date);
                        $get_in_database = strtotime($getDataIn['in_time']);

                        // Get work hour
                        $get_work_hour = $this->getTime($change_out_time - $get_in_database);
                        $work_hour = "$get_work_hour[0]:$get_work_hour[1]:$get_work_hour[2]";

                        // Get over time
                        $get_over_time = $this->getTime($change_out_time - strtotime($data['out']));
                        if ($get_in_database > strtotime($data['out']) || $change_out_time < strtotime($data['out'])) {
                            $over_time = '00:00:00';
                        }
                        else {
                            $over_time = "$get_over_time[0]:$get_over_time[1]:$get_over_time[2]";
                        }

                        // Early out time
                        $get_early_out_time = $this->getTime(strtotime($data['out']) - $change_out_time);
                        if ($get_in_database > strtotime($data['out'])) {
                            $early_out_time = '00:00:00';
                        }
                        else {
                            $early_out_time = "$get_early_out_time[0]:$get_early_out_time[1]:$get_early_out_time[2]";
                        }

                        // Add data
                        $allData = array(
                            'name' => $name,
                            'date' => $date,
                            'out_location' => $location,
                            'out_time' => $out_time,
                            'work_hour' => $work_hour,
                            'over_time' => $over_time,
                            'early_out_time' => $early_out_time
                        );

                        $updateData = $this->MainModel->updateAbsent($allData);
                        if ($updateData == true) {
                            echo 'Success!';
                        } else {
                            echo 'Error! Something Went Wrong!';
                        }
                    } else {
                        echo 'Error! Wrong Command!';
                    }
                } else {
                    echo 'The KEY is Wrong!';
                }
            } else {
                echo 'Please Setting KEY First!';
            }
        } else {
            echo "You can't access this page!";
        }
    }

    /**
     * Function get time.
     *
     * @param $total
     * @return array
     */
    public function getTime($total)
    {
        $hours = (int)($total / 3600);
        $seconds_remain = ($total - ($hours * 3600));
        $minutes = (int)($seconds_remain / 60);
        $seconds = ($seconds_remain - ($minutes * 60));
        return array($hours, $minutes, $seconds);
    }

    protected function _islocal()
    {
        return strpos($_SERVER['HTTP_HOST'], 'local');
    }
}
