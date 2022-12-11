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

$error = (object) array();
$error->error = (object) array();
$error->error->text = "Duplicate entry";

if (!isset($data)) {
    echo "error";
}

if (!isset($data->field)) {
    echo "error";
}

$field = $data->field;
$field_name = $field->field_name;
$field_name_insert = "";
$sql = "";


if ($field_name == "distrito") { 
    $sql = "INSERT INTO empresas_distritos (distrito_name) VALUES(?)";
    $field_name_insert = $field->distrito_name;
    $sql_compare = "SELECT  REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(distrito_name, ' ', 4), ' ', 2), ')', '')  AS distrito_number FROM empresas_distritos where distrito_name <> 'sin datos';";
    $fields = Database::get_array($sql_compare);
    foreach ($fields as $field) {   
        $distrito = $field;
        foreach ($distrito as $distrito_name) {
            $district_comp = explode("(", $field_name_insert);
            $district_comp = trim($district_comp[0], " ");        
            if ($distrito_name == $district_comp) {
                echo $error->error->text;
                return;
            }          
        }
    }
}

if ($field_name == "sector") { 
    $sql = "INSERT INTO empresas_sector (empresas_sector_name) VALUES(?)";
    $field_name_insert = $field->empresas_sector_name;
}

if ($field_name == "poligono") { 
    $sql = "INSERT INTO empresas_poligonos (empresas_poligono_name) VALUES(?)";
    $field_name_insert = $field->empresas_poligono_name;    
}

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $field_name_insert);

try {
    $field_insert = $stmt->execute();
    if ($field_insert) {
        echo 1;
    } else {
        echo 0;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}


$stmt->close();
$conn->close();