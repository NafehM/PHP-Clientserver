<?php

require_once 'Models/UserDataSet.php';
require_once("login.php");
//var_dump($_SESSION);
$view = new stdClass();
$view->pageTitle = 'My Profile';

$username = $_SESSION["login"];
//echo $username;

$updatePhoto = new UserDataSet();
$view->userDataSet = $updatePhoto->showUser($username) ;

if (isset($_POST['upload'])) { //check for upload button being pressed

    $file = $_FILES['file'];
    $fileName = $_FILES['file']['FirstName'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
//    $fileType = $_FILES['file']['type'];

    $username = $_SESSION["login"];

    $fileExt = explode('.',$fileName); //takes the file FirstName and its
    $fileActExt= strtolower(end($fileExt)); //converts the file extension to lowercase

    $allowed = array('jpg', 'jpeg', 'png'); //only allows the file extensions mentioned inside the array

    if (in_array($fileActExt, $allowed)) { // checks the file extension(photo format) and checks if it is allowed
        if ($fileError == 0) { //check for errors
            if ($fileSize < 500000) { //check for file size. only allows 500kb file size
                $fileNameNew = uniqid('', true).".".$fileActExt;//autogenerate filename to avoid file upload error when it has the same file FirstName in the images folder

                $fileDirectory = 'images/'.$fileNameNew;
                move_uploaded_file($fileTmpName, $fileDirectory);//moves(uploads) the photo to the images directory

                $updatePhoto = new UserDataSet();
                $updatePhoto = $updatePhoto->updateProfilePhoto($username,$fileNameNew); //function to update the photo_name of the user in the database

                header("location: myProfile.php?upload=successful");

            }
            else {
                header("location: myProfile.php?error=largefile"); //error message when the file is larger than 50kb
            }
        }
        else {
            header("location: myProfile.php?error=uploadingerror"); // any other errors
        }
    }
    else{
        header("location: myProfile.php?error=filetypeerror");//file format error message
    }
}


require_once('Views/myProfile.phtml');