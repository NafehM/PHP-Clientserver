<?php

require_once 'Models/UserDataSet.php';
require_once("login.php");

$view = new stdClass();
$view->pageTitle = 'Friend Finding';

$username = $_SESSION["login"];
//echo $username;
if (isset($_SESSION['user_id'])) {
    $response = '';
    $currentUserId = $_SESSION['user_id'];//it is current user id
    if (isset($_POST['add'])) {


        $userId = $_POST ['userId'];//get user id
        $userName = $_POST ['firstName'];//get user name


        $userDataSet = new UserDataSet();//create a new user data set object
        $userDataSet->addFriend($currentUserId,$userId);//call user data set object function sendRequest
        //once request sent successfully return user to friendship status page to show the result.
       $response = header("Location: ./friendshipController.php?action=sendRequest&name=$userName");
        exit();//stop running the code further.0


    }
    //if cancel request button is pressed
    if (isset($_POST['cancelRequest'])) {


        $userId = $_POST['friendId'];//get user id
        $name = $_POST['name'];//get username


        $userDataSet = new UserDataSet();//create a new user data set object
        $userDataSet->cancelRequest($userId, $currentUserId);//call user data set object function cancelRequest
        //once cancelled successfully return user to friendship status page to show the result.
       $response = header("Location: ./friendshipController.php?action=cancel&name=$name");
        exit();//stop running the code further.


    }


    $response;
    require_once './Views/friendship.phtml';



//    $updatePhoto = new UserDataSet();


}else{
    //diverting to index page in user is not logged in.
    header("Location: ./index.php");
}
