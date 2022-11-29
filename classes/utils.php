<?php

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
		$sql = "SELECT (MAX(Empresa_det_id) + 1) As NEXT FROM empresas.empresas_principal";
		$lastIdOfEmpDet =  $db->get_value_query($sql, 'NEXT');
		return "$lastIdOfEmpDet";
	}
}