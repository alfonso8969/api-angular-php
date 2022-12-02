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
$empresa = $data->empresa;

$Web = $empresa->Web;
$Facebook = $empresa->Facebook;
$Twitter= $empresa->Twitter;
$Instagram = $empresa->Instagram;
$Google_plus = $empresa->Google_plus;
$Linkedin = $empresa->Linkedin;
$Telefono = $empresa->Telefono;
$OtherTelefono = $empresa->OtherTelefono;
$Email = $empresa->Email;
$Persona_contacto = $empresa->Persona_contacto;
$Direccion = $empresa->Direccion;
$Localidad = $empresa->Localidad;
$Provincia = $empresa->Provincia;
$Cod_postal = $empresa->Cod_postal;
$Nombre = $empresa->Nombre;
$Sector = $empresa->Sector;
$Distrito = $empresa->Distrito;
$Poligono = $empresa->Poligono;
$Empresa_det_id = $empresa->Empresa_det_id;
$Habilita = $empresa->Habilita;
$fecha_alta = $empresa->fecha_alta;
