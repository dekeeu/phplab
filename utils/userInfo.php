<?php
/**
 * Created by PhpStorm.
 * User: dekeeu
 * Date: 21/05/2017
 * Time: 20:29
 */

session_start();

function userIsLoggedIn(){
    if(!isset($_SESSION['GuestBookApp']) || !isset($_SESSION['GuestBookApp']['logged']) || $_SESSION['GuestBookApp']['logged'] != '1'){
        return false;
    }
    return true;
}

function userIsAdmin(){
    if(!isset($_SESSION['GuestBookApp']) || !isset($_SESSION['GuestBookApp']['logged']) || $_SESSION['GuestBookApp']['logged'] != '1'){
        die('User is not logged in !');
    }else if($_SESSION['GuestBookApp']['userLevel'] == 0){
        return true;
    }else{
        return false;
    }
}