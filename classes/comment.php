<?php

    require_once("buttonProvider.php");

    class Comment{

        private $con, $data, $videoId, $user;

        public function __construct($con, $input, $videoId, $user){
            $this->con = $con;
            $this->videoId = $videoId;
            $this->user = $user;

            if(!is_array($input)){

                $query = $con->prepare("SELECT * FROM comments WHERE id=:id");
                $query->bindParam(":id", $input);
                $query->execute();

                $input = $query->fetch(PDO::FETCH_ASSOC);
            }

            $this->data = $input;
        }

        public function create(){

            $body = $this->data["body"];
            $postedBy = $this->data["postedBy"];

            $profileButton = ButtonProvider::createProfileButton($this->con, $postedBy);
            //$action = "postComment(this, \"$postedBy\", $videoId, null, \"comments\")";
            $timespan = "";

            //$commentBtn = ButtonProvider::createButton("COMMENT", null, $action, "postComment");
            
            return "<div class='itemContainer'>
                        <div class='comment'>
                            $profileButton
                            <div class='mainContainer'>
                                <div class='commentHeader'>
                                    <a href='profile.php?username=$postedBy'
                                        <span class='username'>$postedBy</span>
                                    </a>
                                    <span class='timespan'>$timespan</span>
                                </div>

                                <div class='body'>
                                    $body
                                </div>
                            </div>
                        </div>
                       
                    </div>";
        }

    }

?>