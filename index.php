<?php

require_once 'Models/UserDataSet.php';

$view = new stdClass();
$view->pageTitle = 'Homepage';

$usersDataSet = new UserDataSet();
$view->usersDataSet = $usersDataSet->fetchAllUsers();

require_once("login.php");

require_once('Views/index.phtml');