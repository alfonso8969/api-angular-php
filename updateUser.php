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
$user_password = '';
$user = $data->user;
$id = $user->id_user;
$user_name = $user->user_name;
$user_lastName = $user->user_lastName;


if (isset($user->newuser_img)) {
    unlink('./uploads/' . $user->user_img);
    $user_img = $user->newuser_img;
} else {
    $user_img = $user->user_img;
}

$user_phone = $user->user_phone;
$user_email = $user->user_email;

$db = new Database();
$conn = $db->getConnection();



if (isset($user->user_password)) {
    $user_password = Utils::crypt($user->user_password);
    $stmt = $conn->prepare("UPDATE empresas_user 
    SET user_img = ?,
    user_password = ?,
    user_name = ?,
    user_lastName = ?,
    user_email = ?,
    user_phone = ? 
    WHERE id_user = ?");
    $stmt->bind_param(
        "ssssssi",
        $user_img,
        $user_password,
        $user_name,
        $user_lastName,
        $user_email,
        $user_phone,
        $id
    );
} else {
    $stmt = $conn->prepare("UPDATE empresas_user 
    SET user_img = ?,
    user_name = ?,
    user_lastName = ?,
    user_email = ?,
    user_phone = ? 
    WHERE id_user = ?");
    $stmt->bind_param(
        "sssssi",
        $user_img,
        $user_name,
        $user_lastName,
        $user_email,
        $user_phone,
        $id
    );
}

$user_update = $stmt->execute();

if ($user_update) {
    echo 1;
} else {
    echo 0;
}

$stmt->close();
$conn->close();