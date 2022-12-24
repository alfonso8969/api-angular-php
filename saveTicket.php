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
$ticket = $data->ticket;

$id_ticket = $ticket->id_ticket;
$user_id = $ticket->user_id;
$campo = $ticket->campo;
$message = $ticket->message;
$phpdate = strtotime($ticket->fecha);
$mysqldate = date('Y-m-d H:i:s', $phpdate);
$code = $ticket->code;
$respondido = $ticket->respondido;

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("INSERT INTO empresas_ticket (
                        user_id,
                        campo,
                        message,
                        fecha,
                        ticket_code,
                        respondido) 
                        VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssi", $user_id, $campo, $message, $mysqldate, $code, $respondido);
                    
$ticket = $stmt->execute();
if ($ticket) {
    echo 1;
} else {
    echo 0;
}


$stmt->close();
$conn->close();