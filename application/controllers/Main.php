<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

    public $status;
    public $roles;

    function __construct(){
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
	    if(empty($data)){
	        redirect(site_url().'main/login/');
	    }

	    //check user level
	    if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }
	    $dataLevel = $this->userlevel->checkLevel($data['role']);
	    //check user level

	    $data['title'] = "Dashboard Admin";
	    $result = $this->user_model->getAllEmployee();
	    $data['many_employee'] = $result->many_employee;
	    $data['start'] = $result->start_time;
	    $data['out'] = $result->out_time;
	    $data['timezone'] = $result->timezone;

	    $now = new DateTime();
        $now->setTimezone(new DateTimezone($data['timezone'])); //change your city
        $data['nowToday'] =  $now->format('Y-m-d');

	    $data['count_absent_today'] = $this->user_model->getAbsentToday("","","date", $data['nowToday']);
	    $data['count_late_today'] = $this->user_model->getAbsentToday("late_time >", "00:00:00","date", $data['nowToday'] );

        if(empty($this->session->userdata['email'])){
            redirect(site_url().'main/login/');
        }else{
            $this->load->view('header', $data);
            $this->load->view('navbar', $data);
            $this->load->view('container');
            $this->load->view('index', $data);
            $this->load->view('footer');
        }

	}

	public function users()
	{
	    $data = $this->session->userdata;
	    $data['title'] = "User List";
	    $data['groups'] = $this->user_model->getUserData();

	    //check user level
	    if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }
	    $dataLevel = $this->userlevel->checkLevel($data['role']);
	    //check user level

	    //check is admin or not
	    if($dataLevel == "is_admin"){
            $this->load->view('header', $data);
            $this->load->view('navbar', $data);
            $this->load->view('container');
            $this->load->view('user', $data);
            $this->load->view('footer');
	    }else{
	        redirect(site_url().'main/');
	    }
	}

	public function changelevel() //level user
	{
        $data = $this->session->userdata;
        //check user level
	    if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }
	    $dataLevel = $this->userlevel->checkLevel($data['role']);
	    //check user level

	    $data['title'] = "Change Level Admin";
	    $data['groups'] = $this->user_model->getUserData();

	    //check is admin or not
	    if($dataLevel == "is_admin"){

            $this->form_validation->set_rules('email', 'Your Email', 'required');
            $this->form_validation->set_rules('level', 'User Level', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('header', $data);
                $this->load->view('navbar', $data);
                $this->load->view('container');
                $this->load->view('changelevel', $data);
                $this->load->view('footer');
            }else{
                $cleanPost['email'] = $this->input->post('email');
                $cleanPost['level'] = $this->input->post('level');
                if(!$this->user_model->updateUserLevel($cleanPost)){
                    $this->session->set_flashdata('flash_message', 'There was a problem updating the level user');
                }else{
                    $this->session->set_flashdata('success_message', 'The level user has been updated.');
                }
                redirect(site_url().'main/changelevel');
            }
	    }else{
	        redirect(site_url().'main/');
	    }
	}

	public function banuser() //ban or unban user
	{
        $data = $this->session->userdata;
        //check user level
	    if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }
	    $dataLevel = $this->userlevel->checkLevel($data['role']);
	    //check user level

	    $data['title'] = "Ban User";
	    $data['groups'] = $this->user_model->getUserData();

	    //check is admin or not
	    if($dataLevel == "is_admin"){

            $this->form_validation->set_rules('email', 'Your Email', 'required');
            $this->form_validation->set_rules('banuser', 'Ban or Unban', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('header', $data);
                $this->load->view('navbar', $data);
                $this->load->view('container');
                $this->load->view('banuser', $data);
                $this->load->view('footer');
            }else{
                $cleanPost['email'] = $this->input->post('email');
                $cleanPost['banuser'] = $this->input->post('banuser');
                if(!$this->user_model->updateUserban($cleanPost)){
                    $this->session->set_flashdata('flash_message', 'There was a problem updating');
                }else{
                    $this->session->set_flashdata('success_message', 'The status user has been updated.');
                }
                redirect(site_url().'main/banuser');
            }
	    }else{
	        redirect(site_url().'main/');
	    }
	}

	public function changeuser() //edit user
    {
        $data = $this->session->userdata;
        if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }

        $dataInfo = array(
            'firstName'=> $data['first_name'],
            'id'=>$data['id'],
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
            $this->load->view('header', $data);
            $this->load->view('navbar', $data);
            $this->load->view('container');
            $this->load->view('changeuser', $data);
            $this->load->view('footer');
        }else{
            $this->load->library('password');
            $post = $this->input->post(NULL, TRUE);
            $cleanPost = $this->security->xss_clean($post);
            $hashed = $this->password->create_hash($cleanPost['password']);
            $cleanPost['password'] = $hashed;
            $cleanPost['user_id'] = $dataInfo['id'];
            $cleanPost['email'] = $this->input->post('email');
            $cleanPost['firstname'] = $this->input->post('firstname');
            $cleanPost['lastname'] = $this->input->post('lastname');
            if($issetPass){
                unset($cleanPost['passconf']);
                if(!$this->user_model->updateProfile($cleanPost)){
                    $this->session->set_flashdata('flash_message', 'There was a problem updating your profile');
                }else{
                    $this->session->set_flashdata('success_message', 'Your profile has been updated.');
                }
            }else{
                 if(!$this->user_model->updateProfileUser($cleanPost)){
                    $this->session->set_flashdata('flash_message', 'There was a problem updating your profile');
                }else{
                    $this->session->set_flashdata('success_message', 'Your profile has been updated.');
                }
            }
            redirect(site_url().'main/changeuser/');
        }
    }

    public function profile()
    {
        $data = $this->session->userdata;
        if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }

        $data['title'] = "Profile";
        $this->load->view('header', $data);
        $this->load->view('navbar', $data);
        $this->load->view('container');
        $this->load->view('profile', $data);
        $this->load->view('footer');

    }

    public function deleteuser($id)
    {
        $data = $this->session->userdata;
        if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }
	    $dataLevel = $this->userlevel->checkLevel($data['role']);
	    //check user level

	    //check is admin or not
	    if($dataLevel == "is_admin"){
	        $getDelete = $this->user_model->deleteUser($id);

            if($getDelete == false ){
               $this->session->set_flashdata('flash_message', 'Error, cant delete the user!');
            }
            else if($getDelete == true ){
               $this->session->set_flashdata('success_message', 'Delete user was successful.');
            }else{
                $this->session->set_flashdata('flash_message', 'Someting Error!');
            }
            redirect(site_url().'main/users/');
	    }else{
	        redirect(site_url().'main/');
	    }
    }

    public function adduser()
    {
        $data = $this->session->userdata;

      //check user level
	    if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }
	    $dataLevel = $this->userlevel->checkLevel($data['role']);
	    //check user level

	    //check is admin or not
	    if($dataLevel == "is_admin"){

            $this->form_validation->set_rules('firstname', 'First Name', 'required');
            $this->form_validation->set_rules('lastname', 'Last Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('role', 'role', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
            $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

            $data['title'] = "Add User";

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('header', $data);
                $this->load->view('navbar');
                $this->load->view('container');
                $this->load->view('adduser', $data);
                $this->load->view('footer');
            }else{
                if($this->user_model->isDuplicate($this->input->post('email'))){
                    $this->session->set_flashdata('flash_message', 'User email already exists');
                    redirect(site_url().'main/adduser');
                }else{
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
                    if(!$this->user_model->addUser($cleanPost)){
                        $this->session->set_flashdata('flash_message', 'There was a problem updating your profile');
                    }else{
                        $this->session->set_flashdata('success_message', 'Success adding user.');
                    }
                    redirect(site_url().'main/users/');
                };
            }
	    }else{
	        redirect(site_url().'main/');
	    }
    }

    public function register()
    {
        $data['title'] = "Register to Admin";
        $this->load->library('curl');
        $this->load->library('recaptcha');
        $this->form_validation->set_rules('firstname', 'First Name', 'required');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        //recaptcha
        $result = $this->user_model->getAllEmployee();
        $recaptcha = $result->recaptcha;
        $data['recaptcha'] = $result->recaptcha;

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('header', $data);
            $this->load->view('container');
            $this->load->view('register');
            $this->load->view('footer');
        }else{
            if($this->user_model->isDuplicate($this->input->post('email'))){
                $this->session->set_flashdata('flash_message', 'User email already exists');
                redirect(site_url().'main/register');
            }else{
                $clean = $this->security->xss_clean($this->input->post(NULL, TRUE));

                // recaptcha
                // check if recaptcha is on
                if($recaptcha == 1){

                    //recaptcha
                    $recaptchaResponse = $this->input->post('g-recaptcha-response');
                    $userIp = $_SERVER['REMOTE_ADDR'];
                    $key = $this->recaptcha->secret;
                    $url = "https://www.google.com/recaptcha/api/siteverify?secret=".$key."&response=".$recaptchaResponse."&remoteip=".$userIp; //link
                    $response = $this->curl->simple_get($url);
                    $status= json_decode($response, true);

                    //recaptcha check
                    if($status['success']){
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
                        $message .= 'Hello, ' .$this->input->post('lastname') .'<br>';
                        $message .= '<br>';
                        $message .= 'Welcome! you have signed up with our website with the following information:<br>';
                        $message .= '<br>';
                        $message .= '<strong>Username : '. $this->input->post('email') .'</strong><br>';
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

                        $this->email->from($this->config->item('register'), 'Set Password ' . $this->input->post('firstname') .' '. $this->input->post('lastname')); //from sender, title email
                        $this->email->to($to_email);
                        $this->email->subject('Set Password Login');
                        $this->email->message($message);
                        $this->email->set_mailtype("html"); //type is HTML

                        //Sending mail
                        if($this->email->send()){
                            redirect(site_url().'main/successregister/');
                        }else{
                            $this->session->set_flashdata('flash_message', 'There was a problem sending an email.');
                            exit;
                        }
                    }else{
                        //recaptcha failed
                        $this->session->set_flashdata('flash_message', 'Error...! Google Recaptcha UnSuccessful!');
                        redirect(site_url().'main/register/');
                        exit;
                    }
                // check if recaptcha is off
                }else {
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
                    $message .= 'Hello, ' .$this->input->post('lastname') .'<br>';
                    $message .= '<br>';
                    $message .= 'Welcome! you have signed up with our website with the following information:<br>';
                    $message .= '<br>';
                    $message .= '<strong>Username : '. $this->input->post('email') .'</strong><br>';
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

                    $this->email->from($this->config->item('register'), 'Set Password ' . $this->input->post('firstname') .' '. $this->input->post('lastname')); //from sender, title email
                    $this->email->to($to_email);
                    $this->email->subject('Set Password Login');
                    $this->email->message($message);
                    $this->email->set_mailtype("html"); //type is HTML

                    //Sending mail
                    if($this->email->send()){
                        redirect(site_url().'main/successregister/');
                    }else{
                        $this->session->set_flashdata('flash_message', 'There was a problem sending an email.');
                        exit;
                    }
                }
            };
        }
    }

    public function successregister()
    {
        $data['title'] = "Success Register";
        $this->load->view('header', $data);
        $this->load->view('container');
        $this->load->view('register-info');
        $this->load->view('footer');
    }

    public function successresetpassword()
    {
        $data['title'] = "Success Reset Password";
        $this->load->view('header', $data);
        $this->load->view('container');
        $this->load->view('reset-pass-info');
        $this->load->view('footer');
    }

    protected function _islocal()
    {
        return strpos($_SERVER['HTTP_HOST'], 'local');
    }

    public function complete()
    {
        $token = base64_decode($this->uri->segment(4));
        $cleanToken = $this->security->xss_clean($token);

        $user_info = $this->user_model->isTokenValid($cleanToken); //either false or array();

        if(!$user_info){
            $this->session->set_flashdata('flash_message', 'Token is invalid or expired');
            redirect(site_url().'main/login');
        }
        $data = array(
            'firstName'=> $user_info->first_name,
            'email'=>$user_info->email,
            'user_id'=>$user_info->id,
            'token'=>$this->base64url_encode($token)
        );

        $data['title'] = "Set the Password";

        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('header', $data);
            $this->load->view('container');
            $this->load->view('complete', $data);
            $this->load->view('footer');
        }else{

            $this->load->library('password');
            $post = $this->input->post(NULL, TRUE);

            $cleanPost = $this->security->xss_clean($post);

            $hashed = $this->password->create_hash($cleanPost['password']);
            $cleanPost['password'] = $hashed;
            unset($cleanPost['passconf']);
            $userInfo = $this->user_model->updateUserInfo($cleanPost);

            if(!$userInfo){
                $this->session->set_flashdata('flash_message', 'There was a problem updating your record');
                redirect(site_url().'main/login');
            }

            unset($userInfo->password);

            foreach($userInfo as $key=>$val){
                $this->session->set_userdata($key, $val);
            }
            redirect(site_url().'main/');

        }
    }

    public function login()
    {
        $data = $this->session->userdata;
        if(!empty($data['email'])){
	        redirect(site_url().'main/');
	    }else{
	        $this->load->library('curl');
            $this->load->library('recaptcha');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');

            //recaptcha
            $result = $this->user_model->getAllEmployee();
            $recaptcha = $result->recaptcha;
            $data['recaptcha'] = $result->recaptcha;
            $data['title'] = "Welcome Back!";

            if($this->form_validation->run() == FALSE) {
                $this->load->view('header', $data);
                $this->load->view('container');
                $this->load->view('login');
                $this->load->view('footer');
            }else{
                $post = $this->input->post();
                $clean = $this->security->xss_clean($post);
                $userInfo = $this->user_model->checkLogin($clean);

                // recaptcha
                // check if recaptcha is on
                if($recaptcha == 1){
                  $recaptchaResponse = $this->input->post('g-recaptcha-response');
                  $userIp = $_SERVER['REMOTE_ADDR'];
                  $key = $this->recaptcha->secret;
                  $url = "https://www.google.com/recaptcha/api/siteverify?secret=".$key."&response=".$recaptchaResponse."&remoteip=".$userIp; //link
                  $response = $this->curl->simple_get($url);
                  $status= json_decode($response, true);

                  if(!$userInfo){
                      $this->session->set_flashdata('flash_message', 'Wrong password or email.');
                      redirect(site_url().'main/login');
                  }elseif($userInfo->banned_users == "ban"){
                      $this->session->set_flashdata('danger_message', 'You’re temporarily banned from our website!');
                      redirect(site_url().'main/login');
                  }elseif($status['success'] && $userInfo && $userInfo->banned_users == "unban"){ //recaptcha check, success login, ban or unban
                      foreach($userInfo as $key=>$val){
                      $this->session->set_userdata($key, $val);
                      }
                      redirect(site_url().'main/');
                  }else{
                      //recaptcha failed
                      $this->session->set_flashdata('flash_message', 'Error...! Google Recaptcha UnSuccessful!');
                      redirect(site_url().'main/login/');
                      exit;
                  }
                // check if recaptcha is off
                }else{
                  if(!$userInfo){
                      $this->session->set_flashdata('flash_message', 'Wrong password or email.');
                      redirect(site_url().'main/login');
                  }elseif($userInfo->banned_users == "ban"){
                      $this->session->set_flashdata('danger_message', 'You’re temporarily banned from our website!');
                      redirect(site_url().'main/login');
                  }elseif($userInfo && $userInfo->banned_users == "unban"){ //recaptcha check, success login, ban or unban
                      foreach($userInfo as $key=>$val){
                      $this->session->set_userdata($key, $val);
                      }
                      redirect(site_url().'main/');
                  }
                }
            }
	    }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(site_url().'main/login/');
    }

    public function forgot()
    {
        $data['title'] = "Forgot Password";
        $this->load->library('curl');
        $this->load->library('recaptcha');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        //recaptcha
        $result = $this->user_model->getAllEmployee();
        $recaptcha = $result->recaptcha;
        $data['recaptcha'] = $result->recaptcha;

        if($this->form_validation->run() == FALSE) {
            $this->load->view('header', $data);
            $this->load->view('container');
            $this->load->view('forgot');
            $this->load->view('footer');
        }else{
            $email = $this->input->post('email');
            $clean = $this->security->xss_clean($email);
            $userInfo = $this->user_model->getUserInfoByEmail($clean);

            if(!$userInfo){
                $this->session->set_flashdata('flash_message', 'We cant find your email address');
                redirect(site_url().'main/login');
            }

            if($userInfo->status != $this->status[1]){ //if status is not approved
                $this->session->set_flashdata('flash_message', 'Your account is not in approved status');
                redirect(site_url().'main/login');
            }

            // recaptcha
            // check if recaptcha is on
            if($recaptcha == 1){

                //recaptcha
                $recaptchaResponse = $this->input->post('g-recaptcha-response');
                $userIp = $_SERVER['REMOTE_ADDR'];
                $key = $this->recaptcha->secret;
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=".$key."&response=".$recaptchaResponse."&remoteip=".$userIp; //link
                $response = $this->curl->simple_get($url);
                $status= json_decode($response, true);

                //recaptcha check
                if($status['success']){

                    //generate token
                    $token = $this->user_model->insertToken($userInfo->id);
                    $qstring = $this->base64url_encode($token);
                    $url = site_url() . 'main/reset_password/token/' . $qstring;
                    $link = '<a href="' . $url . '">' . $url . '</a>';

                    //send to email
                    //content
                    $message = '';
                    $message .= 'Hello, ' .$this->input->post('lastname') .'<br>';
                    $message .= '<br>';
                    $message .= 'We\'ve generated a new password for you at your<br>';
                    $message .= 'request, you can use this new password with your username:<br>';
                    $message .= '<br>';
                    $message .= '<strong>Username : '. $this->input->post('email') .'</strong><br>';
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

                    $this->email->from($this->config->item('forgot'), 'Reset Password! ' . $this->input->post('firstname') .' '. $this->input->post('lastname')); //from sender, title email
                    $this->email->to($to_email);
                    $this->email->subject('Reset Password');
                    $this->email->message($message);
                    $this->email->set_mailtype("html"); //type is HTML

                    //Sending mail
                    if($this->email->send()){
                        redirect(site_url().'main/successresetpassword/');
                    }else{
                        $this->session->set_flashdata('flash_message', 'There was a problem sending an email.');
                        exit;
                    }
                }else{
                    //recaptcha failed
                    $this->session->set_flashdata('flash_message', 'Error...! Google Recaptcha UnSuccessful!');
                    redirect(site_url().'main/register/');
                    exit;
                }
            }else{
                //generate token
                $token = $this->user_model->insertToken($userInfo->id);
                $qstring = $this->base64url_encode($token);
                $url = site_url() . 'main/reset_password/token/' . $qstring;
                $link = '<a href="' . $url . '">' . $url . '</a>';

                //send to email
                //content
                $message = '';
                $message .= 'Hello, ' .$this->input->post('lastname') .'<br>';
                $message .= '<br>';
                $message .= 'We\'ve generated a new password for you at your<br>';
                $message .= 'request, you can use this new password with your username:<br>';
                $message .= '<br>';
                $message .= '<strong>Username : '. $this->input->post('email') .'</strong><br>';
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

                $this->email->from($this->config->item('forgot'), 'Reset Password! ' . $this->input->post('firstname') .' '. $this->input->post('lastname')); //from sender, title email
                $this->email->to($to_email);
                $this->email->subject('Reset Password');
                $this->email->message($message);
                $this->email->set_mailtype("html"); //type is HTML

                //Sending mail
                if($this->email->send()){
                    redirect(site_url().'main/successresetpassword/');
                }else{
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

        if(!$user_info){
            $this->session->set_flashdata('flash_message', 'Token is invalid or expired');
            redirect(site_url().'main/login');
        }
        $data = array(
            'firstName'=> $user_info->first_name,
            'email'=>$user_info->email,
            //'user_id'=>$user_info->id,
            'token'=>$this->base64url_encode($token)
        );

        $data['title'] = "Reset Password";
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('header', $data);
            $this->load->view('container');
            $this->load->view('reset_password', $data);
            $this->load->view('footer');
        }else{
            $this->load->library('password');
            $post = $this->input->post(NULL, TRUE);
            $cleanPost = $this->security->xss_clean($post);
            $hashed = $this->password->create_hash($cleanPost['password']);
            $cleanPost['password'] = $hashed;
            $cleanPost['user_id'] = $user_info->id;
            unset($cleanPost['passconf']);
            if(!$this->user_model->updatePassword($cleanPost)){
                $this->session->set_flashdata('flash_message', 'There was a problem updating your password');
            }else{
                $this->session->set_flashdata('success_message', 'Your password has been updated. You may now login');
            }
            redirect(site_url().'main/login');
        }
    }

    public function base64url_encode($data)
    {
      return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function base64url_decode($data)
    {
      return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    public function absent_attendance()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $key = $this->input->post('key');

            $result = $this->user_model->getAllEmployee();
    	    $data['many_employee'] = $result->many_employee;
    	    $data['start'] = $result->start_time;
    	    $data['out'] = $result->out_time;
    	    $data['key'] = $result->key_insert;
            if(!empty($key)){
                if($key == $data['key']){

                    $Q = $this->security->xss_clean($this->input->post('q', TRUE));
                    $name = $this->security->xss_clean($this->input->post('name', TRUE));
                    $date = $this->security->xss_clean($this->input->post('date', TRUE));
                    $location = $this->security->xss_clean($this->input->post('location', TRUE));

                    //Get time function
                    function gettime ($total){
                        $hours = intval($total / 3600);
                        $seconds_remain = ($total - ($hours * 3600));
                        $minutes = intval($seconds_remain / 60);
                        $seconds = ($seconds_remain - ($minutes * 60));
                        return array($hours,$minutes,$seconds);
                    }

                    //check command
                    if($Q == "in"){

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
                        if($go == true){
                            echo "Success!";
                        }else{
                            echo "Error! Something Went Wrong!";
                        }
                    }else if($Q == "out"){

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
                        if($change_out_time < strtotime($data['out']))
                            $over_time = "00:00:00";
                        else
                            $over_time = "$get_over_time[0]:$get_over_time[1]:$get_over_time[2]";

                        //Early out time
                        $get_early_out_time = gettime(strtotime($data['out']) - $change_out_time);
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
                        if($go == true){
                            echo "Success!";
                        }else{
                            echo "Error! Something Went Wrong!";
                        }
                    }else{
                        echo 'Error! Wrong Command!';
                    }
                }else{
                    echo "The KEY is Wrong!";
                }
            }else{
                echo "Please Setting KEY First!";
            }

        }else{
            echo "You can't access this page!";
        }
    }

    public function settings() //edit user
    {
        $data = $this->session->userdata;
        if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }
        $this->load->helper('url');
	    $dataLevel = $this->userlevel->checkLevel($data['role']);
	    //check user level

        $data['title'] = "Settings";
        $this->form_validation->set_rules('start_time', 'Start', 'required');
        $this->form_validation->set_rules('out_time', 'Out', 'required');
        $this->form_validation->set_rules('many_employee', 'How many employee', 'required');
        $this->form_validation->set_rules('key', 'KEY', 'required');
        $this->form_validation->set_rules('timezone', 'Timezone', 'required');

        $result = $this->user_model->getAllEmployee();
        $data['id'] = $result->id;
        $data['many_employee'] = $result->many_employee;
        $data['start'] = $result->start_time;
        $data['out'] = $result->out_time;
        $data['recaptcha'] = $result->recaptcha;

	    if (!empty($data['timezone'] = $result->timezone))
	    {
	        $data['timezonevalue'] = $result->timezone;
	        $data['timezone'] = $result->timezone;
	    }
	    else
	    {
          $data['timezonevalue'] = "";
          $data['timezone'] = "Select a time zone";
	    }

	    if (!empty($data['key'] = $result->key_insert))
	    {
	        $data['key'] = $result->key_insert;
	    }

	    if($dataLevel == "is_admin"){
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('header', $data);
                $this->load->view('navbar', $data);
                $this->load->view('container');
                $this->load->view('settings', $data);
                $this->load->view('footer');
            }else{
                $post = $this->input->post(NULL, TRUE);
                $cleanPost = $this->security->xss_clean($post);
                $cleanPost['id'] = $this->input->post('id');
                $cleanPost['start_time'] = $this->input->post('start_time');
                $cleanPost['out_time'] = $this->input->post('out_time');
                $cleanPost['many_employee'] = $this->input->post('many_employee');
                $cleanPost['key'] = $this->input->post('key');
                $cleanPost['timezone'] = $this->input->post('timezone');
                $cleanPost['recaptcha'] = $this->input->post('recaptcha');

                if(!$this->user_model->settings($cleanPost)){
                    $this->session->set_flashdata('flash_message', 'There was a problem updating your data!');
                }else{
                    $this->session->set_flashdata('success_message', 'Your data has been updated.');
                }
                redirect(site_url().'main/settings/');
            }
	    }
    }

    public function employees()
	  {
	    $data = $this->session->userdata;
        $this->load->library("pagination");

	    //check user level
	    if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }

        $dataInfo = array(
            'id'=>$data['id']
        );

	    $dataLevel = $this->userlevel->checkLevel($data['role']);
	    //check user level

	    $data['title'] = "Employees List";
        $this->form_validation->set_rules('name', 'Name');
        $this->form_validation->set_rules('datefrom', 'Date From');
        $this->form_validation->set_rules('dateto', 'Date To');
        $this->form_validation->set_rules('order', 'Order');

	    $result = $this->user_model->getAllEmployee();
	    $data['timezone'] = $result->timezone;

	    $now = new DateTime();
        $now->setTimezone(new DateTimezone($data['timezone'])); //change your city
        $nowToday =  $now->format('Y-m-d');
        $data['date'] = $nowToday;

        $resultGetUser = $this->user_model->getUserInfo($dataInfo['id']);
    	$name = $resultGetUser->first_name . " " . $resultGetUser->last_name;

	    //check is admin or not
	    if($dataLevel == "is_admin"){
	        if (empty($_GET)) {
              $data["links"] = "";
              $data['groups'] = $this->user_model->getEmployees("date", $nowToday);
	            if(!empty($data['groups'])){
                    $this->load->view('header', $data);
                    $this->load->view('navbar', $data);
                    $this->load->view('container');
                    $this->load->view('employees', $data);
                    $this->load->view('footer');
                }else{
                    $data['info'] = "No employee checked in & checked out today.";
                    $this->load->view('header', $data);
                    $this->load->view('navbar', $data);
                    $this->load->view('container');
                    $this->load->view('employees', $data);
                    $this->load->view('footer');
                }
              }else{
                    $post = $this->input->get(NULL, TRUE);
                    $cleanPost = $this->security->xss_clean($post);

                    $cleanPost['datefrom'] = $this->input->get('datefrom');
                    $cleanPost['dateto'] = $this->input->get('dateto');
                    $cleanPost['name'] = $this->input->get('name');
                    $cleanPost['order'] = $this->input->get('order');
                    $cleanPost['download'] = $this->input->get('download');

                    // $data['groups'] = $this->user_model->search($cleanPost['name'], $cleanPost['datefrom'], $cleanPost['dateto'], $cleanPost['order']);

  					$config = array();
  					$config["total_rows"] = "";
  					$config["base_url"] = base_url().'main/employees/';
  					$config['reuse_query_string'] = TRUE;
  					$countAll = $this->user_model->record_count($cleanPost['name'], $cleanPost['datefrom'], $cleanPost['dateto'], $cleanPost['order']);
  					if (is_array($countAll)){
  						$config["total_rows"] = 0;
  					}else{
  						$config["total_rows"] = $this->user_model->record_count($cleanPost['name'], $cleanPost['datefrom'], $cleanPost['dateto'], $cleanPost['order']);
  					}
  					$config["per_page"] = 10;
  					$config["uri_segment"] = 3;
  					$choice = $config["total_rows"] / $config["per_page"];
  					$config["num_links"] = round($choice);
  					/* This Application Must Be Used With BootStrap 3 *  */
  					$config['full_tag_open'] = "<ul class='pagination'>";
  					$config['full_tag_close'] ="</ul>";
  					$config['num_tag_open'] = '<li>';
  					$config['num_tag_close'] = '</li>';
  					$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
  					$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
  					$config['next_tag_open'] = "<li>";
  					$config['next_tagl_close'] = "</li>";
  					$config['prev_tag_open'] = "<li>";
  					$config['prev_tagl_close'] = "</li>";
  					$config['first_tag_open'] = "<li>";
  					$config['first_tagl_close'] = "</li>";
  					$config['last_tag_open'] = "<li>";
  					$config['last_tagl_close'] = "</li>";

  					$this->pagination->initialize($config);

  					$page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;
  					$data["groups"] = $this->user_model->search($config["per_page"], $page, $cleanPost['name'], $cleanPost['datefrom'], $cleanPost['dateto'], $cleanPost['order']);
  					$data["links"] = $this->pagination->create_links();

                    if(!empty($cleanPost['download']) && empty($cleanPost['name']) && empty($cleanPost['datefrom']) && empty($cleanPost['dateto'])){
                         $this->user_model->createcsv("", $nowToday, "", $cleanPost['download']);
                    }else if(!empty($cleanPost['download'])){
                        $this->user_model->createcsv($cleanPost['name'], $cleanPost['datefrom'], $cleanPost['dateto'], $cleanPost['download']);
                    }

                    if(!empty($data['groups'])){
                        $this->load->view('header', $data);
                        $this->load->view('navbar', $data);
                        $this->load->view('container');
                        $this->load->view('employees', $data);
                        $this->load->view('footer');
                    }else{
                        $data['info'] = "Not Found.";
                        $this->load->view('header', $data);
                        $this->load->view('navbar', $data);
                        $this->load->view('container');
                        $this->load->view('employees', $data);
                        $this->load->view('footer');
                    }
                }
            }else if($dataLevel == "is_employee"){
        	        if (empty($_GET)) {
                        $data['groups'] = $this->user_model->getEmployeesPerson("date", $nowToday, $name);
    					          $data["links"] = "";
                        if(!empty($data['groups'])){
                            $this->load->view('header', $data);
                            $this->load->view('navbar', $data);
                            $this->load->view('container');
                            $this->load->view('employees', $data);
                            $this->load->view('footer');
                        }else{
                            $data['info'] = "No employee checked in & checked out today.";
                            $this->load->view('header', $data);
                            $this->load->view('navbar', $data);
                            $this->load->view('container');
                            $this->load->view('employees', $data);
                            $this->load->view('footer');
                        }
                    }else{
    					$post = $this->input->get(NULL, TRUE);
                        $cleanPost['name'] = $name;
                        $cleanPost = $this->security->xss_clean($post);

                        $cleanPost['datefrom'] = $this->input->get('datefrom');
                        $cleanPost['dateto'] = $this->input->get('dateto');
                        $cleanPost['order'] = $this->input->get('order');
                        $cleanPost['download'] = $this->input->get('download');

                        $config = array();

      					$config["base_url"] = base_url().'main/employees/';
      					$config['reuse_query_string'] = TRUE;
                        $countAll = $this->user_model->record_count_searchPerson($name, $cleanPost['datefrom'], $cleanPost['dateto'], $cleanPost['order']);
      					if (is_array($countAll)){
      						$config["total_rows"] = 0;
      					}else{
      						$config["total_rows"] = $this->user_model->record_count_searchPerson($name, $cleanPost['datefrom'], $cleanPost['dateto'], $cleanPost['order']);
      					}
      					$config["per_page"] = 10;
      					$config["uri_segment"] = 3;
      					$choice = $config["total_rows"] / $config["per_page"];
      					$config["num_links"] = round($choice);
      					/* This Application Must Be Used With BootStrap 3 *  */
      					$config['full_tag_open'] = "<ul class='pagination'>";
      					$config['full_tag_close'] ="</ul>";
      					$config['num_tag_open'] = '<li>';
      					$config['num_tag_close'] = '</li>';
      					$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
      					$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
      					$config['next_tag_open'] = "<li>";
      					$config['next_tagl_close'] = "</li>";
      					$config['prev_tag_open'] = "<li>";
      					$config['prev_tagl_close'] = "</li>";
      					$config['first_tag_open'] = "<li>";
      					$config['first_tagl_close'] = "</li>";
      					$config['last_tag_open'] = "<li>";
      					$config['last_tagl_close'] = "</li>";

      					$this->pagination->initialize($config);

      					$page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;
      					$data["groups"] = $this->user_model->searchPerson($config["per_page"], $page, $name, $cleanPost['datefrom'], $cleanPost['dateto'], $cleanPost['order']);
      					$data["links"] = $this->pagination->create_links();

                        if(!empty($cleanPost['download']) && empty($cleanPost['name']) && empty($cleanPost['datefrom']) && empty($cleanPost['dateto'])){
                             $this->user_model->createcsv("", $nowToday, "", $cleanPost['download']);
                        }else if(!empty($cleanPost['download'])){
                            $this->user_model->createcsv($cleanPost['name'], $cleanPost['datefrom'], $cleanPost['dateto'], $cleanPost['download']);
                        }

                        if(!empty($data['groups'])){
                            $this->load->view('header', $data);
                            $this->load->view('navbar', $data);
                            $this->load->view('container');
                            $this->load->view('employees', $data);
                            $this->load->view('footer');
                        }else{
                            $data['info'] = "Not Found.";
                            $this->load->view('header', $data);
                            $this->load->view('navbar', $data);
                            $this->load->view('container');
                            $this->load->view('employees', $data);
                            $this->load->view('footer');
                        }
                    }
              }
	}

	public function generateqr()
	{
	    $data = $this->session->userdata;

	    //check user level
	    if(empty($data['role'])){
	        redirect(site_url().'main/login/');
	    }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        //check is admin or not
        if($dataLevel == "is_admin"){

            $data['title'] = "Generate QR Code";

            $userDetails = $this->input->post('user-details');

            // generate the qr with user details
            if(!empty($userDetails) && $userDetails == 1){

                $this->form_validation->set_rules('firstname', 'First Name', 'required');
                $this->form_validation->set_rules('lastname', 'Last Name', 'required');
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
                $this->form_validation->set_rules('role', 'role', 'required');
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
                $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

                $this->form_validation->set_rules('qr', 'Your Employee Full Name');

                if($this->user_model->isDuplicate($this->input->post('email'))){
                    $this->session->set_flashdata('flash_message', 'User email already exists');
                    redirect(site_url().'main/generateqr');
                }else{
                    $this->load->library('password');
                    $post = $this->input->post(NULL, TRUE);
                    $cleanPost = $this->security->xss_clean($post);
                    $cleanPost['qr'] = $this->input->post('firstname')." ".$this->input->post('lastname');
                    $hashed = $this->password->create_hash($cleanPost['password']);
                    $cleanPost['email'] = $this->input->post('email');
                    $cleanPost['role'] = $this->input->post('role');
                    $cleanPost['firstname'] = $this->input->post('firstname');
                    $cleanPost['lastname'] = $this->input->post('lastname');
                    $cleanPost['password'] = $hashed;
                    unset($cleanPost['passconf']);

                    $cleanPost['qr'] = $this->input->post('firstname').' '.$this->input->post('lastname');
                    //insert to database
                    if(!$this->user_model->addUser($cleanPost)){
                        $this->session->set_flashdata('flash_message', 'There was a problem saving the QR.');
                        redirect(site_url().'main/generateqr');
                    }else{
                        if(!$this->user_model->insertQr($cleanPost)){
                            $this->session->set_flashdata('flash_message', 'There was a problem saving the QR.');
                            redirect(site_url().'main/generateqr');
                        }else{
                            $this->load->view('header', $data);
                            $this->load->view('navbar', $data);
                            $this->load->view('container');
                            $this->load->view('generateqr', $data, $cleanPost);
                            $this->load->view('footer');
                        }
                    }
                }

            }else{
                $this->form_validation->set_rules('qr', 'Your Employee Full Name');
                if (empty($_POST)) {
                        $this->load->view('header', $data);
                        $this->load->view('navbar', $data);
                        $this->load->view('container');
                        $this->load->view('generateqr', $data);
                        $this->load->view('footer');
                }else{
                    $post = $this->input->post(NULL, TRUE);
                    $cleanPost = $this->security->xss_clean($post);
                    $cleanPost['qr'] = $this->input->post('qr');

                    if(!$this->user_model->insertQr($cleanPost)){
                        $this->session->set_flashdata('flash_message', 'There was a problem saving the QR, and generate the QR.');
                        redirect(site_url().'main/generateqr');
                    }else{
                        $this->load->view('header', $data);
                        $this->load->view('navbar', $data);
                        $this->load->view('container');
                        $this->load->view('generateqr', $data, $cleanPost);
                        $this->load->view('footer');
                    }
                }
            }
        } // check user level
	}

    public function historyqr()
    {
        $data = $this->session->userdata;
        $data['title'] = "History QR";
        $data['groups'] = $this->user_model->getHistoryQrData();
        $data['count'] = count($data['groups']);

        //check user level
        if(empty($data['role'])){
            redirect(site_url().'main/login/');
        }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        //check is admin or not
        if($dataLevel == "is_admin"){
            $this->load->view('header', $data);
            $this->load->view('navbar', $data);
            $this->load->view('container');
            $this->load->view('historyqr', $data);
            $this->load->view('footer');
        }else{
            redirect(site_url().'main/');
        }
    }

    public function deletehistoryqr($id)
    {
        $data = $this->session->userdata;
        if(empty($data['role'])){
            redirect(site_url().'main/login/');
        }
        $dataLevel = $this->userlevel->checkLevel($data['role']);
        //check user level

        //check is admin or not
        if($dataLevel == "is_admin"){
            $getDelete = $this->user_model->deleteHistoryQr($id);

            $alldata = $this->user_model->getHistoryQrData();
            $dataCount = count($alldata);
            if($getDelete == false && $dataCount > 0){
               $this->session->set_flashdata('flash_message', 'Error, cant delete the user!');
            }
            else if($getDelete == true && $dataCount > 0){
               $this->session->set_flashdata('success_message', 'Delete user was successful.');
            }else if($dataCount > 0){
                $this->session->set_flashdata('flash_message', 'Someting Error!');
            }
            redirect(site_url().'main/historyqr/');
        }else{
            redirect(site_url().'main/');
        }
    }
}
