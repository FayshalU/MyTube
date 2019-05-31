<?php

    require_once("../config.php");
    require_once("../classes/comment.php");
    require_once("../classes/user.php");

    $text = $_POST["text"];
    $postedBy = $_POST["postedBy"];
    $videoId = $_POST["videoId"];
    $responseTo = $_POST["responseTo"];
    $user = new User($con, $_SESSION["user"]);

    //echo $text;

    $query = $con->prepare("INSERT INTO comments(postedBy, videoId, responseTo, body) 
                            VALUES(:postedBy, :videoId, :responseTo, :body)");
    $query->bindParam(":postedBy", $postedBy);
    $query->bindParam(":videoId", $videoId);
    $query->bindParam(":responseTo", $responseTo);
    $query->bindParam(":body", $text);
    $query->execute();

    $newComment = new Comment($con, $con->lastInsertId(), $videoId, $user);
    echo $newComment->create();

?>