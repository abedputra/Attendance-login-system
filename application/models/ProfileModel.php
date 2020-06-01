<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProfileModel extends CI_Model
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
     * Update function profile.
     *
     * @param $post
     * @return bool
     */
    public function updateProfile($post)
    {
        $this->db->where('id', $post['user_id']);
        if($post['withPassword'] === 'yes'){
            $this->db->update('users', array('password' => $post['password'], 'email' => $post['email'], 'first_name' => $post['firstname'], 'last_name' => $post['lastname']));
        }else{
            $this->db->update('users', array('email' => $post['email'], 'first_name' => $post['firstname'], 'last_name' => $post['lastname']));
        }
        $this->db->trans_complete();

        $success = $this->db->affected_rows();

        if (!$success) {
            // any trans error?
            return !($this->db->trans_status() === FALSE);
        }
        return true;
    }
}
