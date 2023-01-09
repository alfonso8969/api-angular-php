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

require_once "./classes/class.Database.php";
require_once "./classes/utils.php";

$data = json_decode(file_get_contents("php://input"));
$sql = "";

if (!isset($data)) {
    echo "error";
}

$session = $data->session;
$phpDate = strtotime($session->date);

$id_user = $session->id_user;
$user_email = $session->user_email;
$date = date('Y-m-d H:i:s', $phpDate);
$ip = $session->ip;
$message = $session->message;
$complete = $session->complete;

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare(
    "INSERT INTO empresas_sessions (
        id_user,
        user_email,
        date,
        ip,
        message,
        complete 
    )
    VALUES (?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param(
    "issssi",
    $id_user,
    $user_email,
    $date,
    $ip,
    $message,
    $complete
);

try {
    $session_insert = $stmt->execute();
    if ($session_insert) {
        echo 1;
    } else {
        echo 0;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

$stmt->close();
$conn->close();