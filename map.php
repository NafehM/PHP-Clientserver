<?php

require_once 'Models/UserDataSet.php';
require_once("login.php");


$view = new stdClass();
$view->pageTitle = 'Map';

//checking is user is logged in or not if not divert him to index page
if (isset($_SESSION['user_id'])) {


    require_once './Views/map.phtml';



} else {
    //if user is not logged in and trying to access this page diverts him to index page
    header("Location: ./index.php");
}


//require_once ('./Views/myFriends.phtml');//redirect it to the index page for now



