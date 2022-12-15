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

if (!isset($data->user)) {
    echo "error";
}

$empresa = $data->empresa;

$field_id = $empresa->Empresa_det_id;
$field_user_id = '';
$field_hab_update = $empresa->Habilitada;

$fecha = new DateTime();
$fechastr = $fecha->format('Y-m-d H:i:s');

if ($field_hab_update == 1) {
    $field_user_id = $empresa->user_id_alta;
    $sql = "UPDATE empresas_user SET Habilitada = ?, fecha_alta = '%s', user_id_alta = %d, user_id_baja = NULL, fecha_baja = NULL WHERE Empresa_det_id = ?";
} else {
    $field_user_id = $empresa->user_id_baja;
    $sql = "UPDATE empresas_user SET Habilitada = ?, fecha_baja = '%s', user_id_baja = %d, user_id_alta = NULL, fecha_alta = NULL WHERE Empresa_det_id = ?";
}

$sql = sprintf($sql, $fechastr, $field_user_id);  

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $field_hab_update, $field_id);

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
