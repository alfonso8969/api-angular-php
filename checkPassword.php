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
$user_password = Utils::encrypt($user->user_password);


$db = Database::getInstance();
$user_email = $user->user_email;
$sql = "SELECT user_password FROM empresas_user where user_email = '%s';";
$sql = sprintf($sql, $user_email);
$user_passwordDB = $db->get_value_query($sql, 'user_password');

$user_passwordDecrypt = Utils::decrypt($user_password);
$user_passwordDBDecrypt = Utils::decrypt($user_passwordDB);
$user_passwordComp = $user_passwordDecrypt == $user_passwordDBDecrypt;

echo $user_passwordComp;