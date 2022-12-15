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

include_once "./classes/class.Database.php";
include_once "./classes/utils.php";

$data = json_decode(file_get_contents("php://input"));
$sql = "";

if (!isset($data)) {
    echo "error";
}

if ($data->field == "distrito") { 
    $sql = "CALL empresas_por_distrito";
}

if ($data->field == "sector") { 
    $sql = "CALL empresas_por_sector";
}

if ($data->field == "poligono") { 
    $sql = "CALL empresas_por_poligono";
}


$fields_count = Database::get_array($sql);

$fields_count = Utils::utf8Converter($fields_count);

$response = json_encode($fields_count);

echo "$response";
