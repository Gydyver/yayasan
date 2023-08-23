<?php

namespace App\Helpers;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ShaHelper
{
    function sha256WithSalt($string_ori, $salt_ori)
    {
        $blockSize = 64; // Block size in bytes
        $hashSize = 32; // Hash size in bytes
        // dd($string);
        // Convert salt and string to binary
        $string = hex2bin(strval($string_ori));
        // dd($string);
        //masih masalah salt belum hexadecimal
        $salt = hex2bin(strval($salt_ori));
        dd($salt);
        // dd($string);

        // Combine salt and string
        $saltedString = $salt . $string;

        // Padding the salted string
        $saltedString = padMessage($saltedString, $blockSize);

        // Initialize hash values (first 32 bits of the fractional parts of the square roots of the first 8 primes 2..19)
        $hash = [
            0x6a09e667, 0xbb67ae85, 0x3c6ef372, 0xa54ff53a,
            0x510e527f, 0x9b05688c, 0x1f83d9ab, 0x5be0cd19
        ];

        // Constants (first 32 bits of the fractional parts of the cube roots of the first 64 primes 2..311)
        $constants = [
            0x428a2f98, 0x71374491, 0xb5c0fbcf, 0xe9b5dba5,
            0x3956c25b, 0x59f111f1, 0x923f82a4, 0xab1c5ed5,
            // ...
        ];

        // Process the message in 512-bit chunks
        $chunks = str_split($saltedString, $blockSize);
        foreach ($chunks as $chunk) {
            $schedule = [];
            for ($i = 0; $i < 16; $i++) {
                $schedule[] = substr($chunk, $i * 4, 4);
            }

            for ($i = 16; $i < 64; $i++) {
                $s0 = rightRotate(hexdec($schedule[$i - 15]), 7) ^ rightRotate(hexdec($schedule[$i - 15]), 18) ^ (hexdec($schedule[$i - 15]) >> 3);
                $s1 = rightRotate(hexdec($schedule[$i - 2]), 17) ^ rightRotate(hexdec($schedule[$i - 2]), 19) ^ (hexdec($schedule[$i - 2]) >> 10);
                $schedule[] = sprintf('%08x', (hexdec($schedule[$i - 16]) + $s0 + hexdec($schedule[$i - 7]) + $s1) & 0xffffffff);
            }

            $workingVars = $hash;
            for ($i = 0; $i < 64; $i++) {
                $s1 = rightRotate($workingVars[4], 6) ^ rightRotate($workingVars[4], 11) ^ rightRotate($workingVars[4], 25);
                $ch = ($workingVars[4] & $workingVars[5]) ^ (~$workingVars[4] & $workingVars[6]);
                $temp1 = ($workingVars[7] + $s1 + $ch + $constants[$i] + hexdec($schedule[$i])) & 0xffffffff;

                $s0 = rightRotate($workingVars[0], 2) ^ rightRotate($workingVars[0], 13) ^ rightRotate($workingVars[0], 22);
                $maj = ($workingVars[0] & $workingVars[1]) ^ ($workingVars[0] & $workingVars[2]) ^ ($workingVars[1] & $workingVars[2]);
                $temp2 = ($s0 + $maj) & 0xffffffff;

                $workingVars[7] = $workingVars[6];
                $workingVars[6] = $workingVars[5];
                $workingVars[5] = $workingVars[4];
                $workingVars[4] = ($workingVars[3] + $temp1) & 0xffffffff;
                $workingVars[3] = $workingVars[2];
                $workingVars[2] = $workingVars[1];
                $workingVars[1] = $workingVars[0];
                $workingVars[0] = ($temp1 + $temp2) & 0xffffffff;
            }

            // Update hash values
            for ($i = 0; $i < 8; $i++) {
                $hash[$i] = ($hash[$i] + $workingVars[$i]) & 0xffffffff;
            }
        }

        // Convert the hash to a hex string
        $result = '';
        foreach ($hash as $h) {
            $result .= sprintf('%08x', $h);
        }

        return $result;
    }

    function rightRotate($number, $bits)
    {
        return ($number >> $bits) | (($number << (32 - $bits)) & 0xffffffff);
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
        return new ShaHelper();
    }
}
