<?php

require_once 'Models/UserDataSet.php';
require_once 'Models/FriendsData.php';
require_once("login.php");


$view = new stdClass();
$view->pageTitle = 'My Friends';

//checking is user is logged in or not if not divert him to index page
if (isset($_SESSION['user_id'])) {
    $currentUserId = $_SESSION['user_id'];//get current user id

    $userDataSet = new UserDataSet();//creating a user data set object
    //fetching fetchMyFriends data from the database
    $dataSet = $statement = $userDataSet->getMyFriends($currentUserId);



    //passing dataSet array to view
    $view->extendDataSet = $dataSet;

    //if rejectRequest button is pressed
    if (isset($_POST['rejectRequest'])) {


        $friendsId = $_GET['friendsId'];//get friend table id
        $userName = $_GET['name'];//get username

        require_once './Models/UserDataSet.php';

        $userDataSet = new UserDataSet();//creating a user data set object
        //call user data set object function rejectRequest to delete request from the database
        $userDataSet->rejectRequest($friendsId, $currentUserId);
        //on successful  rejection show a message to the user
        $response = header("Location: ./friendshipController.php?action=reject&name=$userName");
//        require_once './Views/friendStatus.phtml';
        exit();//stop the code for running further.


    }
    //if cancelRequest button is pressed
    if (isset($_POST['cancelRequest'])) {


        $friendID = $_POST['friendId'];//get friend table id
        $name = $_POST['name'];//get username


        $userDataSet = new UserDataSet();//creating a user data set object
        //call user data set object function cancelRequest to delete request from the database
        $userDataSet->cancelRequest($friendID, $currentUserId);
        //on successful  cancellation show a message to the user
        header("Location: ./friendshipController.php?action=cancel&name=$name");
        exit();//stop the code for running further.


    }
    //if acceptRequest button is pressed
    if (isset($_POST['acceptRequest'])) {


        $friendsId = $_POST['friendsId'];//get friend table id
        $userId = $_POST['userId'];//get user id
        $name = $_POST['name'];//get username


        $userDataSet = new UserDataSet();//creating a user data set object
        //call user data set object function acceptRequest to delete request from the database
        $userDataSet->acceptRequest($friendsId, $userId);
        //on acceptance show a message to the user
        header("Location: ./friendshipController.php?action=accept&name=$name");
        exit();//stop the code for running further

    }

    //if unfriend button is pressed
    if (isset($_POST['unfriend'])) {

        $friendID = $_POST['friendId'];//get friend table id
        $name = $_POST['name'];//get username

        $userDataSet = new UserDataSet();//creating a user data set object
        //call user data set object function unfriend to delete request from the database
        $userDataSet->unfriend($friendID);
        //on successful  unfriend show a message to the user
        header("Location: ./friendshipController.php?action=unfriend&name=$name");
        exit();//stop the code for running further

    }

    require_once('./Views/myFriends.phtml');

} else {
    //if user is not logged in and trying to access this page redirects him to index page
    header("Location: ./index.php");
}

//require_once ('./Views/myFriends.phtml');//redirect it to the index page for now



