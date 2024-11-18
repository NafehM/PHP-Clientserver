<?php

require_once 'Models/UserDataSet.php';
require_once("login.php");



//checking is user is logged in or not if not divert him to index page
if (isset($_SESSION['user_id'])) {

    $responseText = '';

    /* It's getting the userId from the URL. */
    $userID = $_GET["userId"];
    $longitude = $_GET["longitude"];
    $latitude = $_GET["latitude"];

    $token = $_GET["token"];//get token
    /* It's checking if the token is the same as the one in the session. */
    if ($_SESSION['token'] == $token) {
        $userDataSet = new UserDataSet();//create a user data set object
        //call user data set function loginDetails and pass it two parameters username and password to verify with database
        $userMapData = $userDataSet->updateUserPosition($userID,$longitude,$latitude);
//        $userMapData = $userDataSet->showFriendsOnMap($userID);
        $responseText = 'user position update successful';

    } else {
        $responseText = 'there is no data found.';
    }
    echo $responseText;




}


//require_once ('./Views/myFriends.phtml');//redirect it to the index page for now



