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
 * @link     https://bitbucket/private/repository
 */

require "./classes/class.Database.php";
require "./classes/utils.php";

$data = json_decode(file_get_contents("php://input"));
$sql = "";

if (!isset($data)) {
    echo "error";
}

if ($data->field == "distrito") { 
    $sql = "SELECT * FROM empresas_distritos;";
}

if ($data->field == "sector") { 
    $sql = "SELECT * FROM empresas_sector;";
}

if ($data->field == "poligono") { 
    $sql = "SELECT * FROM empresas_poligonos;";
}


$fields = Database::get_array($sql);

$fields = Utils::utf8Converter($fields);

$response = json_encode(array('data' => $fields));

echo "$response";
