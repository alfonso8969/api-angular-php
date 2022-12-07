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

$id = $data->id;

$sql = "SELECT Empresa_det_id, Nombre, Sector, Telefono, otherTelefono , Email, Persona_contacto, Direccion, 
Distrito, Poligono, Localidad, Provincia, Cod_postal, Web, 
Facebook, Twitter, Instagram, Linkedin, Google_plus, Habilitada FROM empresas_principal ep 
INNER JOIN empresas_descripcion ed ON ep.Empresa_det_id = ed.emp_det_id
INNER JOIN empresas_redes ers ON ep.Empresa_det_id = ers.emp_red_id
WHERE Empresa_det_id = " . $id;

$empresa = Database::get_Row($sql);
$fields = Utils::utf8Converter($empresa);
$response = json_encode($fields);

echo "$response";
