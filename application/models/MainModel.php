<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MainModel extends CI_Model
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
        // Call the Model constructor
        parent::__construct();
        $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
        $this->banned_users = $this->config->item('banned_users');
    }

    /**
     * Function insert user.
     *
     * @return int
     */
    public function insertUser($d)
    {
        $string = array(
            'first_name' => $d['firstname'],
            'last_name' => $d['lastname'],
            'email' => $d['email'],
            'role' => $this->roles[0],
            'status' => $this->status[0],
            'banned_users' => $this->banned_users[0],
        );
        $q = $this->db->insert_string('users', $string);
        $this->db->query($q);
        return $this->db->insert_id();
    }

    /**
     * Function check duplicate email user.
     *
     * @param $email
     * @return boolean
     */
    public function isDuplicate($email)
    {
        $this->db->get_where('users', array('email' => $email), 1);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Function insert token when register.
     *
     * @param $user_id
     * @return string
     */
    public function insertToken($user_id)
    {
        $token = substr(sha1(rand()), 0, 30);
        $date = date('Y-m-d');

        $string = array(
            'token' => $token,
            'user_id' => $user_id,
            'created' => $date
        );
        $query = $this->db->insert_string('tokens', $string);
        $this->db->query($query);
        return $token . $user_id;
    }

    /**
     * Function check token register.
     *
     * @param $token
     * @return boolean
     */
    public function isTokenValid($token)
    {
        $tkn = substr($token, 0, 30);
        $uid = substr($token, 30);

        $q = $this->db->get_where('tokens', array(
            'tokens.token' => $tkn,
            'tokens.user_id' => $uid), 1);

        if ($this->db->affected_rows() > 0) {
            $row = $q->row();

            $created = $row->created;
            $createdTS = strtotime($created);
            $today = date('Y-m-d');
            $todayTS = strtotime($today);

            if ($createdTS != $todayTS) {
                return false;
            }
            return $this->getUserInfo($row->user_id);
        }

        return false;
    }

    /**
     * Function get user info.
     *
     * @param $id
     * @return array | boolean
     */
    public function getUserInfo($id)
    {
        $q = $this->db->get_where('users', array('id' => $id), 1);
        if ($this->db->affected_rows() > 0) {
            $row = $q->row();
            return $row;
        }

        error_log('no user found getUserInfo(' . $id . ')');
        return false;
    }

    /**
     * Function get user info.
     *
     * @param $email
     * @return array | boolean
     */
    public function getUserAllData($email)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $email);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row;
        } else {
            error_log('no user found getUserAllData(' . $email . ')');
            return false;
        }
    }

    /**
     * Function update user info.
     *
     * @param $post
     * @return array | boolean
     */
    public function updateUserInfoComplete($post)
    {
        $data = array(
            'password' => $post['password'],
            'last_login' => date('Y-m-d h:i:s A'),
            'status' => $this->status[1]
        );
        $this->db->where('id', $post['user_id']);
        $this->db->update('users', $data);
        $this->db->trans_complete();

        $success = $this->db->affected_rows();

        if (!$success) {
            // any trans error?
            if ($this->db->trans_status() === FALSE) {
                return false;
            }
            $user_info = $this->getUserInfo($post['user_id']);
            return $user_info;
        } else {
            $user_info = $this->getUserInfo($post['user_id']);
            return $user_info;
        }
    }

    /**
     * Function check login.
     *
     * @param $post
     * @return array | boolean
     */
    public function checkLogin($post)
    {
        $this->load->library('password');
        $this->db->select('*');
        $this->db->where('email', $post['email']);
        $query = $this->db->get('users');
        $userInfo = $query->row();
        $count = $query->num_rows();

        if ($count == 1) {
            if (!$this->password->validate_password($post['password'], $userInfo->password)) {
                error_log('Unsuccessful login attempt(' . $post['email'] . ')');
                return false;
            }
            $this->updateLoginTime($userInfo->id);
        } else {
            error_log('Unsuccessful login attempt(' . $post['email'] . ')');
            return false;
        }

        unset($userInfo->password);
        return $userInfo;
    }

    /**
     * Function update login time.
     *
     * @param $id
     * @return void
     */
    public function updateLoginTime($id)
    {
        $this->db->where('id', $id);
        $this->db->update('users', array('last_login' => date('Y-m-d h:i:s A')));
    }

    /**
     * Function get user data by email.
     *
     * @param $email
     * @return array | boolean
     */
    public function getUserInfoByEmail($email)
    {
        $q = $this->db->get_where('users', array('email' => $email), 1);
        if ($this->db->affected_rows() > 0) {
            $row = $q->row();
            return $row;
        } else {
            error_log('no user found getUserInfo(' . $email . ')');
            return false;
        }
    }

    /**
     * Function update password user when reset password.
     *
     * @param $post
     * @return array | boolean
     */
    public function updatePassword($post)
    {
        $this->db->where('id', $post['user_id']);
        $this->db->update('users', array('password' => $post['password']));
        $this->db->trans_complete();

        $success = $this->db->affected_rows();

        if (!$success) {
            // any trans error?
            if ($this->db->trans_status() === FALSE) {
                return false;
            }
            return true;
        }
        return true;
    }

    /**
     * Function all data user.
     *
     * @return array | boolean
     */
    public function getUserData()
    {
        $query = $this->db->get('users');
        return $query->result();
    }

    /**
     * Function all data user by id.
     *
     * @param $id
     * @return array | boolean
     */
    public function getUserDataById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->result();
    }

    /**
     * Function add new user.
     *
     * @param $post
     * @return bool
     */
    public function addUser($post)
    {
        $string = array(
            'first_name' => $post['firstname'],
            'last_name' => $post['lastname'],
            'email' => $post['email'],
            'password' => $post['password'],
            'role' => $post['role'],
            'status' => $this->status[1],
            'banned_users' => $this->banned_users[0]
        );
        $q = $this->db->insert_string('users', $string);
        $this->db->query($q);
        return $this->db->insert_id();
    }

    /**
     * Function get all data from settings.
     *
     * @return array
     */
    public function getSettings()
    {
        $this->db->select('*');
        $this->db->from('settings');
        return $this->db->get()->row();

    }

    /**
     * Function attendance today.
     *
     * @param $collA = first column
     * @param $whereA = first where
     * @param $collB = second column
     * @param $whereB = second where
     * @return array
     */
    public function getAbsentToday($collA, $whereA, $collB, $whereB)
    {
        $where = array();

        if ($whereA != '') $where[$collA] = $whereA;
        if ($whereB != '') $where[$collB] = $whereB;


        if (empty($where)) {
            return array(); // ... or NULL
        }

        $query = $this->db->get_where('absent', $where);
        return $query->result();
    }

    /**
     * Function get in_time, when user check-out.
     *
     * @param $collA = first column
     * @param $whereA = first where
     * @param $collB = second column
     * @param $whereB = second where
     * @return array | boolean
     */
    public function getDataAbsent($collA, $whereA, $collB, $whereB)
    {
        $this->db->select('*');
        $this->db->from('absent');
        $this->db->where($collA, $whereA);
        $this->db->where($collB, $whereB);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['in_time'];
        }

        return false;

    }

    /**
     * Function insert attendance when check-in.
     *
     * @param $post
     * @return boolean
     */
    public function insertAbsent($post)
    {
        $string = array(
            'name' => $post['name'],
            'date' => $post['date'],
            'late_time' => $post['late_time'],
            'in_time' => $post['in_time'],
            'in_location' => $post['in_location']
        );
        $q = $this->db->insert_string('absent', $string);
        $this->db->query($q);
        $check = $this->db->insert_id();

        if ($check) {
            return true;
        }

        return false;

    }

    /**
     * Function update attendance when check-out.
     *
     * @param $post
     * @return boolean
     */
    public function updateAbsent($post)
    {
        $name = $post['name'];
        $date = $post['date'];

        $string = array(
            'out_location' => $post['out_location'],
            'out_time' => $post['out_time'],
            'work_hour' => $post['work_hour'],
            'over_time' => $post['over_time'],
            'early_out_time' => $post['early_out_time']
        );
        $this->db->where('name', $name);
        $this->db->where('date', $date);
        $this->db->limit(1);
        $this->db->order_by('id', 'desc');
        $this->db->update('absent', $string);
        $this->db->trans_complete();

        $success = $this->db->affected_rows();

        if ($success) {
            return true;
        } else {
            // any trans error?
            if ($this->db->trans_status() === FALSE) {
                return false;
            }
            return true;
        }
    }
}
