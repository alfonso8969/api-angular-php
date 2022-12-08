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
$phpdate = strtotime($empresa->fecha_alta);
$mysqldate = date('Y-m-d H:i:s', $phpdate);
$user_id_alta = $empresa->user_id_alta;


$Web = $empresa->Web;
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
                    $Habilitada, $mysqldate, $user_id_alta
                );
$princ = $stmt->execute();

if ($princ) { 
    $stmt = $conn->prepare("INSERT INTO empresas_descripcion (
        emp_det_id,
        Web,
        Telefono,
        otherTelefono,
        Email,
        Direccion,
        Localidad,
        Provincia,
        Cod_postal,
        Persona_contacto)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssss", 
                        $Empresa_det_id, $Web, $Telefono, $OtherTelefono,
                        $Email, $Direccion, $Localidad, $Provincia,
                        $Cod_postal, $Persona_contacto
                        );
    $descrip = $stmt->execute();
}

if ($princ && $descrip) {  
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
                        $Instagram, $Google_plus, $Linkedin
                    );
    $redes = $stmt->execute();
}


if ($redes && $princ && $descrip) {
    echo 1;
} else {
    echo 0;
}


$stmt->close();
$conn->close();
