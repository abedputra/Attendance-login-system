<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SettingsModel extends CI_Model
{
    /**
     * Function save settings.
     *
     * @param $post
     * @return bool
     */
    public function settings($post)
    {
        $this->db->where('id', $post['id']);
        $this->db->update('settings',
            array(
                'start_time' => $post['start_time'],
                'out_time' => $post['out_time'],
                'many_employee' => $post['many_employee'],
                'key_insert' => $post['key'],
                'timezone' => $post['timezone'],
                'recaptcha' => $post['recaptcha']
            )
        );
        $this->db->trans_complete();

        $success = $this->db->affected_rows();

        if (!$success) {
            // any trans error?
            return !($this->db->trans_status() === FALSE);
        }

        return true;

    }
}
