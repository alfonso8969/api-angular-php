<?php

include_once "./classes/class.Database.php";

$target_dir = "uploads/"; //image upload folder name
$target_file = $target_dir . basename($_FILES["image"]["name"]);
//moving multiple images inside folder
if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    $data = array("data" => "El archivo es válido y se cargó con éxito.");
    print json_encode($data);
}
