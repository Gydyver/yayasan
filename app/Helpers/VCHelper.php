<?php

namespace App\Helpers;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class VCHelper
{
    function vigenereEncrypt($plaintext, $key)
    {
        $plaintext = strtoupper($plaintext);
        $key = strtoupper($key);
        $ciphertext = '';

        $plaintextLength = strlen($plaintext);
        $keyLength = strlen($key);

        for ($i = 0; $i < $plaintextLength; $i++) {
            $char = $plaintext[$i];
            $keyChar = $key[$i % $keyLength];

            if (ctype_alpha($char)) {
                $charCode = ord($char) - 65;
                $keyCharCode = ord($keyChar) - 65;
                $encryptedCharCode = ($charCode + $keyCharCode) % 26;
                $encryptedChar = chr($encryptedCharCode + 65);
                $ciphertext .= $encryptedChar;
            } else {
                $ciphertext .= $char;
            }
        }

        return $ciphertext;
    }


    function vigenereDecrypt($ciphertext, $key)
    {
        $ciphertext = strtoupper($ciphertext);
        $key = strtoupper($key);
        $plaintext = '';

        $ciphertextLength = strlen($ciphertext);
        $keyLength = strlen($key);

        for ($i = 0; $i < $ciphertextLength; $i++) {
            $char = $ciphertext[$i];
            $keyChar = $key[$i % $keyLength];

            if (ctype_alpha($char)) {
                $charCode = ord($char) - 65;
                $keyCharCode = ord($keyChar) - 65;
                $decryptedCharCode = ($charCode - $keyCharCode + 26) % 26;
                $decryptedChar = chr($decryptedCharCode + 65);
                $plaintext .= $decryptedChar;
            } else {
                $plaintext .= $char;
            }
        }

        return $plaintext;
    }
}
