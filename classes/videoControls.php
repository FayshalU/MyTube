<?php
    require_once("classes/buttonProvider.php");

    class VideoControls{

        private $video, $user;

        public function __construct($video, $user){
            $this->video = $video;
            $this->user = $user;
        }

        public function create(){

            $likebtn = $this->createLikeBtn();
            $dislikebtn = $this->createDislikeBtn();

            return "<div class='controls'>
                        $likebtn
                        $dislikebtn
                    </div>";
        }

        public function createLikeBtn(){

            $text = $this->video->getLikes();
            $videoId = $this->video->getId();
            $action = "likeVideo(this, $videoId)";
            $class = "likeButton";

            $image = null;
            if($this->video->isLiked()){
                $image = "<i class='fa fa-thumbs-up fa-lg' aria-hidden='true'></i>";
            }
            else{
                $image = "<i class='fa fa-thumbs-o-up fa-lg' aria-hidden='true'></i>";
            }

            return ButtonProvider::createButton($text, $image, $action, $class);
        }

        public function createDislikeBtn(){
            
            $text = $this->video->getDislikes();
            $videoId = $this->video->getId();
            $action = "dislikeVideo(this, $videoId)";
            $class = "dislikeButton";

            $image = null;
            if($this->video->isDisliked()){
                $image = "<i class='fa fa-thumbs-down fa-lg' aria-hidden='true'></i>";
            }
            else{
                $image = "<i class='fa fa-thumbs-o-down fa-lg' aria-hidden='true'></i>";
            }
            
            return ButtonProvider::createButton($text, $image, $action, $class);
        }
    }

?>