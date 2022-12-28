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
$Link = 'sin datos';
$Empresa_det_id = $empresa->Empresa_det_id;
$Habilitada = 1;
$phpDate = strtotime($empresa->fecha_alta);
$mysqlDate = date('Y-m-d H:i:s', $phpDate);
$user_id_alta = $empresa->user_id_alta;


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
$LinkedIn = $empresa->Linkedin;

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("INSERT INTO empresas_principal (
                        Nombre,
                        Sector,
                        Distrito,
                        Poligono,
                        Link,
                        Empresa_det_id,
                        Habilitada,
                        fecha_alta,
                        user_id_alta) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("siiisiisi", 
                    $Nombre, $Sector, $Distrito, 
                    $Poligono, $Link, $Empresa_det_id,
                    $Habilitada, $mysqlDate, $user_id_alta
                );
$principal = $stmt->execute();

if ($principal) { 
    $stmt = $conn->prepare("INSERT INTO empresas_descripcion (
        emp_det_id,
        CIF,
        Web,
        Telefono,
        otherTelefono,
        Email,
        Direccion,
        Localidad,
        Provincia,
        Cod_postal,
        Persona_contacto,
        installation_year,
        workers_number)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssii",
                        $Empresa_det_id, $CIF, $Web,
                        $Telefono, $OtherTelefono, $Email,
                        $Direccion, $Localidad, $Provincia,
                        $Cod_postal, $Persona_contacto,
                        $installation_year, $workers_number);
    $description = $stmt->execute();
}

if ($principal && $description) {  
    $stmt = $conn->prepare("INSERT INTO empresas_redes
    (emp_red_id,
    Facebook,
    Twitter,
    Instagram,
    Google_plus,
    Linkedin)
    VALUES
    (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", 
                        $Empresa_det_id, $Facebook, $Twitter, 
                        $Instagram, $Google_plus, $LinkedIn
                    );
    $redes = $stmt->execute();
}


if ($redes && $principal && $description) {
    echo 1;
} else {
    echo 0;
}


$stmt->close();
$conn->close();
