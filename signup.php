<?php

$view = new stdClass();
$view->pageTitle = 'Signup';
//var_dump($_POST);

if (isset($_POST["sign-upBtn"])) {
    var_dump($_POST);
//    require_once 'Models/Database.php';
    require_once 'Models/UserDataSet.php';

    $username = $_POST["username"];
    $first_name = $_POST["firstName"];
    $last_name = $_POST["lastName"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRe = $_POST["password-repeat"]; //Repeat password for password verification
    $antispam = $_POST["antispam"];

    if (empty($username) || empty($first_name) || empty($last_name)  || empty($email) || empty($password) ||empty($passwordRe)) {
        //checks if there is any field left empty. if yes, it fills the fields that were filled; leaves the password fields empty.
        header("location: signup.php?error=emptyinput&firstName=".$first_name."&lastName=".$last_name."&username=".$username."&email=".$email);
        exit();
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username) )  {
        //checks if both email & username are valid. if not, it fills the fields that were filled; leaves the invalid field & password fields empty.
        header("location: signup.php?error=invalidusernameandemail&firstName=".$first_name."&lastName=".$last_name);
        exit();
    }
    elseif (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        //checks if the username is valid. if not, it fills the fields that were filled; leaves the username field & password fields empty.
        header("location: signup.php?error=invalidusername&firstName=".$first_name."&lastName=".$last_name."&email=".$email);
        exit();
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //checks if the email is valid. if not, it fills the fields that were filled and valid; leaves the email field & password fields empty.
        header("location: signup.php?error=invalidemail&firstName=".$first_name."&lastName=".$last_name."&username=".$username);
        exit();
    }
    elseif ($password !== $passwordRe) {
        //checks if the passwords match. if not, it fills the fields and leaves password fields empty.
        header("location: signup.php?error=passwordmissmatch&firstName=".$first_name."&lastName=".$last_name."&username=".$username."&email=".$email);
        exit();
    }
    elseif ($antispam !=="on") {
        header("location: signup.php?error=antispam&firstName=".$first_name."&lastName=".$last_name."&username=".$username."&email=".$email);
        exit();
    }
    else {
        $userDataSet = new UserDataSet();
        $addNewUser = $userDataSet->addUser($username,$first_name, $last_name, $email, $password);
        header("location: signup.php?signup=successful");
    }
}

require_once("login.php");

require_once('Views/signup.phtml');
