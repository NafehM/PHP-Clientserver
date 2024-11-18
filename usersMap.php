<?php

require_once 'Models/UserDataSet.php';
require_once("login.php");



//checking if user is logged in or not if not redirect him to index page
if (isset($_SESSION['user_id'])) {

    $responseText = '';

    /* It's getting the userId from the URL. */
    $userID = $_GET["userId"];

    $token = $_GET["token"];//get token
    /* It's checking if the token is the same as the one in the session. */
    if ($_SESSION['token'] == $token) {
        $userDataSet = new UserDataSet();//create a user data set object

        /* It's getting the user's friends from the database. */
        $userMapData = $userDataSet->showFriends($userID);
        /* It's adding the current user to the array. */
        $userMapData[] = $userDataSet->showCurrentUser($userID);
        /* It's encoding the data into JSON. */
        $responseText = json_encode($userMapData);

    } else {
        $responseText = 'there is no data found.';
    }
    echo $responseText;

}


//require_once ('./Views/myFriends.phtml');//redirect it to the index page for now



