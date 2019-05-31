<?php 
    require_once("header.php"); 

    if(!isset($_POST["uploadBtn"])){
        echo "No files found to upload";
        exit();
    }

    $userId = $user->getUsername();

    //var_dump($_FILES["uploadFile"]);
    $isValid = true;
    $sizeLimit = 500000000;
    $fileType = array("mp4","avc", "avi", "vob", "mkv", "webm", "mov", "flv", "wmv", "mpg", "3gp", "amv", "mpg4");
    $ffmpegPath = realpath("ffmpeg/bin/ffmpeg.exe");
    
    $targetDir = "uploads/videos";
    $tempFilePath = $targetDir."/". uniqid(). basename($_FILES["uploadFile"]["name"]);

    $tempFilePath = str_replace(" ", "_", $tempFilePath);
    //echo $tempFilePath;

    $videoType = pathinfo($tempFilePath, PATHINFO_EXTENSION);
    $size = $_FILES["uploadFile"]["size"];
    //echo $size;

    if(!in_array(strtolower($videoType), $fileType))
    {
        echo "File is not allowed";
        $isValid = false;
    }
    else if($size > $sizeLimit){
        echo "File too large";
        $isValid = false;
    }
    else if($_FILES["uploadFile"]["error"] != 0){
        echo "Error code: ". $_FILES["uploadFile"]["error"];
        $isValid = false;
    }
    elseif(!$isValid){

    }
    else{
        if(move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $tempFilePath)){
            //echo "File uploaded successfully";

            $duration = getDuration($tempFilePath);

            $finalFilePath = $targetDir."/". uniqid(). ".mp4";

            $query = $con->prepare("INSERT INTO videos(title, uploadBy, description, privacy, category, filepath, duration)
                            VALUES(:title, :uploadBy, :description, :privacy, :category, :filepath, :duration)");

            $query->bindParam(":title", $_POST["title"]);
            $query->bindParam(":uploadBy", $userId);
            $query->bindParam(":description", $_POST["description"]);
            $query->bindParam(":privacy", $_POST["privacy"]);
            $query->bindParam(":category", $_POST["category"]);
            $query->bindParam(":filepath", $finalFilePath);
            $query->bindParam(":duration", $duration);

            if($query->execute()){
                //echo "Inserted successfully";

                if(!convertVideo($tempFilePath, $finalFilePath)){
                    echo "Conversion Failed";
                }
                else{
                    //echo "Converted successfully";

                    if(!deleteTempVideo($tempFilePath)){
                        echo "Could not delete file";
                    }
                    else{
                        //echo "File deleted";

                        if(!generateThumbnails($finalFilePath, $con)){

                        }
                        else{
                            echo "Video uploaded successfully!";
                        }
                    }
                }
            }
            else{
                echo "Inserting failed";
            }
        }
        else{
            echo "Uploading failed";
        }
    }



    function convertVideo($tempPath, $finalPath){

        $ffmpegPath = realpath("ffmpeg/bin/ffmpeg.exe");

        $cmd = $ffmpegPath ." -i ". $tempPath." ". $finalPath. " 2>&1";

        $outputLog = array();
        exec($cmd, $outputLog, $returnCode);

        if($returnCode != 0){
            foreach($outputLog as $line){
                echo $line ."<br>";
            }
            return false;
        }
        else{
            return true;
        }
    }
    
    function deleteTempVideo($filePath){
        if(!unlink($filePath)){
            return false;
        }

        return true;
    }

    function generateThumbnails($filePath, $con){
        $size = "210x118";
        $num = 3;
        $thumbPath = "uploads/videos/thumbnails";

        $ffprobePath = realpath("ffmpeg/bin/ffprobe.exe");
        $ffmpegPath = realpath("ffmpeg/bin/ffmpeg.exe");

        $duration = (int)shell_exec("$ffprobePath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filePath");
        //echo "duration: $duration";
        $videoid = $con->lastInsertId();

        for($i=1; $i<= $num; $i++){
            $imageName = uniqid().".jpg";
            $interval = ($duration*0.8)/ $num * $i;

            $fullThumbPath = "$thumbPath/$videoid-$imageName";

            $cmd = "$ffmpegPath -i $filePath -ss $interval -s $size -vframes 1 $fullThumbPath 2>&1";

            $outputLog = array();
            exec($cmd, $outputLog, $returnCode);

            if($returnCode != 0){
                foreach($outputLog as $line){
                    echo $line ."<br>";
                }
            }

            $query = $con->prepare("INSERT INTO thumbnails(videoid, path, selected)
                            VALUES(:videoid, :path, :selected)") ;
            $query->bindParam(":videoid", $videoid);
            $query->bindParam(":path", $fullThumbPath);
            $query->bindParam(":selected", $selected);

            $selected = ($i==1)? 1:0;

            $success = $query->execute();

            if(! $success){
                echo "Could not insert thumbnails";
                return false;
            }
        }

        return true;
    }

    function getDuration($filePath){

        $ffprobePath = realpath("ffmpeg/bin/ffprobe.exe");
        $duration =  (int)shell_exec("$ffprobePath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filePath");

        $hours = floor($duration/3600);
        $mins = floor(($duration- ($hours*3600)) / 60);
        $secs = floor($duration % 60);

        if($hours < 1){
            $hours = "";
        }
        else{
            $hours = $hours. ":";
        }
        $mins = ($mins < 10)? "0".$mins.":" : $mins.":";
        $secs = ($secs < 10)? "0".$secs : $secs;

        // echo "<br> $hours";
        // echo "<br> $mins";
        // echo "<br> $secs";

        return $hours.$mins.$secs;
    }
?>