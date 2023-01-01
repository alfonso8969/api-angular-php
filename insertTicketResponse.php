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

$id_technical = $ticket->id_technical;
$respuesta = $ticket->respuesta;
$fecha = new DateTime();
$fechaStr = $fecha->format('Y-m-d H:i:s');
$ticket_code = $ticket->ticket_code;
$ticket_refer = $ticket->ticket_refer;
$solucionado = $ticket->solucionado;

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("INSERT INTO empresas_ticket_tratados (
id_technical,
respuesta,
fecha,
ticket_code,
ticket_refer,
solucionado) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param(
    "issssi",
    $id_technical,
    $respuesta,
    $fechaStr,
    $ticket_code,
    $ticket_refer,
    $solucionado
);
$ticket_insert = $stmt->execute();

$sql = "UPDATE empresas_ticket SET respondido = 1 WHERE ticket_code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ticket_code);
$field_update = $stmt->execute();

try {
    if ($ticket_insert && $field_update) {
        echo 1;
    } else {
        echo 0;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}


$stmt->close();
$conn->close();