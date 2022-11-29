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
 * @link     https://bitbucket/private/repository
 */

 require "./classes/utils.php";

 $empDetId = Utils::getLastIdOfEmpDet();
 echo $empDetId;