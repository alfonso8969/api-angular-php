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

$Nombre = $empresa->Nombre;
$Sector = $empresa->Sector;
$Distrito = $empresa->Distrito;
$Poligono = $empresa->Poligono;
$Empresa_det_id = $empresa->Empresa_det_id;

$Web = $empresa->Web;
$CIF = $empresa->CIF;
$installation_year = $empresa->installation_year;
$workers_number = $empresa->workers_number;
$Telefono = $empresa->Telefono;
$OtherTelefono = $empresa->OtherTelefono;
$Email = $empresa->Email;
$Persona_contacto = $empresa->Persona_contacto;
$Direccion = $empresa->Direccion;
$Localidad = $empresa->Localidad;
$Provincia = $empresa->Provincia;
$Cod_postal = $empresa->Cod_postal;

$Facebook = $empresa->Facebook;
$Twitter= $empresa->Twitter;
$Instagram = $empresa->Instagram;
$Google_plus = $empresa->Google_plus;
$Linkedin = $empresa->Linkedin;

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("UPDATE empresas_principal SET 
                        Nombre = ?,
                        Sector = ?,
                        Distrito = ?,
                        Poligono = ?
                        WHERE Empresa_det_id = ?");
                        
$stmt->bind_param("siiii", $Nombre, $Sector, $Distrito, $Poligono, $Empresa_det_id);
$principal = $stmt->execute();

if ($principal) { 
    $stmt = $conn->prepare("UPDATE empresas_descripcion        
        SET Web = ?,
        CIF = ?,
        installation_year = ?,
        workers_number = ?,
        Telefono = ?,
        otherTelefono = ?,
        Email = ?,
        Direccion = ?,
        Localidad = ?,
        Provincia = ?,
        Cod_postal = ?,
        Persona_contacto = ?
        WHERE emp_det_id = ?");
    $stmt->bind_param("ssiissssssisi", $Web, $CIF, $installation_year, $workers_number, $Telefono, $OtherTelefono, $Email, $Direccion, $Localidad, $Provincia, $Cod_postal, $Persona_contacto, $Empresa_det_id);
    $description = $stmt->execute();
}

if ($principal && $description) {  
    $stmt = $conn->prepare("UPDATE empresas_redes  
    SET Facebook = ?,
    Twitter = ?,
    Instagram = ?,
    Google_plus = ?,
    Linkedin = ? 
    WHERE emp_red_id = ?");
    $stmt->bind_param("sssssi", $Facebook, $Twitter, $Instagram, $Google_plus, $Linkedin, $Empresa_det_id);
    $redes = $stmt->execute();
}

if ($redes && $principal && $description) {
    echo 1;
} else {
    echo 0;
}
