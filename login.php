<?php
if(session_id() == ''){
    session_start();
}
//session_start(['cookie_lifetime' => 86400,]);
//var_dump($_SESSION);

//check for login button being pressed
if (isset($_POST["loginBtn"])) {

    require_once 'Models/UserDataSet.php';
    require_once 'Models/UserData.php';

    $username = $_POST["username"];
    $password = $_POST["password"];

//check the input fields are empty
    if (empty($username) || empty($password)) {
        header("location: index.php?error=emptyfields&username=".$username);
        exit();
    } else {
        //create an object ($userDataSet) from UserDataSet class
        $userDataSet = new UserDataSet();
        //call loginValidation function in UserDataSet class and passes the inputs as parameter to it
        $user = $userDataSet->loginValidation($username, $password);
        header("location: index.php?login=successful");//redirect the user to the homepage after successful login

//        echo $username. " is logged in"; //to print the username of the logged-in user
            $_SESSION["login"] = $username;

    }
}
//check for logout button being pressed
if (isset($_POST["logoutBtn"]))
{
//    echo "You are logged out!";
    unset($_SESSION["login"]);
    session_destroy();
    header("location: index.php?logout=loggedout");
}



