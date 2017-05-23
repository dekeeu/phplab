<?php
/**
 * Created by PhpStorm.
 * User: dekeeu
 * Date: 17/05/2017
 * Time: 22:57
 */

require_once "utils/session_fix.php";
require_once "utils/ui.php";
require_once "utils/userInfo.php";
require_once "utils/pages.php";


function showContent(){
    if(userIsAdmin()){
        displayPage(getAdminGUI());
    }else{
        displayPage(getUserGUI());
    }
}

function showOptions(){
    if(userIsLoggedIn() == false){
        header('Location: login.php');
    }else{
        showContent();
    }
}

showOptions();


