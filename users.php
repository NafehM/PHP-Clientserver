<?php

require_once 'Models/UserDataSet.php';

$view = new stdClass();
$view->pageTitle = 'Users\' Details';

$userDataSet = new UserDataSet();
$view->userDataSet = $userDataSet->fetchAllUsers() ;


require_once("login.php");

require_once('Views/allUsersDetail.phtml');