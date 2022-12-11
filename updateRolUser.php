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

$user = $data->user;

$field_id = $user->id_user;
$field_hab_update = $user->user_rol;

$sql = "UPDATE empresas_user SET user_rol = ? WHERE id_user = ?";

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