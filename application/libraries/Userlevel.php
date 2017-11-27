<?php
/**
 * User Level
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class UserLevel{

    public function checkLevel($role) {
        if(!empty($role))
        {
            if($role==1)
            {
                $userLevel = 'is_admin';
            }
            elseif($role==2)
            {
                $userLevel = 'is_author';
            }
            elseif($role==3)
            {
                $userLevel = 'is_editor';
            }
            elseif($role==4)
            {
                $userLevel = 'is_subscriber';
            }
        }else{
            echo "Empty Role";
            return false;
        }
        return $userLevel;
    }

}
