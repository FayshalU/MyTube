<?php
    require_once("../config.php");

    $videoId = $_POST["videoId"];
    $username = $_SESSION["user"];

    $query = $con->prepare("SELECT * FROM dislikes WHERE username=:username AND videoId=:videoId");
    $query->bindParam(":username", $username);
    $query->bindParam(":videoId", $videoId);

    $query->execute();

    if($query->rowCount() > 0){
        $query = $con->prepare("DELETE FROM dislikes WHERE username=:username AND videoId=:videoId");
        $query->bindParam(":username", $username);
        $query->bindParam(":videoId", $videoId);

        $query->execute();


        $result = array(
            "likes"=>0,
            "dislikes"=>-1
        );

        echo json_encode($result);
    }

    else{

        $query = $con->prepare("DELETE FROM likes WHERE username=:username AND videoId=:videoId");
        $query->bindParam(":username", $username);
        $query->bindParam(":videoId", $videoId);
        $query->execute();

        $count = $query->rowCount();

        $query = $con->prepare("INSERT INTO dislikes(username, videoId) VALUES(:username, :videoId)");
        $query->bindParam(":username", $username);
        $query->bindParam(":videoId", $videoId);

        $query->execute();

        $result = array(
            "likes"=>(0-$count),
            "dislikes"=>1
        );

        echo json_encode($result);
    }

?>