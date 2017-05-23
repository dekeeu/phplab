<?php
/**
 * Created by PhpStorm.
 * User: dekeeu
 * Date: 21/05/2017
 * Time: 22:39
 */

require_once "utils/session_fix.php";
require_once "utils/commands.php";

function addReview(){
    if($_SERVER['REQUEST_METHOD'] != 'POST'){
        die('Invalid method !');
    }
    if(!isset($_POST['title']) || !isset($_POST['comment']) || empty($_POST['title']) || empty($_POST['comment'])){
        die('Invalid input !');
    }

    $title = mysqli_real_escape_string(getConnection(), $_POST['title']);
    $comment = mysqli_real_escape_string(getConnection(), $_POST['comment']);

    addReviewCommand($_SESSION['GuestBookApp']['userID'], $title, $comment);
}

function getAllReviews(){
    echo getAllReviewsCommand();
}

function deleteReview(){
    if($_SERVER['REQUEST_METHOD'] != 'POST'){
        die('Invalid method !');
    }
    if(!isset($_POST['review_id']) || $_POST['review_id'] < 0 || !isset($_POST['user_id']) || $_POST['user_id'] < 0){
        die('Invalid input !');
    }

    $userID = (int)$_POST['user_id'];
    $reviewID = (int)$_POST['review_id'];

    deleteReviewCommand($reviewID, $userID);


}

function editReview(){
    if($_SERVER['REQUEST_METHOD'] != 'POST'){
        die('Invalid method !');
    }
    if(!isset($_POST['id']) || $_POST['id'] < 0 || empty($_POST['title']) || empty($_POST['comment'])){
        die('Invalid input !');
    }

    $review_id = (int)$_POST['id'];
    $title = mysqli_real_escape_string(getConnection(), $_POST['title']);
    $comment = mysqli_real_escape_string(getConnection(), $_POST['comment']);

    editReviewCommand($review_id, $title, $comment);
}

function searchReviews(){
    if($_SERVER['REQUEST_METHOD'] != 'POST'){
        die('Invalid method !');
    }

    if(isset($_GET['page']) && intval($_GET['page']) != 0){
        $p = intval($_GET['page']);
    }else{
        $p = 1;
    }

    $q = "";
    $col = "";

    if(isset($_POST['q']) || strlen($_POST['q']) > 0){
        $q = mysqli_real_escape_string(getConnection(), $_POST['q']);
    }

    if(isset($_POST['search_by']) && strlen($_POST['search_by']) > 0){
        if($_POST['search_by'] == "title"){
            $col = "title";
        }else{
            $col = "author";
        }
    }

    if(strlen($q) == 0){
        echo getAllReviewsCommand();
    }else{
        if($col == "title"){
            echo searchByTitleCommand($q, $p);
        }else{
            echo searchByAuthor($q);
        }
    }
}

function start(){
    if(isset($_GET['a']) && !empty($_GET['a'])){
        $action = $_GET['a'];
    }else{
        die('Invalid action !');
    }

    switch ($action){
        case "add" : addReview(); break;
        case "delete" : deleteReview(); break;
        case "edit" : editReview(); break;
        case "search" : searchReviews(); break;
        case "all" : getAllReviews(); break;
        default: die('Invalid action !');
    }
}

start();