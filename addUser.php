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

$user = $data->user;

$user_name = $user->user_name;
$user_lastName = $user->user_lastName;

if (!isset($user->user_img)) {
    $user_img = "";
} else {
    $user_img = $user->user_img;
}
$user_password = $user->user_password;
$user_phone = $user->user_phone;
$user_email = $user->user_email;
$user_rol = $user->user_rol;
$habilitado = $user->habilitado;
$phpdate = strtotime($user->fecha_alta);
$mysqldate = date('Y-m-d H:i:s', $phpdate);
$fecha_alta = $mysqldate;

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("INSERT INTO empresas_user (
user_img,
user_password,
user_name,
user_lastName,
user_email,
user_phone,
user_rol,
habilitado,
fecha_alta) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param(
    "sssssssis",
    $user_img,
    $user_password,
    $user_name,
    $user_lastName,
    $user_email,
    $user_phone,
    $user_rol,
    $habilitado,
    $fecha_alta
);
$user_insert = $stmt->execute();

if ($user_insert) {
    echo 1;
} else {
    echo 0;
}

$stmt->close();
$conn->close();
