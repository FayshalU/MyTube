<?php

    require_once("classes/videoControls.php");

    class Video{

        private $con, $data, $user;

        public function __construct($con, $id, $user){
            $this->con = $con;
            $this->user = $user;

            $query = $con->prepare("SELECT * FROM videos WHERE id=:id");
            $query->bindParam(":id", $id);
            $query->execute();

            $this->data = $query->fetch(PDO::FETCH_ASSOC);
        }

        public function getId(){
            return $this->data["id"];
        }

        public function getUploadBy(){
            return $this->data["uploadBy"];
        }

        public function getTitle(){
            return $this->data["title"];
        }

        public function getDescription(){
            return $this->data["description"];
        }

        public function getPrivacy(){
            return $this->data["privacy"];
        }

        public function getCategory(){
            return $this->data["category"];
        }

        public function getFilePath(){
            return $this->data["filePath"];
        }

        public function getUploadDate(){
            $date = $this->data["uploadDate"];

            return date("M j, Y", strtotime($date));
        }

        public function getViews(){
            return $this->data["views"];
        }

        public function getDuration(){
            return $this->data["duration"];
        }

        public function incrementViews(){
            $id = $this->getId();

            $query = $this->con->prepare("UPDATE videos SET views=views+1 WHERE id=:id");
            $query->bindParam(":id", $id);
            $query->execute();

            $this->data["views"] += 1;
        }

        public function getPrimaryInfo(){
            $title = $this->getTitle();
            $views = $this->getViews();

            $infoControls = new VideoControls($this, $this->user);
            $controls = $infoControls->create();

            return "<div class='videoInfo'>
                        <h1>$title</h1>
                        <div class='bottomInfo'>
                            <span class='view'>$views views</span>
                            $controls
                        </div>
                    </div>";
        }

        public function getSecondaryInfo(){

            $description = $this->getDescription();
            $uploadDate = $this->getUploadDate();
            $uploadBy = $this->getUploadBy();
            $profile = ButtonProvider::createProfileButton($this->con, $uploadBy);

            if($uploadBy == $this->user->getUsername()){
                $actionbtn = ButtonProvider::editVideoButton($this->getId());
            }
            else{
                $userTo = new User($this->con, $uploadBy);
                $actionbtn = ButtonProvider::createSubscriberButton($this->con, $userTo, $this->user);
            }

            return "<div class='secondaryInfo'>
                        <div class='topRow'>
                            $profile

                            <div class='uploadInfo'>
                                <span class='owner'>
                                    <a href='profile.php?username=$uploadBy'>
                                        $uploadBy
                                    </a>
                                </span>
                                <span class='date'>Published on $uploadDate</span>
                            </div>
                            $actionbtn
                        </div>

                        <div class='descriptionContainer'>
                            $description
                        </div>
                    </div>";
        }

        public function getLikes(){
            $videoId = $this->getId();

            $query = $this->con->prepare("SELECT count(*) as 'count' FROM likes WHERE videoId = :videoId");
            $query->bindParam(":videoId", $videoId);
            $query->execute();

            $data = $query->fetch(PDO::FETCH_ASSOC);

            return $data["count"];
        }

        public function getDislikes(){
            $videoId = $this->getId();
            
            $query = $this->con->prepare("SELECT count(*) as 'count' FROM dislikes WHERE videoId = :videoId");
            $query->bindParam(":videoId", $videoId);
            $query->execute();

            $data = $query->fetch(PDO::FETCH_ASSOC);

            return $data["count"];
        }

        public function isLiked(){

            $username = $this->user->getUsername();
            $videoId = $this->getId();

            $query = $this->con->prepare("SELECT * FROM likes WHERE username=:username AND videoId=:videoId");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $videoId);
            $query->execute();

            return ($query->rowCount() > 0);
        }

        public function isDisliked(){

            $username = $this->user->getUsername();
            $videoId = $this->getId();

            $query = $this->con->prepare("SELECT * FROM dislikes WHERE username=:username AND videoId=:videoId");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $videoId);
            $query->execute();

            return ($query->rowCount() > 0);
        }

        public function getComments(){

            $videoId = $this->getId();

            $query = $this->con->prepare("SELECT count(*) as 'count' FROM comments WHERE videoId = :videoId");
            $query->bindParam(":videoId", $videoId);
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);

            return $data["count"];
        }
    }

?>