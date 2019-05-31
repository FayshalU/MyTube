<?php 
    require_once("header.php"); 
    require_once("classes/videoPlayer.php");
    require_once("classes/commentData.php");
    require_once("classes/comment.php");

    if(!isset($_GET["id"])){
        echo "No video found";
    }
    else{
        $video = new Video($con, $_GET["id"], $user);

        //echo $video->getTitle();

        if($video->getTitle() != ""){

            $video->incrementViews();
        }
        else{
            echo "No video found";
        }
        
    }
?>

<div class="leftColumn">

<?php
    $videoPlayer = new VideoPlayer($video);
    echo $videoPlayer->create(true);

    echo $video->getPrimaryInfo();
    echo $video->getSecondaryInfo();

    $comment = new CommentData($con, $video, $user);
    echo $comment->create(true);

?>

</div>

<div class="suggestion">
</div>

<?php require_once("footer.php"); ?>

<script src="assets/js/videoActions.js"></script>
