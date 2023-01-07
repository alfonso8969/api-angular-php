<?php

/**
 * Profile query.
 * php version 8.1.10
 *  
 * @category Config
 * @package  Server
 * @author   Author <alfonsoj.gonzalez@alfonsogonz.es>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @version  GIT: @1.0.0
 * @link     https://github.com/alfonso8969/api-angular-php.git
 */

require_once "class.Database.php";

 /**
  * Constantes encrypt
  */
  define('FIRSTKEY', 'ZORev+6z+qRe0C0DfAO36PPX/OSPOBQbU1HNQG90ABw=');
  define('SECONDKEY', '+FMFPeCKVeYRRNePYbuLec+JtWT48dmcSP09yYz/r7xE75Nq/evGnMBSQOf7ecDNGf56NLA0TuJBSuHf4Lzx6g==');

class Utils
{
    public static function utf8Converter($array)
    {
        array_walk_recursive(
            $array, function (&$item, $key) {
                if (!mb_detect_encoding($item, 'utf-8', true)) {
                    $item = utf8_encode($item);
                }
            }
        );

        return $array;
    }

    public static function getLastIdOfEmpDet() 
    {
        $db = Database::getInstance();
        $sql = "SELECT (MAX(Empresa_det_id) + 1) As NEXT FROM empresas_principal";
        $lastIdOfEmpDet =  $db->get_value_query($sql, 'NEXT');
        return "$lastIdOfEmpDet";
    }

    public static function getLastDistrict() 
    {
        $db = Database::getInstance();
        $sql = "SELECT  MAX(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(distrito_name, ' ', 4), ' ', -1), ')', ''))  AS distrito_number FROM empresas_distritos where distrito_name <> 'sin datos';";
        $lastIdOfDistritos =  $db->get_value_query($sql, 'distrito_number');
        return "$lastIdOfDistritos";
    }

    // ====================================================================
    // Funciones para encryptar y desencryptar data:
    // crypt_blowfish_bydinvaders
    // ====================================================================
    public static function crypt($toEncrypt, $digit = 7)
    {
        $setSalt = './1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $salt = sprintf('$2a$%02d$', $digit);
        for ($i = 0; $i < 22; $i++) {
            $salt .= $setSalt[mt_rand(0, 22)];
        }
        return crypt($toEncrypt, $salt);
    }

    public static function uncrypt($toEval, $against)
    {
        return (crypt($toEval, $against) == $against);
    }

    /**
     * Encrypt data
     *
     * Encrypt the data passed as parameter
     * 
     * @param string $data Data to encrypt
     *
     * @return string  Data encrypted
     */
    public static function encrypt($data)
    {
        $firstkey = base64_decode(FIRSTKEY);
        $secondkey = base64_decode(SECONDKEY);
            
        $method = "AES-256-CBC";
        $ivLength = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($ivLength);
                
        $firstEncrypted = openssl_encrypt($data, $method, $firstkey, OPENSSL_RAW_DATA, $iv);
        $secondEncrypted = hash_hmac('sha3-512', $firstEncrypted, $secondkey, true);
                    
        return base64_encode($iv . $secondEncrypted . $firstEncrypted);    
    }

    /**
     * Decrypt data
     *
     * Decrypt the data passed as parameter
     * 
     * @param string $input Data to decrypt
     *
     * @return string  Data decrypted
     */
    public static function decrypt($input)
    {
        $firstKey = base64_decode(FIRSTKEY);
        $secondKey = base64_decode(SECONDKEY);
        $mix = base64_decode($input);
                
        $method = "AES-256-CBC";
        $ivLength = openssl_cipher_iv_length($method);
                    
        $iv = substr($mix, 0, $ivLength);
        $secondEncrypted = substr($mix, $ivLength, 64);
        $firstEncrypted = substr($mix, $ivLength + 64);
                    
        $data = openssl_decrypt($firstEncrypted, $method, $firstKey, OPENSSL_RAW_DATA, $iv);
        $secondEncryptedNew = hash_hmac('sha3-512', $firstEncrypted, $secondKey, true);

        if (hash_equals($secondEncrypted, $secondEncryptedNew)) {
            return $data;
        }
        return false;
    }


}