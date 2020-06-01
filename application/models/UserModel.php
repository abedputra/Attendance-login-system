<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model
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
     * Function delete user.
     *
     * @param $id
     * @return bool
     */
    public function deleteUser($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('users');

        return $this->db->affected_rows() == '1';
    }

    /**
     * Function update user.
     *
     * @param $post
     * @return bool
     */
    public function updateUserInfo($post)
    {

        $this->db->where('id', $post['id']);
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

    /**
     * Function ban or unban user.
     *
     * @param $post
     * @return bool
     */
    public function updateUserBan($post)
    {
        $this->db->where('email', $post['email']);
        $this->db->update('users', array('banned_users' => $post['banuser']));
        $this->db->trans_complete();

        $success = $this->db->affected_rows();

        if (!$success) {
            // any trans error?
            return !($this->db->trans_status() === FALSE);
        }
        return true;
    }

    /**
     * Get dataTables.
     *
     * @return array
     */
    public function getDataTables()
    {
        $this->load->library('datatables');
        $this->datatables->select('*, users.id as id');
        $this->datatables->from('users');
        $this->datatables->add_column('change_role', anchor('user/edit/$1', 'Edit', array('class' => 'btn btn-primary btn-sm')), 'id');
        $this->datatables->add_column('ban_user', anchor('user/banUser/$1', 'Ban User', array('class' => 'btn btn-success btn-sm')), 'id');
        $this->datatables->add_column('delete', anchor('user/delete/$1', 'Delete', array('class' => 'btn btn-danger btn-sm')), 'id');

        // Generate the data
        return $this->datatables->generate();

    }
}
