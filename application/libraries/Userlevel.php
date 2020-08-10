<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserLevel
{
    /**
     * User Level
     *
     * @param $role
     * @return string
     */
    public function checkLevel($role)
    {
        $userLevel = '';
        if (!empty($role)) {
            if ($role == 1) {
                $userLevel = 'is_admin';
            } elseif ($role == 2) {
                $userLevel = 'is_user';
            } elseif ($role == 3) {
                $userLevel = 'is_subscriber';
            }
        } else {
            echo 'Empty Role';
            return false;
        }
        return $userLevel;
    }

}
