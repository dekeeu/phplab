<?php
/**
 * Created by PhpStorm.
 * User: dekeeu
 * Date: 21/05/2017
 * Time: 19:51
 */

require_once 'utils/session_fix.php';

session_destroy();

header('Location: index.php');