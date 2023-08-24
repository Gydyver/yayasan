<?php

namespace App\Helpers;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SessionCheckingHelper
{
    function checkAuthenticated()
    {
        // dd('masuk checkAuthenticated');
        if (!isset($_SESSION["data"])) {
            abort(redirect('/login'));
        }
        return true;
    }

    function checkSession($auth_group, $auth_id, $user_in_data)
    {
        if ($auth_group != 1) {
            if ($auth_group == 2) {
                //Teacher
                if ($auth_id == $user_in_data) {
                    return true;
                } else {
                    return false;
                }
            } else {
                //Student
                if ($auth_id ==  $user_in_data) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        //Superadmin
        return true;
    }

    function checkSuperadmin($auth_group)
    {
        if ($auth_group == 1) {
            return true;
        } else {
            return false;
        }
    }

    function checkTeacher($auth_group)
    {
        if ($auth_group == 2) {
            return true;
        } else {
            return false;
        }
    }

    function checkStudent($auth_group)
    {
        if ($auth_group == 3) {
            return true;
        } else {
            return false;
        }
    }

    public static function instance()
    {
        return new SessionCheckingHelper();
        session_start();
    }
}
