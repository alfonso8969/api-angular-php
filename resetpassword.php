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
$user_email = $user->user_email;
$user_password = $user->user_password;
$user_password = Utils::crypt($user_password);

$sql = "UPDATE empresas_user SET user_password = ? WHERE user_email = ?";


$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user_password, $user_email);


try {
    $reset_password = $stmt->execute();
    if ($reset_password) {
        echo 1;
    } else {
        echo 0;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

$stmt->close();
$conn->close();