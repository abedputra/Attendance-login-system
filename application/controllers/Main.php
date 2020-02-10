<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller
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
        //user data from session
        $data = $this->session->userdata;
        if (empty($data)) {
            redirect(site_url() . 'main/login/');
        }

        //check user level
        if (empty($data['role'])) {
            redirect(site_url() . 'main/login/');
        }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        $data['title'] = "Dashboard Admin";
        $result = $this->user_model->getHowManyPeople();
        $data['many_employee'] = $result->many_employee;
        $data['start'] = $result->start_time;
        $data['out'] = $result->out_time;
        $data['timezone'] = $result->timezone;

        $now = new DateTime();
        $now->setTimezone(new DateTimezone($data['timezone'])); //change your city
        $data['nowToday'] = $now->format('Y-m-d');

        $data['count_absent_today'] = $this->user_model->getAbsentToday("", "", "date", $data['nowToday']);
        $data['count_late_today'] = $this->user_model->getAbsentToday("late_time >", "00:00:00", "date", $data['nowToday']);

        if (empty($this->session->userdata['email'])) {
            redirect(site_url() . 'main/login/');
        } else {
            $this->load->view('template/header', $data);
            $this->load->view('template/navbar', $data);
            $this->load->view('template/container');
            $this->load->view('main/index', $data);
            $this->load->view('template/footer');
        }
    }

    public function register()
    {
        $data['title'] = "Register to Our System";
        $this->load->library('curl');
        $this->load->library('recaptcha');
        $this->form_validation->set_rules('firstname', 'First Name', 'required');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        //recaptcha
        $result = $this->user_model->getHowManyPeople();
        $recaptcha = $result->recaptcha;
        $data['recaptcha'] = $result->recaptcha;

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('main/register', $data);
        } else {
            if ($this->user_model->isDuplicate($this->input->post('email'))) {
                $this->session->set_flashdata('flash_message', 'User email already exists');
                redirect(site_url() . 'main/register');
            } else {
                $clean = $this->security->xss_clean($this->input->post(NULL, TRUE));

                // recaptcha
                // check if recaptcha is on
                if ($recaptcha == 1) {

                    //recaptcha
                    $recaptchaResponse = $this->input->post('g-recaptcha-response');
                    $userIp = $_SERVER['REMOTE_ADDR'];
                    $key = $this->recaptcha->secret;
                    $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $key . "&response=" . $recaptchaResponse . "&remoteip=" . $userIp; //link
                    $response = $this->curl->simple_get($url);
                    $status = json_decode($response, true);

                    //recaptcha check
                    if ($status['success']) {
                        //insert to database
                        $id = $this->user_model->insertUser($clean);
                        $token = $this->user_model->insertToken($id);

                        //generate token
                        $qstring = $this->base64url_encode($token);
                        $url = site_url() . 'main/complete/token/' . $qstring;
                        $link = '<a href="' . $url . '">' . $url . '</a>';

                        //send to email
                        //content
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

                        //Load email library
                        $this->load->library('email');

                        $this->email->from($this->config->item('register'), 'Set Password ' . $this->input->post('firstname') . ' ' . $this->input->post('lastname')); //from sender, title email
                        $this->email->to($to_email);
                        $this->email->subject('Set Password Login');
                        $this->email->message($message);
                        $this->email->set_mailtype("html"); //type is HTML

                        //Sending mail
                        if ($this->email->send()) {
                            redirect(site_url() . 'main/successregister/');
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
                    // check if recaptcha is off
                } else {
                    //insert to database
                    $id = $this->user_model->insertUser($clean);
                    $token = $this->user_model->insertToken($id);

                    //generate token
                    $qstring = $this->base64url_encode($token);
                    $url = site_url() . 'main/complete/token/' . $qstring;
                    $link = '<a href="' . $url . '">' . $url . '</a>';

                    //send to email
                    //content
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

                    //Load email library
                    $this->load->library('email');

                    $this->email->from($this->config->item('register'), 'Set Password ' . $this->input->post('firstname') . ' ' . $this->input->post('lastname')); //from sender, title email
                    $this->email->to($to_email);
                    $this->email->subject('Set Password Login');
                    $this->email->message($message);
                    $this->email->set_mailtype("html"); //type is HTML

                    //Sending mail
                    if ($this->email->send()) {
                        redirect(site_url() . 'main/successregister/');
                    } else {
                        $this->session->set_flashdata('flash_message', 'There was a problem sending an email.');
                        exit;
                    }
                }
            };
        }
    }

    public function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function successregister()
    {
        $data['title'] = "Success Register";
        $this->load->view('template/header', $data);
        $this->load->view('template/container');
        $this->load->view('main/register-info');
        $this->load->view('template/footer');
    }

    public function successresetpassword()
    {
        $data['title'] = "Success Reset Password";
        $this->load->view('template/header', $data);
        $this->load->view('template/container');
        $this->load->view('main/reset-pass-info');
        $this->load->view('template/footer');
    }

    public function complete()
    {
        $token = base64_decode($this->uri->segment(4));
        $cleanToken = $this->security->xss_clean($token);

        $user_info = $this->user_model->isTokenValid($cleanToken); //either false or array();

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

        $data['title'] = "Set the Password";

        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('template/header', $data);
            $this->load->view('template/container');
            $this->load->view('main/complete', $data);
            $this->load->view('template/footer');
        } else {

            $this->load->library('password');
            $post = $this->input->post(NULL, TRUE);

            $cleanPost = $this->security->xss_clean($post);

            $hashed = $this->password->create_hash($cleanPost['password']);
            $cleanPost['password'] = $hashed;
            unset($cleanPost['passconf']);
            $userInfo = $this->user_model->updateUserInfo($cleanPost);

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

            //recaptcha
            $result = $this->user_model->getHowManyPeople();
            $recaptcha = $result->recaptcha;
            $data['recaptcha'] = $result->recaptcha;
            $data['title'] = "Welcome Back! Please Login";

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/login', $data);
            } else {
                $post = $this->input->post();
                $clean = $this->security->xss_clean($post);
                $userInfo = $this->user_model->checkLogin($clean);

                // recaptcha
                // check if recaptcha is on
                if ($recaptcha == 1) {
                    $recaptchaResponse = $this->input->post('g-recaptcha-response');
                    $userIp = $_SERVER['REMOTE_ADDR'];
                    $key = $this->recaptcha->secret;
                    $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $key . "&response=" . $recaptchaResponse . "&remoteip=" . $userIp; //link
                    $response = $this->curl->simple_get($url);
                    $status = json_decode($response, true);

                    if (!$userInfo) {
                        $this->session->set_flashdata('flash_message', 'Wrong password or email.');
                        redirect(site_url() . 'main/login');
                    } elseif ($userInfo->banned_users == "ban") {
                        $this->session->set_flashdata('danger_message', 'You’re temporarily banned from our website!');
                        redirect(site_url() . 'main/login');
                    } elseif ($status['success'] && $userInfo && $userInfo->banned_users == "unban") { //recaptcha check, success login, ban or unban
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
                    // check if recaptcha is off
                } else {
                    if (!$userInfo) {
                        $this->session->set_flashdata('flash_message', 'Wrong password or email.');
                        redirect(site_url() . 'main/login');
                    } elseif ($userInfo->banned_users == "ban") {
                        $this->session->set_flashdata('danger_message', 'You’re temporarily banned from our website!');
                        redirect(site_url() . 'main/login');
                    } elseif ($userInfo && $userInfo->banned_users == "unban") { //recaptcha check, success login, ban or unban
                        foreach ($userInfo as $key => $val) {
                            $this->session->set_userdata($key, $val);
                        }
                        redirect(site_url() . 'main/');
                    }
                }
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(site_url() . 'main/login/');
    }

    public function forgot()
    {
        $data['title'] = "Forgot Password";
        $this->load->library('curl');
        $this->load->library('recaptcha');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        //recaptcha
        $result = $this->user_model->getHowManyPeople();
        $recaptcha = $result->recaptcha;
        $data['recaptcha'] = $result->recaptcha;

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('main/forgot', $data);
        } else {
            $email = $this->input->post('email');
            $clean = $this->security->xss_clean($email);
            $userInfo = $this->user_model->getUserInfoByEmail($clean);

            if (!$userInfo) {
                $this->session->set_flashdata('flash_message', 'We cant find your email address');
                redirect(site_url() . 'main/login');
            }

            if ($userInfo->status != $this->status[1]) { //if status is not approved
                $this->session->set_flashdata('flash_message', 'Your account is not in approved status');
                redirect(site_url() . 'main/login');
            }

            // recaptcha
            // check if recaptcha is on
            if ($recaptcha == 1) {

                //recaptcha
                $recaptchaResponse = $this->input->post('g-recaptcha-response');
                $userIp = $_SERVER['REMOTE_ADDR'];
                $key = $this->recaptcha->secret;
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $key . "&response=" . $recaptchaResponse . "&remoteip=" . $userIp; //link
                $response = $this->curl->simple_get($url);
                $status = json_decode($response, true);

                //recaptcha check
                if ($status['success']) {

                    //generate token
                    $token = $this->user_model->insertToken($userInfo->id);
                    $qstring = $this->base64url_encode($token);
                    $url = site_url() . 'main/reset_password/token/' . $qstring;
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
                        redirect(site_url() . 'main/successresetpassword/');
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
                $token = $this->user_model->insertToken($userInfo->id);
                $qstring = $this->base64url_encode($token);
                $url = site_url() . 'main/reset_password/token/' . $qstring;
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
                    redirect(site_url() . 'main/successresetpassword/');
                } else {
                    $this->session->set_flashdata('flash_message', 'There was a problem sending an email.');
                    exit;
                }
            }
        }

    }

    public function reset_password()
    {
        $token = $this->base64url_decode($this->uri->segment(4));
        $cleanToken = $this->security->xss_clean($token);
        $user_info = $this->user_model->isTokenValid($cleanToken); //either false or array();

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

        $data['title'] = "Reset Password";
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('template/header', $data);
            $this->load->view('template/container');
            $this->load->view('main/reset_password', $data);
            $this->load->view('template/footer');
        } else {
            $this->load->library('password');
            $post = $this->input->post(NULL, TRUE);
            $cleanPost = $this->security->xss_clean($post);
            $hashed = $this->password->create_hash($cleanPost['password']);
            $cleanPost['password'] = $hashed;
            $cleanPost['user_id'] = $user_info->id;
            unset($cleanPost['passconf']);
            if (!$this->user_model->updatePassword($cleanPost)) {
                $this->session->set_flashdata('flash_message', 'There was a problem updating your password');
            } else {
                $this->session->set_flashdata('success_message', 'Your password has been updated. You may now login');
            }
            redirect(site_url() . 'main/login');
        }
    }

    public function base64url_decode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    public function absent_attendance()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $key = $this->input->post('key');

            $result = $this->user_model->getHowManyPeople();
            $data['many_employee'] = $result->many_employee;
            $data['start'] = $result->start_time;
            $data['out'] = $result->out_time;
            $data['key'] = $result->key_insert;
            if (!empty($key)) {
                if ($key == $data['key']) {

                    $Q = $this->security->xss_clean($this->input->post('q', TRUE));
                    $name = $this->security->xss_clean($this->input->post('name', TRUE));
                    $date = $this->security->xss_clean($this->input->post('date', TRUE));
                    $location = $this->security->xss_clean($this->input->post('location', TRUE));

                    //Get time function
                    function gettime($total)
                    {
                        $hours = intval($total / 3600);
                        $seconds_remain = ($total - ($hours * 3600));
                        $minutes = intval($seconds_remain / 60);
                        $seconds = ($seconds_remain - ($minutes * 60));
                        return array($hours, $minutes, $seconds);
                    }

                    //check command
                    if ($Q == "in") {

                        $in_time = $this->security->xss_clean($this->input->post('in_time', TRUE));
                        $change_in_time = strtotime($in_time);

                        //Get late time
                        $get_late_time = gettime($change_in_time - strtotime($data['start']));
                        $late_time = "$get_late_time[0]:$get_late_time[1]:$get_late_time[2]";

                        $alldata = array(
                            'name' => $name,
                            'date' => $date,
                            'in_location' => $location,
                            'in_time' => $in_time,
                            'late_time' => $late_time
                        );

                        $go = $this->user_model->insertAbsent($alldata);
                        if ($go == true) {
                            echo "Success!";
                        } else {
                            echo "Error! Something Went Wrong!";
                        }
                    } else if ($Q == "out") {

                        $out_time = $this->security->xss_clean($this->input->post('out_time', TRUE));
                        $change_out_time = strtotime($out_time);

                        //open in_time from database
                        $datain['in_time'] = $this->user_model->getDataAbsent("name", $name, "date", $date);
                        $get_in_database = strtotime($datain['in_time']);

                        //Get work hour
                        $get_work_hour = gettime($change_out_time - $get_in_database);
                        $work_hour = "$get_work_hour[0]:$get_work_hour[1]:$get_work_hour[2]";

                        //Get over time
                        $get_over_time = gettime($change_out_time - strtotime($data['out']));
                        if ($get_in_database > strtotime($data['out']) || $change_out_time < strtotime($data['out']))
                            $over_time = "00:00:00";
                        else
                            $over_time = "$get_over_time[0]:$get_over_time[1]:$get_over_time[2]";

                        //Early out time
                        $get_early_out_time = gettime(strtotime($data['out']) - $change_out_time);
                        if ($get_in_database > strtotime($data['out']))
                            $early_out_time = "00:00:00";
                        else
                            $early_out_time = "$get_early_out_time[0]:$get_early_out_time[1]:$get_early_out_time[2]";

                        //do SQL
                        $alldata = array(
                            'name' => $name,
                            'date' => $date,
                            'out_location' => $location,
                            'out_time' => $out_time,
                            'work_hour' => $work_hour,
                            'over_time' => $over_time,
                            'early_out_time' => $early_out_time
                        );

                        $go = $this->user_model->updateAbsent($alldata);
                        if ($go == true) {
                            echo "Success!";
                        } else {
                            echo "Error! Something Went Wrong!";
                        }
                    } else {
                        echo 'Error! Wrong Command!';
                    }
                } else {
                    echo "The KEY is Wrong!";
                }
            } else {
                echo "Please Setting KEY First!";
            }
        } else {
            echo "You can't access this page!";
        }
    }

    protected function _islocal()
    {
        return strpos($_SERVER['HTTP_HOST'], 'local');
    }
}
