<?php

//session_start();
require_once 'Models/UserDataSet.php';
require_once("login.php");
$token = $_GET["token"];//get token

/* Checking if the token is valid. */
if ($_SESSION['token'] == $token) {
    $userDataSet = new UserDataSet();//creating a user data set object
    $searchTxt = $_GET["searchTxt"];


    $responseText = '';

    if(isset($_SESSION['user_id'])) {
        $userId = $_GET["userId"];

        /* Calling the function liveSearchNewFriends from the UserDataSet class. */
        $dataSet = $userDataSet->searchNewFriends($userId, '%' . $searchTxt . '%');

        /* Checking if the data set is not null. */
        if ($dataSet !== null) {
            /* Encoding the data set into a JSON object. */
            $responseText = json_encode($dataSet);
        } else {
            $responseText = 'noRecord';

        }
        echo $responseText;

    }else{

        /* Calling the liveSearch function in the UserDataSet class. */
        $dataSet = $userDataSet->liveSearch('%' . $searchTxt . '%');

        /* Checking if the data set is not null. */
        if ($dataSet !== null) {
            /* Encoding the data set into a json format. */
            $responseText = json_encode($dataSet);
        } else {
            $responseText = 'noRecord';

        }
        echo $responseText;

    }


    }else {
    echo 'there is no data found.';
}