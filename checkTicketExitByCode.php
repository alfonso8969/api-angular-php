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

$ticket["campo"] = "";
$ticket["ticket_code"] = "";

$code = $data->code;

$sql = "SELECT * FROM empresas_ticket WHERE ticket_code = '$code'";

try {
    $newTicket = Database::get_json_row($sql);

    if ($newTicket != null) {
        echo $newTicket;        
    } else {
        echo json_encode($ticket);
    }
} catch (Exception $e) {
    echo $e->getMessage();
}