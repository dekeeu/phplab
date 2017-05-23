<?php
/**
 * Created by PhpStorm.
 * User: dekeeu
 * Date: 17/05/2017
 * Time: 23:22
 */

$hostname = 'localhost';
$port = '3306';
$username = 'root';
$password = 'root';

$database = 'GuestBook';

$SQLConnection;

function createNewConnection($hostname, $username, $password, $database, $port){
    global $SQLConnection;
    $SQLConnection = mysqli_connect($hostname, $username, $password, $database, $port);
    if(!$SQLConnection){
        die('Could not connect: ' . mysqli_error());
    }
}

function selectDatabase($db_name){
    global $SQLConnection;
    mysqli_select_db($SQLConnection, $db_name);
}

function getConnection(){
    global $SQLConnection;
    return $SQLConnection;
}

createNewConnection($hostname, $username, $password, $database, $port);
