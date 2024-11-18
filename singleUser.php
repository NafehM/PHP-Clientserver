<?php

require_once 'Models/UserDataSet.php';
require_once("login.php");
//var_dump($_SESSION);
$view = new stdClass();
$view->pageTitle = 'User Profile';

$username = $_SESSION["login"];
$user = $_GET['user'];


if ($user == $username) {
    require_once ('myProfile.php');

}
else {
    $singleUserDataSet = new UserDataSet();
    $view->userDataSet = $singleUserDataSet->showUser($user) ;
    require_once('Views/singleUserDetails.phtml');
}



