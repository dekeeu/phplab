<?php
/**
 * Created by PhpStorm.
 * User: dekeeu
 * Date: 21/05/2017
 * Time: 20:40
 */

require_once "utils/db.php";

function getAllReviewsCommand(){
    $rows = array();
    $query = "SELECT Users.id as uid, Users.name, Users.email, Reviews.id as rid, Reviews.title, Reviews.comment, Reviews.date FROM Reviews,Users,Users_To_Reviews 
          Where Users_To_Reviews.user_id = Users.id AND Users_To_Reviews.review_id = Reviews.id";

    $result = mysqli_query(getConnection(), $query);
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return json_encode($rows);
}

function getUserReviewsCommand($user_id){
    $rows = array();

    $query = "select * from Reviews, Users_To_Reviews WHERE Users_To_Reviews.user_id = '$user_id'";
    $result = mysqli_query(getConnection(), $query);
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return json_encode($rows);
}

function addReviewCommand($user_id, $title, $comment){
    $r1 = array();
    $r2 = array();

    $query = "INSERT INTO Reviews(title, comment, date) VALUES('$title', '$comment', '" . date("Y-m-d H:i:s") . "')";


    $result = mysqli_query(getConnection(), $query);
    $lastID = mysqli_insert_id(getConnection());

    while($row = mysqli_fetch_assoc($result)){
        $r1[] = $row;
    }

    $query = "INSERT INTO Users_To_Reviews VALUES('$user_id','$lastID')";

    $result = mysqli_query(getConnection(), $query);
    while($row = mysqli_fetch_assoc($result)){
        $r2[] = $row;
    }

    $rows = array_merge($r1, $r2);
    return json_encode($rows);
}

function editReviewCommand($review_id, $title, $comment){
    $rows = array();

    $query = "UPDATE Reviews SET title = '$title', comment = '$comment' WHERE Reviews.id = $review_id";
    $result = mysqli_query(getConnection(), $query);
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return json_encode($rows);
}

function deleteReviewCommand($review_id, $user_id){
    $r1 = array();
    $r2 = array();

    $query = "DELETE FROM Users_To_Reviews WHERE Users_To_Reviews.review_id = '$review_id' AND Users_To_Reviews.user_id = '$user_id'";

    $result = mysqli_query(getConnection(), $query);
    while($row = mysqli_fetch_assoc($result)){
        $r1[] = $row;
    }

    $query = "DELETE FROM Reviews WHERE Reviews.id = '$review_id'";
    $result = mysqli_query(getConnection(), $query);
    while($row = mysqli_fetch_assoc($result)){
        $r2[] = $row;
    }

    $rows = array_merge($r1, $r2);
    return json_encode($rows);
}

function searchByTitleCommand($q, $page){
    $rows = array();
    $query = "SELECT Users.id as uid, Users.name, Users.email, Reviews.id as rid, Reviews.title, Reviews.comment, Reviews.date FROM Reviews,Users,Users_To_Reviews 
          Where Users_To_Reviews.user_id = Users.id AND Users_To_Reviews.review_id = Reviews.id AND Reviews.title like '%$q%'";

    $result = mysqli_query(getConnection(), $query);

    /* Pagination */

    $total = mysqli_num_rows($result);
    $limit = 4;
    $pages = ceil($total / $limit);
    $currentPage = $page;

    $offset = ($currentPage - 1) * $limit;
    $start = $offset + 1;
    $end = min(($offset + $limit), $total);

    $query2 = "SELECT Users.id as uid, Users.name, Users.email, Reviews.id as rid, Reviews.title, Reviews.comment, Reviews.date FROM Reviews,Users,Users_To_Reviews 
          Where Users_To_Reviews.user_id = Users.id AND Users_To_Reviews.review_id = Reviews.id AND Reviews.title like '%$q%' 
          LIMIT $limit
          OFFSET $offset";


    $result2 = mysqli_query(getConnection(), $query2);

    while($row = mysqli_fetch_assoc($result2)){
        $rows[] = $row;
    }

    /* End Pagination */

    return json_encode($rows);
}

function searchByAuthor($q){
    $rows = array();
    $query = "SELECT Users.id as uid, Users.name, Users.email, Reviews.id as rid, Reviews.title, Reviews.comment, Reviews.date FROM Reviews,Users,Users_To_Reviews 
          Where Users_To_Reviews.user_id = Users.id AND Users_To_Reviews.review_id = Reviews.id AND Users.name like '%$q%'";

    $result = mysqli_query(getConnection(), $query);
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }

    return json_encode($rows);
}
