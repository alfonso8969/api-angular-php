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

$db = Database::getInstance();
$user_email = $user->user_email;
$sql = "SELECT user_password FROM empresas_user where user_email = '%s';";
$sql = sprintf($sql, $user_email); 
$user_passwordDB = $db->get_value_query($sql, 'user_password');


$user_password = $user->user_password;
$user_passwordComp = Utils::uncrypt($user_password, $user_passwordDB);

if ($user_passwordComp) {
    $sql = "SELECT id_user,
    user_img,
    user_name,
    user_lastName,
    user_email,
    user_phone,
    user_other_phone,
    user_rol,
    habilitado,
    fecha_alta,
    fecha_baja
     FROM empresas_user where user_email = '%s' AND user_password = '%s';";
    $sql = sprintf($sql, $user_email, $user_passwordDB); 
    try {
        $newUser = Database::get_json_row($sql);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    
    if ($newUser == null) {
        echo json_encode($user);
    } else {
        echo $newUser;
    }
    
} else {
    echo json_encode($user);
}


