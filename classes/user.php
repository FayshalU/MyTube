<?php

    class User{

        private $con, $data;

        public function __construct($con, $username){
            $this->con = $con;

            $query = $con->prepare("SELECT * FROM users WHERE username=:username");
            $query->bindParam(":username", $username);
            $query->execute();

            $this->data = $query->fetch(PDO::FETCH_ASSOC);
        }

        public static function isLoggedIn(){
            return isset($_SESSION["user"]);
        }

        public function getName(){
            return $this->data["firstName"]." ".$this->data["lastName"];
        }

        public function getFirstName(){
            return $this->data["firstName"];
        }

        public function getLastName(){
            return $this->data["lastName"];
        }

        public function getUsername(){
            return $this->data["username"];
        }

        public function getEmail(){
            return $this->data["email"];
        }

        public function getSignUpDate(){
            return $this->data["signUpDate"];
        }

        public function getPicture(){
            return $this->data["picture"];
        }

        public function isSubscribedTo($userTo){
            $username = $this->getUsername();
            $query = $this->con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
            $query->bindParam(":userTo", $userTo);
            $query->bindParam(":userFrom", $username);
            $query->execute();

            return $query->rowCount() > 0;
        }

        public function getSubscriberCount(){
            $username = $this->getUsername();

            $query = $this->con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo");
            $query->bindParam(":userTo", $username);
            $query->execute();

            return $query->rowCount();
        }
    }

?>