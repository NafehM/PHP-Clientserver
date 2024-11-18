<?php
require_once ('Models/Database.php');
require_once ('Models/UserData.php');
require_once ('Models/UserExtendedData.php');
require_once ('Models/FriendsData.php');

class UserDataSet {
    protected $_dbHandle, $_dbInstance;

    /**
     * The constructor of the class
     */
    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function LiveSearch($searchTxt){

        $sqlQuery = "SELECT user_id, username, first_name, last_name, email, photo_name FROM users WHERE username OR first_name or email LIKE ? ;";
        // $sqlQuery = "SELECT user_id , real_name, email, photo_name FROM users WHERE real_name LIKE ?  ORDER BY real_name $orderBy LIMIT ?,?;";
        $statement = $this->_dbHandle->prepare($sqlQuery);

        if (!$this->_dbHandle->prepare($sqlQuery)) {
            // Returning the string "sqlerror"
            return "sqlerror";
            exit();//it stops the code to run further.
        } else {
            //this is bindParam avoid the sql injection or any
            // unwanted characters to be inserted into the query
            $statement->bindParam(1, $searchTxt, PDO::PARAM_STR);
            $statement->execute();

            $dataSet =[];//this dataSet array to hold user data
            while ($row = $statement->fetch()) {
                if(isset($_SESSION['user_id'])){
                    $dataSet[] = new UserExtendedData($row);
                }else{
                    //it creates new user and adds it to dataSet array
                    $dataSet[] = new UserData($row);
                }
            }
            return $dataSet;//returns the dataSet array back to user.

        }
    }

    /**
     * @param $currentUserId
     * @param $searchTxt
     * @return array
     */
    public function searchNewFriends($currentUserId, $searchTxt)
    {
        $sqlQuery = "SELECT u2.*
                    FROM  users u1,  users u2 
                    WHERE NOT EXISTS(SELECT 1
                                     FROM friends f
                                     WHERE f.sending_to = u1.user_id AND
                                             f.sending_from = u2.user_id)
                      AND NOT EXISTS(SELECT 1
                                     FROM friends f
                                     WHERE f.sending_to = u2.user_id AND
                                             f.sending_from = u1.user_id)
                      AND u1.user_id != u2.user_id 
                      AND u1.user_id = $currentUserId 
                      AND u2.username OR u2.first_name OR u2.last_name OR u2.email LIKE ? ; ";

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        //this is bindParam avoid the sql injection or any
        // unwanted characters to be inserted into the query
//        $statement->bindParam(1, $searchBy, PDO::PARAM_STR);
        $statement->bindParam(1, $searchTxt, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement

        //while loop runs and creates a data set of users
        $dataSet = [];//it holds the all users one by one
        while ($row = $statement->fetch()) {
            //query row is passed to userExtendedData object to process the information
            $dataSet[] = new UserExtendedData($row);
        }
        return $dataSet;//it returns the data set of users from the query result.

    }

    /**
     * @param $currentUserId
     * @return array|string
     */
    public function showFriends($currentUserId){
        //this query joins to tables users and friend table and
        //creates a new table to show the current users
        //sent the request or request is sent to current users.
        $sqlQuery = "SELECT * FROM  (SELECT * FROM users WHERE users.user_id IN 
                    (SELECT sending_from as Sending FROM friends WHERE (friends.sending_from = $currentUserId OR  friends.sending_to = $currentUserId)
                    UNION
                    SELECT sending_to Receive FROM friends WHERE friends.status=2) 
                    AND users.user_id !=$currentUserId)ping inner join friends where ((sending_from=ping.user_id and sending_to=$currentUserId) or
                    (sending_from=$currentUserId and sending_to=ping.user_id ));";

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        if (!$this->_dbHandle->prepare($sqlQuery)) {
            // Returning the string "sqlerror"
            return "sqlerror";
            exit();//it breaks the code to run further.
        } else {
            $statement->execute(); // execute the PDO statement
            //while loop runs and creates a data set of users
            $dataSet = [];//it holds the all users one by one
            while ($row = $statement->fetch()) {
                //query row is passed to userExtendedData object to process the information
                $dataSet[] = new UserExtendedData($row);
            }
            return $dataSet;//it returns the data set of users from the query result.
        }
    }

    public function showCurrentUser($currentUserId){

        // The above code is selecting all the data from the users table where the user_id is equal to
        //the current user id.
        $sqlQuery = "SELECT * FROM  users WHERE user_id = $currentUserId;";

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        if (!$this->_dbHandle->prepare($sqlQuery)) {
            // Returning the string "sqlerror"
            return "sqlerror";
            exit();//it breaks the code to run further.
        } else {
            $statement->execute(); // execute the PDO statement
            //while loop runs and creates a data set of users
            $dataSet ='';//it holds the all users one by one
            while ($row = $statement->fetch()) {
                //query row is passed to userExtendedData object to process the information
                $dataSet = new UserExtendedData($row);
            }
            return $dataSet;//it returns the data set of users from the query result.
        }
    }

    public function updateUserPosition($userId, $longitude, $latitude){

        //it checks if user entered username or email
        $sqlQuery = "UPDATE users SET longitude = ?, latitude = ? WHERE user_id = ?;";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        if (!$this->_dbHandle->prepare($sqlQuery)) {
            //if error in the query it returns the user to the index page
            return "sqlerror";
            exit();//it stops the code to run further.
        } else {

            //this is bindParam avoid the sql injection or any
            // unwanted characters to be inserted into the query
            $statement->bindParam(1, $longitude, PDO::PARAM_INT);
            $statement->bindParam(2, $latitude, PDO::PARAM_INT);
            $statement->bindParam(3, $userId, PDO::PARAM_INT);
            return $statement->execute();
        }

    }
    /**
     * fetch all users' data
     * @return array
     */
    public function fetchAllUsers(): array
    {
        $sqlQuery = 'SELECT * FROM users';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];

        // old version
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }

    /**
     * retrieve the logged in user's details
     * @param $username
     * @return array|void
     */
    public function showUser($username) {
        $sqlQuery = "SELECT * FROM users WHERE username=?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1, $username, type: PDO::PARAM_STR);
        $statement->execute();
        try {
            if (!$this->_dbHandle->prepare($sqlQuery)) { //checks for connection and sql error
                header("location: ../index.php?error=sqlerror");
                exit();
            }
            elseif ($statement->rowCount() == 0) { //check for user's availability
                header("location: index.php?error=usernotfound");
                exit();
            }
            elseif ($statement->rowCount() > 0) {

                $row = $statement->fetch(PDO::FETCH_ASSOC);
                $dataSet[] = new UserData($row);
                return $dataSet;
            }
        }
        catch (PDOException $e) { //
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * retrieves friends
     * @param $currentUserId
     * @return array|void
     */
    public function getMyFriends($currentUserId)
    {
        //this query joins to tables users and friend table and
        //creates a new table to show the current users
        //sent the request or request is sent to current users.
        $sqlQuery = "SELECT * FROM  (SELECT * FROM users WHERE users.user_id IN 
                    (SELECT sending_from as Sending FROM friends WHERE (friends.sending_from = $currentUserId OR  friends.sending_to = $currentUserId)
                    UNION
                    SELECT sending_to Receive FROM friends WHERE (friends.sending_from = $currentUserId OR friends.sending_to = $currentUserId)) 
                    AND users.user_id !=$currentUserId)ping inner join friends where ((sending_from=ping.user_id and sending_to=$currentUserId) or
                    (sending_from=$currentUserId and sending_to=ping.user_id ));";

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        if (!$this->_dbHandle->prepare($sqlQuery)) {
            //if issue with query then returns the user with error to myFriend page.
            header("location: ../index.php?error=sqlerror");
            exit();//it breaks the code to run further.
        } else {

            $statement->execute(); // execute the PDO statement

            $dataSet = [];//array variable to store user records
            while ($row = $statement->fetch()) {
                //creating user object and passing information to it and storing each use record in dataSet variable
                $dataSet[] = new FriendsData($row);
            }

            return $dataSet;//it returns the query results back the function call.
        }
    }

    /**
     * allow users to send friend request
     * @param $currentUser
     * @param $sendingTo
     */
    public function addFriend($currentUser, $sendingTo)
    {
        //this query inserts into friends table where sending from will be current user and sending to
        // will be the target user.
        $sqlQuery = "INSERT INTO friends (sending_from, sending_to, status) VALUES ($currentUser,$sendingTo,1);";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        if (!$this->_dbHandle->prepare($sqlQuery)) {
            header("location: ../index.php?error=sqlerror");
            exit();//it stops the code to run further.
        } else {
            $statement->execute();
        }

    }

    /**
     * allows users to accept friendship requests
     * @param $friendsId
     * @param $userId
     */
    public function acceptRequest($friendsId, $userId)
    {
        //this query will update the status column of the friends table to 2 when user
        //click on accept.
        $sqlQuery = "Update friends SET status = 2 WHERE friendship_id = $friendsId AND sending_from = $userId ;";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        if (!$this->_dbHandle->prepare($sqlQuery)) {
            header("location: ../index.php?error=sqlerror");
            exit();//it stops the code to run further.
        } else {
            $statement->execute();
        }
    }

    /**
     * allows users to cancel requests
     * @param $friendsId
     * @param $userId
     */
    public function cancelRequest($friendsId, $userId)
    {
        //this query deletes the row where sending from is equals to the current user id.
        $sqlQuery = "DELETE FROM friends WHERE friendship_id = $friendsId AND sending_from = $userId ;";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        if (!$this->_dbHandle->prepare($sqlQuery)) {
            header("location: ../index.php?error=sqlerror");
            exit();//it stops the code to run further.
        } else {
            $statement->execute();
        }
    }

    /**
     * allows users to unfriend their friends
     * @param $friendsId
     */
    public function unfriend($friendsId)
    {
        //this query deletes the record from the table where table id is equal to
        //id is being passed through the parameter.
        $sqlQuery = "DELETE FROM friends WHERE friendship_id = $friendsId ;";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        if (!$this->_dbHandle->prepare($sqlQuery)) {
            header("location: ../index.php?error=sqlerror");
            exit();//it stops the code to run further.
        } else {
            $statement->execute();
        }

    }

    /**
     * to update the profile photo
     * @param $username
     * @param $fileNameNew
     */
    public function updateProfilePhoto($username,$fileNameNew) {
        try {
            $sqlQuery= "UPDATE users SET photo_name = ? WHERE username = ?";//sql query update the photo_name record in the database
            $statement = $this->_dbHandle->prepare($sqlQuery);//prepared statement
            $statement->bindParam(1, $fileNameNew, type: PDO::PARAM_STR); //$fileNameNew is autogenerated which doesn't need binding parameter, but still used for extra safety
            $statement->bindParam(2, $username, type: PDO::PARAM_STR); // binding parameter to reduce the risk of sql injection
            $statement->execute();
        }
        catch (PDOException $e) { // catching exceptions
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * @param $userID
     * @return array|void
     */
    public function fetchNewFriends($userID)
    {
        $sqlQuery = "SELECT u2.*
                    FROM  users u1,  users u2 
                    WHERE NOT EXISTS(SELECT 1
                                     FROM friends f
                                     WHERE f.sending_to = u1.user_id AND
                                             f.sending_from = u2.user_id)
                      AND NOT EXISTS(SELECT 1
                                     FROM friends f
                                     WHERE f.sending_to = u2.user_id AND
                                             f.sending_from = u1.user_id)
                      AND u1.user_id != u2.user_id 
                      AND u1.user_id = $userID ;";

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        if (!$this->_dbHandle->prepare($sqlQuery)) {
            //if issue with query then returns the user with error to myFriend page.
            header("location: ../index.php?error=sqlerror");
            exit();//it breaks the code to run further.
        } else {
            $statement->execute(); // execute the PDO statement
            $dataSet = [];//array variable to store user records
            while ($row = $statement->fetch()) {
                //creating user object and passing information to it and storing each use record in dataSet variable
                $dataSet[] = new UserExtendedData($row);
            }
            return $dataSet;

        }

    }

    /**
     * get list of friends and their status. This function is not completed yet
     * @param $currentUser
     * @return array|void
     */
    public function fetchAllFriends($currentUser) {
        $sqlQuery = "SELECT * FROM (
                    SELECT* FROM users 
                        WHERE users.user_id in (
                            SELECT friend1 as friend 
                            FROM friends
                            WHERE(friends.friend1=? OR friends.friend2 = ?)
                            UNION 
                            SELECT friend2 as friend
                            FROM friends
                            WHERE (friends.friend1 = ? OR friends.friend2 = ?)
                        )
                            AND users.user_id !=?
                    ) ping inner join friends WHERE ((friend1=ping.user_id AND friend2=?) OR (friend1=? AND friend2=ping.user_id))";

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
//        $statement->execute();

        try {
            if (!$this->_dbHandle->prepare($sqlQuery)) { //checks for connection and sql error
                header("location: ../index.php?error=sqlerror");
                exit();
            }
            else {
                $dataSet = [];
                while ($row = $statement->fetch()) {
                    $dataSet[] = new FriendsData($row);
                }
                return $dataSet;
            }
        }
        catch (PDOException $e) { //
            echo "Error: " . $e->getMessage();
        }
    }




    /**
     * login validation with username or email and password
     * @param $username
     * @param $password
     * @return array|UserDataSet|void
     */
    public function loginValidation($username, $password) {
//          //it checks if user entered username or email
        $sqlQuery = 'SELECT * FROM users WHERE username=? OR email=?;';
        $statement = $this->_dbHandle->prepare($sqlQuery); //creating a prepared statement to avoid sql injection.
        if (!$this->_dbHandle->prepare($sqlQuery)) {
            //if error in the query it returns the user to the index page
            header("location: SocialNet/index.php?error=sqlerror");
            exit();//it stops the code to run further.
        } else {

            //this is bindParam avoid the sql injection or any
            // unwanted characters to be inserted into the query
            $statement->bindParam(1, $username, PDO::PARAM_STR);
            $statement->bindParam(2, $username, PDO::PARAM_STR);
            $statement->execute();

            //it fetches the row matched
            if ($row = $statement->fetch()) {
                //it verifies the entered password with the database password
                $passwordCheck = password_verify($password, $row['password']);
                //if password is false it runs
                if ($passwordCheck == false) {
                    //it sends the user back to index page
                    header("location: index.php?error=wrongpassword");
                    exit();//it stops the code to run further.
                    //if password is correct it runs
                } elseif ($passwordCheck) {
                    //session starts here
                    $_SESSION['user_id'] = $row['user_id'];//setting user id
                    $_SESSION['username'] = $row['username'];//setting username
                    $_SESSION['first_name'] = $row['first_name'];//setting real FirstName
                    $_SESSION['photo_name'] = $row['photo_name'];//setting the photo FirstName
                    //returning the user to myFriend page
                    return "loginsuccessful";
                    exit();//it stops the code to run further.

                } else {
                    //if username is wrong it take user back to index page with error message
                    header("location: index.php?error=usernotfound");
                    exit();//it stops the code to run further.
                }
            } else {
                /* Checking if the username is correct. */
                header("location: index.php?error=usernotfound");
                exit();//it stops the code to run further.
            }
        }

    }

    /**
     * adding or signing up a user and insert data to the database
     * @param $username
     * @param $first_name
     * @param $last_name
     * @param $email
     * @param $password
     */
    public function addUser($username, $first_name, $last_name, $email, $password){
        $photoName = "default.png";
        $sqlQuery = "SELECT username FROM users WHERE username=? OR email=?"; //sql statement for later to check if the username is already exist or the email is used
        $statement = $this->_dbHandle->prepare($sqlQuery); //creating a prepared statement to avoid sql injection.
        try {
            if (!$this->_dbHandle->prepare($sqlQuery)) { //checks for connection and sql error
                header("location: ../signup.php?error=sqlerror");
                exit();
            }
            else {
                $statement->bindParam(1, $username, type: PDO::PARAM_STR); //binds the value to the parameter to tell the database what data type to expect to avoid sql injection
                $statement->bindParam(2, $email, type: PDO::PARAM_STR);
                $statement->execute();

                if ($statement->fetch() > 0) { //checks if the user is taken(when number of the rows is larger than 0)
                    header("location: ../signup.php?error=userORemailtaken&firstName=".$first_name."&lastName=".$last_name);
                    exit();
                }
                else { //when the username isn't taken, it inserts the data into the database
                    $sqlQuery = "INSERT INTO users (username, first_name, last_name, email, password, photo_name) VALUES (?, ?, ?, ?, ?, ?)";
                    $statement = $this->_dbHandle->prepare($sqlQuery); // using prepared statement and passing th e sqlQuery as parameter
                    if (!$this->_dbHandle->prepare($sqlQuery)) {// checking if there is no connection or sql error
                        header("location: ../signup.php?error=sqlerror");
                        exit();
                    }
                    else {
                        $hashPassword = password_hash($password, PASSWORD_DEFAULT); // hashing the password using the bcrypt algorithm(PHP's default)

                        //binding the values to the parameters to avoid the risk of sql injections and inserting data to the database
                        $statement->bindParam(1, $username, type: PDO::PARAM_STR);
                        $statement->bindParam(2, $first_name, type: PDO::PARAM_STR);
                        $statement->bindParam(3, $last_name, type: PDO::PARAM_STR);
                        $statement->bindParam(4, $email, type: PDO::PARAM_STR);
                        $statement->bindParam(5, $hashPassword, type: PDO::PARAM_STR);//inserting the hashed password into the database
                        $statement->bindParam(6, $photoName,type: PDO::PARAM_STR);
                        $statement->execute();
                    }
                }
            }
        }
        catch (PDOException $e) { //
            echo "Error: " . $e->getMessage();
        }
        $sqlQuery = null;
        $statement = null;
    }

    /**
     * search function to search for username, email, firstname or lastname
     * @param $searchWord
     * @return array|void
     */

    public function search($searchWord)
    {
        $sqlQuery = "SELECT * FROM users WHERE username LIKE '%$searchWord%' OR first_name LIKE '%$searchWord%' OR last_name LIKE '%$searchWord%' OR email LIKE '%$searchWord%'";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
        try {
            if (!$this->_dbHandle->prepare($sqlQuery)) { //checks for connection and sql error
                header("location: ../index.php?error=sqlerror");
                exit();
            }
            elseif ($statement->rowCount() > 0) {
                $dataSet = [];
                // old version
                while ($row = $statement->fetch()) {
                    $dataSet[] = new UserData($row);
                }

                return $dataSet; // check if it returns anything

            }
            elseif ($statement->rowCount() == 0) { //check for user's availability
                header("location: index.php?error=usernotfound");
                exit();
            }
        }
        catch (PDOException $e) { //
            echo "Error: " . $e->getMessage();
        }
    }

}
