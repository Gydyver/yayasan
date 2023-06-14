<?php
namespace App\Helpers;

class EncryptionHelper
{

      public function getRandomString($length = 16) {
            //IV generator
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $string = '';
            
            for ($i = 0; $i < $length; $i++) {
                  $string .= $characters[mt_rand(0, strlen($characters) - 1)];
            }

            return $string;
      }

      public function encrypt($data)
      {

            //Define cipher 
            // $cipher = "aes-256-cbc"; 
            $cipher = env('ENCRYPT_CIPHER');
            // dd($cipher);

            //Generate a 256-bit encryption key 
            $encryption_key = env('ENCRYPT_KEY');

            $iv_size = openssl_cipher_iv_length($cipher); 

            $iv = EncryptionHelper::getRandomString($iv_size);

            $value = openssl_encrypt($data, $cipher, $encryption_key, 0, $iv); 

            $object = array("iv" => $iv, "value"=> $value);
            $json =json_encode($object,JSON_UNESCAPED_SLASHES);
            return  base64_encode($json);
      }
  
       
      public function decrypt($encrypted_data)
      {
              //Define cipher 
            //   $cipher = "aes-256-cbc"; 
              $cipher = env('ENCRYPT_CIPHER');
              
              $encryption_key = env('ENCRYPT_KEY');
  
              $json = base64_decode($encrypted_data);
              $data = json_decode($json);
  
              //Decrypt data 
              $decrypted_data = openssl_decrypt($data->value, $cipher, $encryption_key, 0, $data->iv); 
  
              return $decrypted_data; 
      }

     public static function instance()
     {
         return new EncryptionHelper();
     }
}