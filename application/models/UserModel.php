<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

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
        if ($post['withPassword'] === 'yes') {
            $this->db->update('users', array('password' => $post['password'], 'email' => $post['email'], 'first_name' => $post['firstname'], 'last_name' => $post['lastname'], 'role' => $post['role']));
        } else {
            $this->db->update('users', array('email' => $post['email'], 'first_name' => $post['firstname'], 'last_name' => $post['lastname'], 'role' => $post['role']));
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
     * @return Datatables
     */
    public function getDataTables()
    {
        // Get data from the database
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query('SELECT `id`, `first_name`, `last_name`, `email`, `last_login`, `role`, `status`, `banned_users` FROM `users`');

        // Edit the column
        $datatables->edit('role', function ($data) {
            if ($data['role'] == 1) {
                $dataRole = "Admin";
            }else if ($data['role'] == 2) {
                $dataRole = "User";
            }else {
                $dataRole = "Subscribe";
            }
            return $dataRole;
        });

        // Add new column
        $datatables->add('action', static function ($data) {
            return '<a href="' . site_url() . 'user/edit/' . $data['id'] . '"><button class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> </button></a> <a href="' . site_url() . 'user/banUser/' . $data['id'] . '"><button class="btn btn-warning btn-sm"><i class="fa fa-ban" aria-hidden="true"></i> </button> </a> <a href="' . site_url() . 'user/delete/' . $data['id'] . '"><button class="btn btn-danger btn-sm delete-button"><i class="fa fa-trash" aria-hidden="true"></i> </button> </a>';
        });

        // Generate the datatables
        return $datatables->generate();
    }
}
