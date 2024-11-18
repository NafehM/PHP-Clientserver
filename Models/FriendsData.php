<?php
//requiring the UserExtendedData class to be used in FriendsData class
require_once './Models/UserExtendedData.php';

//FriendData class extends the UserExtendedData class
class FriendsData extends UserExtendedData
{
    //these are all fields joined with users friends fields.
    protected  $friends_id, $sending_from, $sending_to, $status;

    //passing the result of query to the constructor to create the FriendData object.
    public function __construct($dbRow)
    {
        //calling the parent constructor and passing
        //query parameter to process fields inside parent class
        parent::__construct($dbRow);
        //following the extended fields in FriendData class

        $this->friends_id = $dbRow['friendship_id'];
        $this->sending_from = $dbRow['sending_from'];
        $this->sending_to = $dbRow['sending_to'];
        $this->status = $dbRow['status'];

    }

    /**
     * @return mixed
     */
    public function getFriendsId(): mixed
    {
        return $this->friends_id;
    }

    /**
     * @return mixed
     */
    public function getSendingFrom(): mixed
    {
        return $this->sending_from;
    }

    /**
     * @return mixed
     */
    public function getSendingTo(): mixed
    {
        return $this->sending_to;
    }

    /**
     * @return mixed
     */
    public function getStatus(): mixed
    {
        return $this->status;
    }





}