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

include_once "class.Database.php";
class Utils 
{
	public static function utf8Converter($array)
	{
		array_walk_recursive($array, function (&$item, $key)
        {
			if (!mb_detect_encoding($item, 'utf-8', true)) {
				$item = utf8_encode($item);
			}
		});

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
        $set_salt = './1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $salt = sprintf('$2a$%02d$', $digit);
        for ($i = 0; $i < 22; $i++) {
            $salt .= $set_salt[mt_rand(0, 22)];
        }
        return crypt($toEncrypt, $salt);
    }

    public static function uncrypt($toEval, $against)
    {
        return (crypt($toEval, $against) == $against);        
    }


}
