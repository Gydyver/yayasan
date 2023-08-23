<?php

namespace App\Helpers;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SessionCheckingHelper
{
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


    function padMessage($message, $blockSize)
    {
        $messageLength = strlen($message) * 8; // Message length in bits
        $paddingSize = $blockSize - (($messageLength + 8) % $blockSize); // Calculate padding size
        $padding = "\x80"; // Append 1 bit followed by zeros
        $padding .= str_repeat("\x00", $paddingSize); // Pad with zeros
        $padding .= pack('N2', 0);
        // phpinfo();
    }

    public static function instance()
    {
        return new SessionCheckingHelper();
    }
}
