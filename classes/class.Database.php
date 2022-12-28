<?php

// Clase: class.Database.php
// Funcion: Se encarga del manejo con la base de datos
// Descripcion: Tiene varias funciones muy útiles para
// 				el manejo de registros.
//
// Ultima Modificación: 28 de noviembre de 2022
// ======================================================

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];

define("ERRORDATABASE", "Database connection failure");
define("ERRORDATABASEREQUEST", "class.Database.class: error ");

if ($method == "OPTIONS") {
    die();
}

/**
 *
 *
 */
class Database
{
    private $_connection;
    // private $_host = "localhost";
    // private $_user = "root";
    // private $_pass = "root";
    // private $_db   = "empresas";
    private $_host = "PMYSQL123.dns-servicio.com";
    private $_user = "alf-user-7336147";
    private $_pass = "qehKs9fwCFxtWDF";
    private $_db   = "7336147_Empresas_MSDES";

    // Store only one instance
    private static $_instance;

    // ================================================
    // Metodo para obtener instancia de base de datos
    // ================================================    
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    // ================================================
    // Constructor de la clase Base de datos
    // ================================================
    public function __construct()
    {
        $this->_connection = new mysqli($this->_host, $this->_user, $this->_pass, $this->_db);

        // Manejar error en base de datos
        if (mysqli_connect_error()) {
            trigger_error(ERRORDATABASE . " " . mysqli_connect_error(), E_USER_ERROR);
        }
    }

    // Metodo vacio __close para evitar duplicacion
    private function __close()
    {

    }

    // Metodo para obtener la conexion a la base de datos
    public function getConnection()
    {
        return $this->_connection;
    }

    // Metodo que revisa el String SQL
    private static function es_string ($sql)
    {
        if (!is_string($sql)) {
            trigger_error("class.Database.inc: sql: '$sql' enviado no es un string");
            return false;
        }
        return true;
    }

    // ==================================================
    // Funcion que ejecuta el SQL y retorna un Arreglo
    // ==================================================
    public static function get_array($sql)
    {
        if (!self::es_string($sql)) {
            exit();
        }
        $db = DataBase::getInstance();
        $mysqli = $db->getConnection();

        $resultado = $mysqli->query($sql);

        // Si hay un error en el SQL, este es el error de MySQL
        if (!$resultado) {
            return ERRORDATABASEREQUEST . " " . $mysqli->error;
        }

        $registros = array();

        while ($row = $resultado->fetch_assoc()) {
            array_push($registros, $row);
        };
        return $registros;
    }

    // ==================================================
    // Funcion que ejecuta el SQL y retorna un ROW
    // Esta funcion esta pensada para SQLs,
    // que retornen unicamente UNA sola línea
    // ==================================================
    public static function get_Row($sql)
    {
        if (!self::es_string($sql)) {
            exit();
        }
        $db = DataBase::getInstance();
        $mysqli = $db->getConnection();
        $result = $mysqli->query($sql);

        if ($row = $result->fetch_assoc()) {
            return $row;
        } else {
            return array();
        }
    }

    // ==================================================
    // Funcion que ejecuta el SQL y retorna un CURSOR
    // Esta funcion esta pensada para SQLs,
    // que retornen multiples lineas (1 o varias)
    // ==================================================
    public function get_Cursor($sql)
    {
        if (!self::es_string($sql)) {
            exit();
        }

        $db = DataBase::getInstance();
        $mysqli = $db->getConnection();

        return $mysqli->query($sql);
        //return $mysqli->query($sql) // Este resultado se puede usar así:  while ($row = $result->fetch_assoc()){...}
    }

    // ==================================================
    // Funcion que ejecuta el SQL y retorna un jSon
    // data: [{...}] con N cantidad de registros
    // ==================================================
    public static function get_json_rows($sql)
    {
        if (!self::es_string($sql)) {
            exit();
        }

        $db = DataBase::getInstance();
        $mysqli = $db->getConnection();

        $result = $mysqli->query($sql);

        // Si hay un error en el SQL, este es el error de MySQL
        if (!$result ) {
            return ERRORDATABASEREQUEST . " " .  $mysqli->error;
        }

        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $registros[$i]= $row;
            $i++;
        };
        return json_encode($registros);
    }

    // ==================================================
    // Funcion que ejecuta el SQL y retorna un jSon
    // de una sola linea. Ideal para imprimir un
    // Query que solo retorne una linea
    // ==================================================
    public static function get_json_row($sql)
    {

        if (!self::es_string($sql)) {
            exit();
        }

        $db = DataBase::getInstance();
        $mysqli = $db->getConnection();

        $result = $mysqli->query($sql);

        // Si hay un error en el SQL, este es el error de MySQL
        if (!$result ) {
            return ERRORDATABASEREQUEST . " " .  $mysqli->error;
        }

        if (!$row = $result->fetch_assoc()) {
            return "{}";
        }
        $result = Utils::utf8Converter($row);

        return json_encode($result);
    }

    // ====================================================================
    // Funcion que ejecuta el SQL y retorna un valor
    // Ideal para count(*), Sum, cosas que retornen una fila y una columna
    // ====================================================================
    public function get_value_query($sql, $column)
    {

        if (!self::es_string($sql)) {
            exit();
        }
        if (!self::es_string($column)) {
            exit();
        }
        $db = DataBase::getInstance();
        $mysqli = $db->getConnection();

        $result = $mysqli->query($sql);

        // Si hay un error en el SQL, este es el error de MySQL
        if (!$result) {
            return ERRORDATABASEREQUEST . " " .  $mysqli->error;
        }

        $value = null;
        // Trae el primer valor del arreglo
        if ($row = $result->fetch_assoc()) {
            // $Valor = array_values($row)[0];
            $value = $row[$column];
        }

        return $value;
    }

    // ====================================================================
    // Funcion que ejecuta el SQL de inserción, actualización y eliminación
    // ====================================================================
    public function execute_instDelUpd($sql)
    {

        if (!self::es_string($sql)) {
            exit();
        }
        $db = DataBase::getInstance();
        $mysqli = $db->getConnection();

        if (!$result = $mysqli->query($sql) ) {
            return ERRORDATABASEREQUEST . " " .  $mysqli->error;
        } else {
            return $result;
        }
    }

 
}

