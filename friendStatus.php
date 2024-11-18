<?php
$view = new stdClass();
$view->pageTitle = "Friends List";// this is page tile
require_once './login.php';//requiring user login session.


//checking is user is logged in or not if not divert him to index page
if(isset($_SESSION['user_id'])) {


    require_once './Views/friendStatus.phtml';
}else {
    //diverting to index page in user is not logged in.
    header("Location: ./index.php");
}
