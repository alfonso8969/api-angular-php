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

$log = $data->log;
$phpDate = strtotime($log->date);

$id_user = $log->id_user;
$action = $log->action;
$user_email = $log->user_email;
$date = date('Y-m-d H:i:s', $phpDate);
$ip = $log->ip;
$message = $log->message;
$status = $log->status;

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare(
    "INSERT INTO empresas_logs (
        id_user,
        user_email,
        action,
        message,
        ip,
        date,
        status
    )
    VALUES (?, ?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param(
    "isssssi",
    $id_user,
    $user_email,
    $action,
    $message,
    $ip,
    $date,
    $status
);

try {
    $log_insert = $stmt->execute();
    if ($log_insert) {
        echo 1;
    } else {
        echo 0;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

$stmt->close();
$conn->close();