<?php

class UserData implements JsonSerializable
{
    protected $_user_id,  $_firstName, $_lastName, $_email, $_photoName ,$username;

    //constructor of the class
    public function __construct($dbRow) {
        $this->username = $dbRow['username'];
        $this->_user_id = $dbRow['user_id'];
        $this->_firstName = $dbRow['first_name'];
        $this->_lastName = $dbRow['last_name'];
        $this->_email = $dbRow['email'];
        $this->_photoName = $dbRow['photo_name'];

    }

    Public function jsonSerialize(){
        return[


            "username" => $this->username,
            "first_name"=> $this->_firstName,
            "last_name"=> $this->_lastName,
            "email"=>$this->_email,
            "photo_name"=>$this->_photoName,


        ];
    }

    /**
     * @return mixed
     */
    public function getUsername(): mixed
    {
        return $this->username;
    }

    public function getUserId() {
        return $this->_user_id;
    }

    public function getFirstName() {
        return $this->_firstName;
    }
    public function getLastName() {
        return $this->_lastName;
    }
    public function getEmail() {
        return $this->_email;
    }
    public function getPhotoName() {
        return $this->_photoName;
    }

}