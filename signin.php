<?php
    require_once("config.php");
    require_once("classes/Errors.php");

    $username = $password = null;
    $errorArray = array();

    if(isset($_POST["submitbtn"])){
        global $errorArray;

        $username = strip_tags($_POST["username"]);
        $username = str_replace(" ", "", $username);
        $password = strip_tags($_POST["password"]);

        $pa = hash("sha512", $password);

        $query = $con->prepare("SELECT * FROM users WHERE username=:username AND password=:password");

        $query->bindParam(":username", $username);
        $query->bindParam(":password", $pa);
        $query->execute();

        if($query->rowCount() == 1){
            //echo "Successfull";
            $_SESSION["user"] = $username;
            header("Location: index.php");
        }
        else{
            array_push($errorArray, Errors::$login);
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MyTube</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" media="screen" href="assets/lib/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="assets/lib/css/font-awesome.css">
        <link rel="stylesheet" type="text/css" media="screen" href="assets/css/style.css">
        <link rel="stylesheet" type="text/css" media="screen" href="assets/css/signin.css">

        <script src="assets/lib/js/jquery-3.3.1.js"></script>
        <script src="assets/lib/js/popper.min.js"></script>
        <script src="assets/lib/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="signInContainer">
            <div class="column">
                <div class="header">
                    <center><img src="assets/images/yt.png" alt="MyTube" title="Logo"></center>
                    <h3>Sign In</h3>
                    <span>to continue to MyTube</span>
                </div>

                <div class="loginForm">
                    <form action="signin.php" method="POST">
                        <input type="text" name="username" placeholder="Username" value="<?=$username?>" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <input type="submit" name="submitbtn" value="SUBMIT">
                        <?php
                            if(in_array(Errors::$login, $errorArray)){
                                echo "<span class='errorMessage'>".Errors::$login."</span>";
                            }
                        ?>
                    </form>
                </div>
                <br>
                <a class="signInMessage" href="signup.php">Don't have an account? Go to Sign Up</a>
            </div>
        </div>
    </body>
</html>