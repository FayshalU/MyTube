<?php

    class CommentData{

        private $con, $video, $user;

        public function __construct($con, $video, $user){
            $this->con = $con;
            $this->video = $video;
            $this->user = $user;
        }

        public function create(){

            $numComments = $this->video->getComments();
            $videoId = $this->video->getId();
            $postedBy = $this->user->getUsername();

            $profileButton = ButtonProvider::createProfileButton($this->con, $postedBy);
            $action = "postComment(this, \"$postedBy\", $videoId, null, \"comments\")";

            $commentBtn = ButtonProvider::createButton("COMMENT", null, $action, "postComment");
            
            return "<div class='commentSection'>
                        <div class='header'>
                            <span class='commentCount'>$numComments Comments</span>

                            <div class='commentForm'>
                                $profileButton
                                <textarea class='commentBodyClass' placeholder='Add a comment'></textarea>
                                $commentBtn
                            </div>
                        </div>
                        <div class='comments'>
                        </div>
                    </div>";
        }

    }

?>