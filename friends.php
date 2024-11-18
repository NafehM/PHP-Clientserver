<?php
//session_start();
require_once 'Models/UserDataSet.php';

$view = new stdClass();
$view->pageTitle = 'Homepage';

$userDataSet = new UserDataSet();
$view->userDataSet = $userDataSet->fetchAllUsers() ;

//shows all users until I work on it again

require_once("login.php");

require_once('Views/allUsersDetail.phtml');