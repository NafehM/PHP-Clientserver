<?php

require_once 'Models/UserDataSet.php';
require_once("login.php");


$view = new stdClass();
$view->pageTitle = 'Find New Friends';

$userDataSet = new UserDataSet();
//$view->userDataSet = $userDataSet->fetchAllUsers() ;
if(isset($_SESSION['user_id'])){

    $userId = $_SESSION['user_id'];
    $view->newFriendDataSet = $userDataSet->fetchNewFriends($userId);


}


require_once('Views/allUsersDetail.phtml');