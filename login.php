<?php
/**
 * Created by PhpStorm.
 * User: dekeeu
 * Date: 17/05/2017
 * Time: 23:12
 */

require_once 'utils/db.php';
require_once 'utils/session_fix.php';
require_once 'utils/ui.php';

$OUT = getHeader();

function show_login_form($message = ''){
    global $OUT;

    $OUT .= "<div class='container-fluid'>";

    if($message != '') {
        $OUT .= "<h3>" . $message . "</h3>";
    }

    $OUT .= "<form action='login.php' method='POST'>
            <div class=\"form-group\">
            <label for='email'>Email:</label>
            <input type='email' class='form-control' id='email' placeholder=\"Enter email\" name=\"email\">
        </div>
            
            <div class=\"form-group\">
      <label for=\"password\">Password:</label>
      <input type=\"password\" class=\"form-control\" id=\"password\" placeholder=\"Enter password\" name=\"password\">
    </div>
            
            <button type=\"submit\" class=\"btn btn-default\">Submit</button>
        </form>

    </div>";



    $OUT .= getFooter();

    echo $OUT;
}

function logUser(){
    $email = '';
    $password = '';

    if(isset($_POST['email']) && !empty($_POST['email'])){
        $email = mysqli_real_escape_string(getConnection(), $_POST['email']);
    }

    if(isset($_POST['password']) && !empty($_POST['password'])){
        $password = mysqli_real_escape_string(getConnection(), $_POST['password']);
    }


    $query = "SELECT * FROM Users WHERE email = '$email' and password = '$password'";
    $result = mysqli_query(getConnection(), $query);


    if(mysqli_num_rows($result) == 0){
        show_login_form('Invalid credentials !');
    }else{
        $_SESSION['GuestBookApp'] = array();
        $_SESSION['GuestBookApp']['logged'] = 1;

        while($row = mysqli_fetch_assoc($result)){
            $_SESSION['GuestBookApp']['userID'] = (int)$row['id'];
            $_SESSION['GuestBookApp']['userFullName'] = $row['name'];
            $_SESSION['GuestBookApp']['userEmail'] = $row['email'];
            $_SESSION['GuestBookApp']['userLevel'] = (int)$row['level'];
        }

        header('Location: index.php');
    }

}

function chooseContent(){
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(!isset($_SESSION['GuestBookApp'])) {
            show_login_form();
        }else{
            header('Location: index.php');
        }
    }else if($_SERVER['REQUEST_METHOD'] == 'POST'){
        logUser();
    }else{
        echo "We can't handle this !";
    }
}

chooseContent();

