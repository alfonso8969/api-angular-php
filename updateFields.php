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

$db = new Database();
$conn = $db->getConnection();

$field = $data->field;
$field_id = "";
$field_name = $field->field_name;
$field_name_update = "";
$sql = "";


if ($field_name == "distrito") { 
    $sql = "UPDATE empresas_distritos SET distrito_name = ? WHERE distrito_id = ?";
    $field_id = $field->distrito_id;
    $field_name_update = $field->distrito_name;
    $sql_compare = "SELECT distrito_name FROM empresas_distritos where distrito_name <> 'sin datos';";
    $fields = Database::get_array($sql_compare);
    foreach ($fields as $field) {   
        $distrito = $field;
        foreach ($distrito as $distrito_name) { 
            if ($distrito_name == $field_name_update) {
                echo $error->error->text;
                return;
            }          
        }
    }
}

if ($field_name == "sector") {
    $field_id = $field->sector_id;
    $field_name_update = $field->empresas_sector_name;
    $sql = "UPDATE empresas_sector SET empresas_sector_name = ? WHERE sector_id = ?";
}

if ($field_name == "poligono") {
    $field_id = $field->poligono_id;
    $field_name_update = $field->empresas_poligono_name;    
    $sql = "UPDATE empresas_poligonos SET empresas_poligono_name = ? WHERE poligono_id = ?";
}

if ($field_name == "tema") {
    $field_id = $field->tema_id;
    $field_name_update = $field->tema_name;
    $field_rol = $field->tema_rol;    
    $sql = "UPDATE empresas_temas SET name = ?, tema_rol = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $field_name_update, $field_rol, $field_id);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $field_name_update, $field_id);
}

try {
    $field_update = $stmt->execute();
    if ($field_update) {
        echo 1;
    } else {
        echo 0;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

$stmt->close();
$conn->close();
