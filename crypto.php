<?php
include_once "./classes/utils.php";
$re = $_GET['re'];
echo Utils::encrypt($re);
